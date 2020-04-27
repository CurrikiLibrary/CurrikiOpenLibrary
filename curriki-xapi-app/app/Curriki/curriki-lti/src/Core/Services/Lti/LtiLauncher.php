<?php
namespace CurrikiLti\Core\Services\Lti;
use Doctrine\ORM\EntityManager;
use CurrikiLti\Core\Services\Lti\Launch;
use CurrikiLti\Core\Entity\LtiResource;

class LtiLauncher
{
    protected $entityManager = null;
    protected $lti_configuration = null;
    protected $launch = null;
    protected $user = null;
    protected $course = null;
    protected $lti_resource = null;
    protected $resource_params = array('resourceid' => 0, 'component' => 'curriki-resource');
    
    public function __construct(EntityManager $entityManager, LtiConfiguration $lti_configuration, Launch $launch, LtiResource $lti_resource)
    {
        $this->entityManager = $entityManager;
        $this->lti_configuration = $lti_configuration;   
        $this->launch = $launch;     
        $this->lti_resource = $lti_resource;     
    }

    public function setResourceParams($params)
    {
        $this->resource_params = $params;
    }

    public function getResourceParams(){
        return $this->resource_params;
    }

    public function setCourse($course){
        $this->course = $course;
    }

    public function getCourse(){
        return $this->course;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function launch($lti_id)
    {        
        $frame_output = '';        
        // Request the launch content with an iframe tag.
        $frame_output.= '<iframe id="contentframe" height="100%" width="100%" frameborder="0" scrolling="auto" src="/lti-launch?id='.$lti_id.'&u=[[lti_user_data]]" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';

        // Output script to make the iframe tag be as large as possible.
        /*$frame_output .= '
        <script type="text/javascript">
        //<![CDATA[
            YUI().use("node", "event", function(Y) {
                var doc = Y.one("body");
                var frame = Y.one("#contentframe");
                var padding = 15; //The bottom of the iframe wasn\'t visible on some themes. Probably because of border widths, etc.
                var lastHeight;
                var resize = function(e) {
                    var viewportHeight = doc.get("winHeight");
                    if(lastHeight !== Math.min(doc.get("docHeight"), viewportHeight)){
                        frame.setStyle("height", viewportHeight - frame.getY() - padding + "px");
                        lastHeight = Math.min(doc.get("docHeight"), doc.get("winHeight"));
                    }
                };

                resize();

                Y.on("windowresize", resize);
            });
        //]]
        </script>
        ';*/

        return $frame_output;
        
    }
    
    public function getUrl($lti_id){
        return '/lti-launch?id='.$lti_id;
    }

    public function launchContent()
    {
        $lti_id = $_GET['id'];        
        $lti = $this->entityManager->getRepository('CurrikiLti\Core\Entity\Lti')->find($lti_id);        
        if(is_null($lti)){
            die('Tool Not Found');
        }

        $lti_resource = null;
        if( $this->getResourceParams()['resourceid'] > 0 ){            
            $lti_resource = $this->entityManager->getRepository('CurrikiLti\Core\Entity\LtiResource')->findOneBy(array(
                'resourceid' => $this->getResourceParams()['resourceid'], 
                'ltiid' => $lti->getId(),
                'component' => $this->getResourceParams('component')
            ) );
            if(is_null($lti_resource)){
                $this->lti_resource->setLti($lti);
                $this->lti_resource->setResourceId($this->getResourceParams()['resourceid']);
                $this->lti_resource->setComponent($this->getResourceParams()['component']);
                $this->entityManager->persist($this->lti_resource);
                $this->entityManager->flush();
                $lti_resource = $this->lti_resource;
            }
        }
        
        $toolconfig = $this->lti_configuration->getTypeConfig($lti->typeid);
        $launchcontainer = $this->lti_configuration->getLaunchContainer($lti, $toolconfig);
        $config = $this->lti_configuration->getTypeTypeConfig($lti->typeid);
        $content = 'Configurations not found.';
        if ($config->lti_ltiversion === LTI_VERSION_1P3) {                        
            $content = $this->initiateLogin(101, 102, $lti, $config);
        }else{                        
            $this->launch->lti_configuration = $this->lti_configuration;
            $this->launch->USER = $this->getUser();
            $this->launch->course = $this->getCourse();
            $content = $this->launch->lti_launch_tool($lti);
        }        
        return $content;
    }
    
    public function initiateLogin($courseid, $id, $instance, $config, $messagetype = 'basic-lti-launch-request', $title = '', $text = '') {
        
        if (!empty($instance)) {
            $endpoint = !empty($instance->toolurl) ? $instance->toolurl : $config->lti_toolurl;
        } else {
            $endpoint = $config->lti_toolurl;
            if (($messagetype === 'ContentItemSelectionRequest') && !empty($config->lti_toolurl_ContentItemSelectionRequest)) {
                $endpoint = $config->lti_toolurl_ContentItemSelectionRequest;
            }
        }
        $endpoint = trim($endpoint);

        // If SSL is forced make sure https is on the normal launch URL.
        if (isset($config->lti_forcessl) && ($config->lti_forcessl == '1')) {
            $endpoint = lti_ensure_url_is_https($endpoint);
        } else if (!strstr($endpoint, '://')) {
            $endpoint = 'http://' . $endpoint;
        }

        $params = array();
        $http = $this->isSecure() ? 'https://':'http://';
        $host = $http . $_SERVER['SERVER_NAME'] . '/';        
        $params['iss'] = $host;
        $params['target_link_uri'] = $endpoint;
        $params['login_hint'] = $this->user->id;
        $params['lti_message_hint'] = $id;
          
        $_SESSION['lti_message_hint'] = "{$courseid},{$config->typeid},{$id}," . base64_encode($title) . ',' .
            base64_encode($text);

        $r = "<form action=\"" . $config->lti_initiatelogin .
            "\" name=\"ltiInitiateLoginForm\" id=\"ltiInitiateLoginForm\" method=\"post\" " .
            "encType=\"application/x-www-form-urlencoded\">\n";

        foreach ($params as $key => $value) {
            $key = htmlspecialchars($key);
            $value = htmlspecialchars($value);
            $r .= "  <input type=\"hidden\" name=\"{$key}\" value=\"{$value}\"/>\n";
        }
        $r .= "</form>\n";
        
        $r .= "<script type=\"text/javascript\">\n" .
            "//<![CDATA[\n" .
            "document.ltiInitiateLoginForm.submit();\n" .
            "//]]>\n" .
            "</script>\n";

        return $r;
    }

    function isSecure() {
        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
    }
}