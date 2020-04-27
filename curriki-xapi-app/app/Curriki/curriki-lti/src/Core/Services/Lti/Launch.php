<?php
namespace CurrikiLti\Core\Services\Lti;

class Launch
{
    public $lti_configuration = null;
    public $USER = null;
    public $course = null;

    public function lti_launch_tool($instance) {

        list($endpoint, $parms) = $this->lti_get_launch_data($instance);
        $debuglaunch = ( $instance->debuglaunch == 1 );
    
        $content = $this->lti_post_launch_html($parms, $endpoint, $debuglaunch);
    
        //echo $content;
        return $content;
    }

    public function lti_get_launch_data($instance, $nonce = '') {
        global $PAGE, $CFG, $USER;
        
        if (empty($instance->typeid)) {
            $tool = $this->lti_get_tool_by_url_match($instance->toolurl, $instance->course);
            if ($tool) {
                $typeid = $tool->id;
                $ltiversion = $tool->ltiversion;
            } else {
                $tool = lti_get_tool_by_url_match($instance->securetoolurl,  $instance->course);
                if ($tool) {
                    $typeid = $tool->id;
                    $ltiversion = $tool->ltiversion;
                } else {
                    $typeid = null;
                    $ltiversion = LTI_VERSION_1;
                }
            }            
        } else {
            $typeid = $instance->typeid;            
            //$tool = lti_get_type($typeid);
            $tool = $instance->getLtiType();
            $ltiversion = $tool->ltiversion;
        }
    
        if ($typeid) {            
            //$typeconfig = lti_get_type_config($typeid);
            $typeconfig = $this->lti_configuration->getTypeConfig($typeid);
        } else {
            // There is no admin configuration for this tool. Use configuration in the lti instance record plus some defaults.
            $typeconfig = (array)$instance;
    
            $typeconfig['sendname'] = $instance->instructorchoicesendname;
            $typeconfig['sendemailaddr'] = $instance->instructorchoicesendemailaddr;
            $typeconfig['customparameters'] = $instance->instructorcustomparameters;
            $typeconfig['acceptgrades'] = $instance->instructorchoiceacceptgrades;
            $typeconfig['allowroster'] = $instance->instructorchoiceallowroster;
            $typeconfig['forcessl'] = '0';
        }
    
        // Default the organizationid if not specified.
        if (empty($typeconfig['organizationid'])) {                        
            //$urlparts = parse_url($CFG->wwwroot);
            $scheme = $this->check_https() ? 'https':'http';            
            $wwwroot = (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] ?: $scheme) . '://' . $_SERVER['HTTP_HOST'];
            $urlparts = parse_url($wwwroot);    
            $typeconfig['organizationid'] = $urlparts['host'];
        }
        
        if (isset($tool->toolproxyid)) {
            /*$toolproxy = lti_get_tool_proxy($tool->toolproxyid);
            $key = $toolproxy->guid;
            $secret = $toolproxy->secret;*/
        } else {
            $toolproxy = null;
            if (!empty($instance->resourcekey)) {
                $key = $instance->resourcekey;
            } else if ($ltiversion === LTI_VERSION_1P3) {
                $key = $tool->clientid;
            } else if (!empty($typeconfig['resourcekey'])) {
                $key = $typeconfig['resourcekey'];
            } else {
                $key = '';
            }
            if (!empty($instance->password)) {
                $secret = $instance->password;
            } else if (!empty($typeconfig['password'])) {
                $secret = $typeconfig['password'];
            } else {
                $secret = '';
            }
        }
    
        $endpoint = !empty($instance->toolurl) ? $instance->toolurl : $typeconfig['toolurl'];
        $endpoint = trim($endpoint);
    
        // If the current request is using SSL and a secure tool URL is specified, use it.
        if ($this->lti_request_is_using_ssl() && !empty($instance->securetoolurl)) {
            $endpoint = trim($instance->securetoolurl);
        }
    
        // If SSL is forced, use the secure tool url if specified. Otherwise, make sure https is on the normal launch URL.
        if (isset($typeconfig['forcessl']) && ($typeconfig['forcessl'] == '1')) {
            if (!empty($instance->securetoolurl)) {
                $endpoint = trim($instance->securetoolurl);
            }
    
            $endpoint = $this->lti_ensure_url_is_https($endpoint);
        } else {
            if (!strstr($endpoint, '://')) {
                $endpoint = 'http://' . $endpoint;
            }
        }
    
        $orgid = $typeconfig['organizationid'];        
        $islti2 = isset($tool->toolproxyid);
        $allparams = $this->lti_build_request($instance, $typeconfig, $this->course, $typeid, $islti2);        
        /*if ($islti2) {
            $requestparams = lti_build_request_lti2($tool, $allparams);
        } else {
            $requestparams = $allparams;
        }*/
        $requestparams = $allparams;
        $requestparams = array_merge($requestparams, $this->lti_build_standard_message($instance, $orgid, $ltiversion));
        $customstr = '';
        if (isset($typeconfig['customparameters'])) {
            $customstr = $typeconfig['customparameters'];
        }
        $requestparams = array_merge($requestparams, $this->lti_build_custom_parameters($toolproxy, $tool, $instance, $allparams, $customstr,
            $instance->instructorcustomparameters, $islti2));
    
        $launchcontainer = $this->lti_get_launch_container($instance, $typeconfig);
        $returnurlparams = array('course' => $this->course->id,
                                 'launch_container' => $launchcontainer,
                                 'instanceid' => $instance->id,
                                 'sesskey' => rand());
    
        // Add the return URL. We send the launch container along to help us avoid frames-within-frames when the user returns.
        /*$url = new \moodle_url('/mod/lti/return.php', $returnurlparams);
        $returnurl = $url->out(false);
    
        if (isset($typeconfig['forcessl']) && ($typeconfig['forcessl'] == '1')) {
            $returnurl = lti_ensure_url_is_https($returnurl);
        }*/
    
        $target = '';
        switch($launchcontainer) {
            case LTI_LAUNCH_CONTAINER_EMBED:
            case LTI_LAUNCH_CONTAINER_EMBED_NO_BLOCKS:
                $target = 'iframe';
                break;
            case LTI_LAUNCH_CONTAINER_REPLACE_MOODLE_WINDOW:
                $target = 'frame';
                break;
            case LTI_LAUNCH_CONTAINER_WINDOW:
                $target = 'window';
                break;
            default:
                $target = 'window';
                break;
        }
        $requestparams['launch_presentation_document_target'] = $target;

        /*if (!empty($target)) {
            $requestparams['launch_presentation_document_target'] = $target;
        }
    
        $requestparams['launch_presentation_return_url'] = $returnurl;*/
        
        // Add the parameters configured by the LTI services.
        /*if ($typeid && !$islti2) {
            $services = $this->lti_get_services();
            foreach ($services as $service) {
                $serviceparameters = $service->get_launch_parameters('basic-lti-launch-request',
                        $course->id, $USER->id , $typeid, $instance->id);
                foreach ($serviceparameters as $paramkey => $paramvalue) {
                    $requestparams['custom_' . $paramkey] = lti_parse_custom_parameter($toolproxy, $tool, $requestparams, $paramvalue,
                        $islti2);
                }
            }
        }*/
    
        // Allow request params to be updated by sub-plugins.
        /*$plugins = core_component::get_plugin_list('ltisource');
        foreach (array_keys($plugins) as $plugin) {
            $pluginparams = component_callback('ltisource_'.$plugin, 'before_launch',
                array($instance, $endpoint, $requestparams), array());
    
            if (!empty($pluginparams) && is_array($pluginparams)) {
                $requestparams = array_merge($requestparams, $pluginparams);
            }
        }*/
        
        if ((!empty($key) && !empty($secret)) || ($ltiversion === LTI_VERSION_1P3)) {
            if ($ltiversion !== LTI_VERSION_1P3) {
                $parms = $this->lti_sign_parameters($requestparams, $endpoint, 'POST', $key, $secret);
            } else {
                //$parms = lti_sign_jwt($requestparams, $endpoint, $key, $typeid, $nonce);
                $parms = array();
            }
                        
            // $endpointurl = new \moodle_url($endpoint);
            // $endpointparams = $endpointurl->params();
            $endpointparams = array();
            parse_str(parse_url($endpoint,PHP_URL_QUERY),$endpointparams);            
                
            // Strip querystring params in endpoint url from $parms to avoid duplication.
            if (!empty($endpointparams) && !empty($parms)) {
                foreach (array_keys($endpointparams) as $paramname) {
                    if (isset($parms[$paramname])) {
                        unset($parms[$paramname]);
                    }
                }
            }
    
        } else {
            // If no key and secret, do the launch unsigned.
            $returnurlparams['unsigned'] = '1';
            $parms = $requestparams;
        }
                
        return array($endpoint, $parms);
    }

    public function lti_sign_parameters($oldparms, $endpoint, $method, $oauthconsumerkey, $oauthconsumersecret) {
        
        $parms = $oldparms;
    
        $testtoken = '';
        require_once __DIR__.'/OAuth.php';        
        // TODO: Switch to core oauthlib once implemented - MDL-30149.       
        $hmacmethod = new \OAuthSignatureMethod_HMAC_SHA1();        
        $testconsumer = new \OAuthConsumer($oauthconsumerkey, $oauthconsumersecret, null);
        $accreq = \OAuthRequest::from_consumer_and_token($testconsumer, $testtoken, $method, $endpoint, $parms);
        $accreq->sign_request($hmacmethod, $testconsumer, $testtoken);
    
        $newparms = $accreq->get_parameters();
    
        return $newparms;
    }

    public function lti_get_launch_container($lti, $toolconfig) {
        if (empty($lti->launchcontainer)) {
            $lti->launchcontainer = LTI_LAUNCH_CONTAINER_DEFAULT;
        }
    
        if ($lti->launchcontainer == LTI_LAUNCH_CONTAINER_DEFAULT) {
            if (isset($toolconfig['launchcontainer'])) {
                $launchcontainer = $toolconfig['launchcontainer'];
            }
        } else {
            $launchcontainer = $lti->launchcontainer;
        }
    
        if (empty($launchcontainer) || $launchcontainer == LTI_LAUNCH_CONTAINER_DEFAULT) {
            $launchcontainer = LTI_LAUNCH_CONTAINER_EMBED_NO_BLOCKS;
        }
    
        //$devicetype = core_useragent::get_device_type();
    
        // Scrolling within the object element doesn't work on iOS or Android
        // Opening the popup window also had some issues in testing
        // For mobile devices, always take up the entire screen to ensure the best experience.
        /*if ($devicetype === core_useragent::DEVICETYPE_MOBILE || $devicetype === core_useragent::DEVICETYPE_TABLET ) {
            $launchcontainer = LTI_LAUNCH_CONTAINER_REPLACE_MOODLE_WINDOW;
        }*/
    
        return $launchcontainer;
    }

    public function check_https() {	
        if ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443) {            
            return true; 
        }
        return false;
    }

    public function lti_get_tool_by_url_match($url, $courseid = null, $state = LTI_TOOL_STATE_CONFIGURED) {
        $possibletools = $this->lti_get_tools_by_url($url, $state, $courseid);
    
        return lti_get_best_tool_by_url($url, $possibletools, $courseid);
    }

    public function lti_get_tools_by_url($url, $state, $courseid = null) {
        $domain = $this->lti_get_domain_from_url($url);
    
        return lti_get_tools_by_domain($domain, $state, $courseid);
    }

    public function lti_get_domain_from_url($url) {
        $matches = array();
    
        if (preg_match(LTI_URL_DOMAIN_REGEX, $url, $matches)) {
            return $matches[1];
        }
    }

    public function lti_post_launch_html($newparms, $endpoint, $debug=false) {
        $r = "<form action=\"" . $endpoint .
            "\" name=\"ltiLaunchForm\" id=\"ltiLaunchForm\" method=\"post\" encType=\"application/x-www-form-urlencoded\">\n";
    
        // Contruct html for the launch parameters.
        foreach ($newparms as $key => $value) {
            $key = htmlspecialchars($key);
            $value = htmlspecialchars($value);
            if ( $key == "ext_submit" ) {
                $r .= "<input type=\"submit\"";
            } else {
                $r .= "<input type=\"hidden\" name=\"{$key}\"";
            }
            $r .= " value=\"";
            $r .= $value;
            $r .= "\"/>\n";
        }
    
        if ( $debug ) {
            $r .= "<script language=\"javascript\"> \n";
            $r .= "  //<![CDATA[ \n";
            $r .= "function basicltiDebugToggle() {\n";
            $r .= "    var ele = document.getElementById(\"basicltiDebug\");\n";
            $r .= "    if (ele.style.display == \"block\") {\n";
            $r .= "        ele.style.display = \"none\";\n";
            $r .= "    }\n";
            $r .= "    else {\n";
            $r .= "        ele.style.display = \"block\";\n";
            $r .= "    }\n";
            $r .= "} \n";
            $r .= "  //]]> \n";
            $r .= "</script>\n";
            $r .= "<a id=\"displayText\" href=\"javascript:basicltiDebugToggle();\">";
            $r .= get_string("toggle_debug_data", "lti")."</a>\n";
            $r .= "<div id=\"basicltiDebug\" style=\"display:none\">\n";
            $r .= "<b>".get_string("basiclti_endpoint", "lti")."</b><br/>\n";
            $r .= $endpoint . "<br/>\n&nbsp;<br/>\n";
            $r .= "<b>".get_string("basiclti_parameters", "lti")."</b><br/>\n";
            foreach ($newparms as $key => $value) {
                $key = htmlspecialchars($key);
                $value = htmlspecialchars($value);
                $r .= "$key = $value<br/>\n";
            }
            $r .= "&nbsp;<br/>\n";
            $r .= "</div>\n";
        }
        $r .= "</form>\n";
    
        if ( ! $debug ) {
            $r .= " <script type=\"text/javascript\"> \n" .
                "  //<![CDATA[ \n" .
                "    document.ltiLaunchForm.submit(); \n" .
                "  //]]> \n" .
                " </script> \n";
        }
        return $r;
    }

    public function lti_get_tool_proxy_table($toolproxies, $id) {
        global $OUTPUT;
    
        if (!empty($toolproxies)) {
            $typename = get_string('typename', 'lti');
            $url = get_string('registrationurl', 'lti');
            $action = get_string('action', 'lti');
            $createdon = get_string('createdon', 'lti');
    
            $html = <<< EOD
            <div id="{$id}_tool_proxies_container" style="margin-top: 0.5em; margin-bottom: 0.5em">
                <table id="{$id}_tool_proxies">
                    <thead>
                        <tr>
                            <th>{$typename}</th>
                            <th>{$url}</th>
                            <th>{$createdon}</th>
                            <th>{$action}</th>
                        </tr>
                    </thead>
EOD;
            foreach ($toolproxies as $toolproxy) {
                $date = userdate($toolproxy->timecreated, get_string('strftimedatefullshort', 'core_langconfig'));
                $accept = get_string('register', 'lti');
                $update = get_string('update', 'lti');
                $delete = get_string('delete', 'lti');
    
                $baseurl = new \moodle_url('/mod/lti/registersettings.php', array(
                        'action' => 'accept',
                        'id' => $toolproxy->id,
                        'sesskey' => sesskey(),
                        'tab' => $id
                    ));
    
                $registerurl = new \moodle_url('/mod/lti/register.php', array(
                        'id' => $toolproxy->id,
                        'sesskey' => sesskey(),
                        'tab' => 'tool_proxy'
                    ));
    
                $accepthtml = $OUTPUT->action_icon($registerurl,
                        new \pix_icon('t/check', $accept, '', array('class' => 'iconsmall')), null,
                        array('title' => $accept, 'class' => 'editing_accept'));
    
                $deleteaction = 'delete';
    
                if ($toolproxy->state != LTI_TOOL_PROXY_STATE_CONFIGURED) {
                    $accepthtml = '';
                }
    
                if (($toolproxy->state == LTI_TOOL_PROXY_STATE_CONFIGURED) || ($toolproxy->state == LTI_TOOL_PROXY_STATE_PENDING)) {
                    $delete = get_string('cancel', 'lti');
                }
    
                $updateurl = clone($baseurl);
                $updateurl->param('action', 'update');
                $updatehtml = $OUTPUT->action_icon($updateurl,
                        new \pix_icon('t/edit', $update, '', array('class' => 'iconsmall')), null,
                        array('title' => $update, 'class' => 'editing_update'));
    
                $deleteurl = clone($baseurl);
                $deleteurl->param('action', $deleteaction);
                $deletehtml = $OUTPUT->action_icon($deleteurl,
                        new \pix_icon('t/delete', $delete, '', array('class' => 'iconsmall')), null,
                        array('title' => $delete, 'class' => 'editing_delete'));
                $html .= <<< EOD
                <tr>
                    <td>
                        {$toolproxy->name}
                    </td>
                    <td>
                        {$toolproxy->regurl}
                    </td>
                    <td>
                        {$date}
                    </td>
                    <td align="center">
                        {$accepthtml}{$updatehtml}{$deletehtml}
                    </td>
                </tr>
EOD;
            }
            $html .= '</table></div>';
        } else {
            $html = get_string('no_' . $id, 'lti');
        }
    
        return $html;
    }

    public function lti_request_is_using_ssl() {  
        $scheme = $this->check_https() ? 'https':'http';                  
        $wwwroot = (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] ?: $scheme) . '://' . $_SERVER['HTTP_HOST'];
        return (stripos($wwwroot, 'https://') === 0);
    }

    public function lti_ensure_url_is_https($url) {
        if (!strstr($url, '://')) {
            $url = 'https://' . $url;
        } else {
            // If the URL starts with http, replace with https.
            if (stripos($url, 'http://') === 0) {
                $url = 'https://' . substr($url, 7);
            }
        }
    
        return $url;
    }

    public function lti_build_request($instance, $typeconfig, $course, $typeid = null, $islti2 = false) {
        global $USER, $CFG;
    
        if (empty($instance->cmid)) {
            $instance->cmid = 0;
        }
        
        $role = $this->lti_get_ims_role($this->USER, $instance->cmid, $instance->course, $islti2);
        
        $requestparams = array(
            'user_id' => $this->USER->id,
            'lis_person_sourcedid' => $this->USER->id,
            'roles' => $role,
            'context_id' => $course->id,
            'context_label' => trim($course->shortname),
            'context_title' => trim($course->fullname),
        );

        if (!empty($instance->name)) {
            $requestparams['resource_link_title'] = trim($instance->name);
        }
        if (!empty($instance->cmid)) {
            /*$intro = format_module_intro('lti', $instance, $instance->cmid);
            $intro = trim(html_to_text($intro, 0, false));*/
    
            // This may look weird, but this is required for new lines
            // so we generate the same OAuth signature as the tool provider.
            $intro = "resource link description as intor";
            $intro = str_replace("\n", "\r\n", $intro);
            $requestparams['resource_link_description'] = $intro;
        }
        if (!empty($instance->id)) {
            $requestparams['resource_link_id'] = $instance->id;
        }
        if (!empty($instance->resource_link_id)) {
            $requestparams['resource_link_id'] = $instance->resource_link_id;
        }
        if ($course->format == 'site') {
            $requestparams['context_type'] = 'Group';
        } else {
            $requestparams['context_type'] = 'CourseSection';
            $requestparams['lis_course_section_sourcedid'] = $course->id;
        }
    
        if (!empty($instance->id) && !empty($instance->servicesalt) && ($islti2 ||
                $typeconfig['acceptgrades'] == LTI_SETTING_ALWAYS ||
                ($typeconfig['acceptgrades'] == LTI_SETTING_DELEGATE && $instance->instructorchoiceacceptgrades == LTI_SETTING_ALWAYS))
        ) {
            $placementsecret = $instance->servicesalt;
            $sourcedid = json_encode($this->lti_build_sourcedid($instance->id, $this->USER->id, $placementsecret, $typeid));
            $requestparams['lis_result_sourcedid'] = $sourcedid;
    
            // Add outcome service URL.
            /*$serviceurl = new \moodle_url('/mod/lti/service.php');
            $serviceurl = $serviceurl->out();
    
            $forcessl = false;
            if (!empty($CFG->mod_lti_forcessl)) {
                $forcessl = true;
            }
    
            if ((isset($typeconfig['forcessl']) && ($typeconfig['forcessl'] == '1')) or $forcessl) {
                $serviceurl = lti_ensure_url_is_https($serviceurl);
            }*/            
            $serviceurl = url('lti/1p1/service');            
            $requestparams['lis_outcome_service_url'] = $serviceurl;
        }
    
        // Send user's name and email data if appropriate.
        if ($islti2 || $typeconfig['sendname'] == LTI_SETTING_ALWAYS ||
            ($typeconfig['sendname'] == LTI_SETTING_DELEGATE && isset($instance->instructorchoicesendname)
                && $instance->instructorchoicesendname == LTI_SETTING_ALWAYS)
        ) {
            
            $user_lastname = $this->USER->lastname;
            
            $consumer_key = null;            
            foreach($instance->getLtiType()->getLtiTypeConfigList() as $config){                
                if( trim($config->getName()) === "resourcekey" ){
                    $user_lastname .= " (".$config->getValue().")";                    
                }                
            }                            

            $requestparams['lis_person_name_given'] = $this->USER->firstname;
            $requestparams['lis_person_name_family'] = $user_lastname;
            $requestparams['lis_person_name_full'] = $this->USER->firstname . ' ' . $user_lastname;
            $requestparams['ext_user_username'] = $this->USER->username;
        }
    
        if ($islti2 || $typeconfig['sendemailaddr'] == LTI_SETTING_ALWAYS ||
            ($typeconfig['sendemailaddr'] == LTI_SETTING_DELEGATE && isset($instance->instructorchoicesendemailaddr)
                && $instance->instructorchoicesendemailaddr == LTI_SETTING_ALWAYS)
        ) {
            $requestparams['lis_person_contact_email_primary'] = $this->USER->email;
        }

        return $requestparams;
    }
    
    public function lti_build_sourcedid($instanceid, $userid, $servicesalt, $typeid = null, $launchid = null) {
        $data = new \stdClass();
    
        $data->instanceid = $instanceid;
        $data->userid = $userid;
        $data->typeid = $typeid;
        if (!empty($launchid)) {
            $data->launchid = $launchid;
        } else {
            $data->launchid = rand();
        }
    
        $json = json_encode($data);
        $hash = hash('sha256', $json . $servicesalt, false);
    
        $container = new \stdClass();
        $container->data = $data;
        $container->hash = $hash;
    
        return $container;
    }

    public function lti_get_ims_role($user, $cmid, $courseid, $islti2) {
        $roles = array();
        array_push($roles, 'Learner');
        //array_push($roles, 'Student');
        /*if (empty($cmid)) {
            // If no cmid is passed, check if the user is a teacher in the course
            // This allows other modules to programmatically "fake" a launch without
            // a real LTI instance.
            $context = context_course::instance($courseid);
    
            if (has_capability('moodle/course:manageactivities', $context, $user)) {
                array_push($roles, 'Instructor');
            } else {
                array_push($roles, 'Learner');
            }
        } else {
            $context = context_module::instance($cmid);
    
            if (has_capability('mod/lti:manage', $context)) {
                array_push($roles, 'Instructor');
            } else {
                array_push($roles, 'Learner');
            }
        }
    
        if (is_siteadmin($user) || has_capability('mod/lti:admin', $context)) {
            // Make sure admins do not have the Learner role, then set admin role.
            $roles = array_diff($roles, array('Learner'));
            if (!$islti2) {
                array_push($roles, 'urn:lti:sysrole:ims/lis/Administrator', 'urn:lti:instrole:ims/lis/Administrator');
            } else {
                array_push($roles, 'http://purl.imsglobal.org/vocab/lis/v2/person#Administrator');
            }
        }
        */
        return join(',', $roles);
    }

    /**
     * This function builds the request that must be sent to an LTI 2 tool provider
     *
     * @param object    $tool           Basic LTI tool object
     * @param array     $params         Custom launch parameters
     *
     * @return array                    Request details
     */
    public function lti_build_request_lti2($tool, $params) {
    
        $requestparams = array();
    
        $capabilities = lti_get_capabilities();
        $enabledcapabilities = explode("\n", $tool->enabledcapability);
        foreach ($enabledcapabilities as $capability) {
            if (array_key_exists($capability, $capabilities)) {
                $val = $capabilities[$capability];
                if ($val && (substr($val, 0, 1) != '$')) {
                    if (isset($params[$val])) {
                        $requestparams[$capabilities[$capability]] = $params[$capabilities[$capability]];
                    }
                }
            }
        }
    
        return $requestparams;
    
    }

    public function lti_build_standard_message($instance, $orgid, $ltiversion, $messagetype = 'basic-lti-launch-request') {
        
        $requestparams = array();
    
        if ($instance) {
            $requestparams['resource_link_id'] = $instance->id;
            if (property_exists($instance, 'resource_link_id') and !empty($instance->resource_link_id)) {
                $requestparams['resource_link_id'] = $instance->resource_link_id;
            }
        }
    
        $requestparams['launch_presentation_locale'] = 'EN';
    
        // Make sure we let the tool know what LMS they are being called from.
        $requestparams['ext_lms'] = 'Curriki LTI Platform';
        $requestparams['tool_consumer_info_product_family_code'] = 'curriki_lti';
        $requestparams['tool_consumer_info_version'] = '1.0';
    
        // Add oauth_callback to be compliant with the 1.0A spec.
        $requestparams['oauth_callback'] = 'about:blank';
    
        $requestparams['lti_version'] = $ltiversion;
        $requestparams['lti_message_type'] = $messagetype;
    
        if ($orgid) {
            $requestparams["tool_consumer_instance_guid"] = $orgid;
        }

        $requestparams['tool_consumer_instance_name'] = 'Curriki';
        $requestparams['tool_consumer_instance_description'] = 'Open Educational Resources';
    
        return $requestparams;
    }

    public function lti_build_custom_parameters($toolproxy, $tool, $instance, $params, $customstr, $instructorcustomstr, $islti2) {

        // Concatenate the custom parameters from the administrator and the instructor
        // Instructor parameters are only taken into consideration if the administrator
        // has given permission.
        $custom = array();
        if ($customstr) {
            $custom = $this->lti_split_custom_parameters($toolproxy, $tool, $params, $customstr, $islti2);
        }
        if ($instructorcustomstr) {
            $custom = array_merge($this->lti_split_custom_parameters($toolproxy, $tool, $params,
                $instructorcustomstr, $islti2), $custom);
        }
        if ($islti2) {
            /*$custom = array_merge($this->lti_split_custom_parameters($toolproxy, $tool, $params,
                $tool->parameter, true), $custom);
            $settings = lti_get_tool_settings($tool->toolproxyid);
            $custom = array_merge($custom, lti_get_custom_parameters($toolproxy, $tool, $params, $settings));
            if (!empty($instance->course)) {
                $settings = lti_get_tool_settings($tool->toolproxyid, $instance->course);
                $custom = array_merge($custom, lti_get_custom_parameters($toolproxy, $tool, $params, $settings));
                if (!empty($instance->id)) {
                    $settings = lti_get_tool_settings($tool->toolproxyid, $instance->course, $instance->id);
                    $custom = array_merge($custom, lti_get_custom_parameters($toolproxy, $tool, $params, $settings));
                }
            }*/
        }
    
        return $custom;
    }

    public function lti_split_custom_parameters($toolproxy, $tool, $params, $customstr, $islti2 = false) {
        $customstr = str_replace("\r\n", "\n", $customstr);
        $customstr = str_replace("\n\r", "\n", $customstr);
        $customstr = str_replace("\r", "\n", $customstr);
        $lines = explode("\n", $customstr);  // Or should this split on "/[\n;]/"?
        $retval = array();
        foreach ($lines as $line) {
            $pos = strpos($line, '=');
            if ( $pos === false || $pos < 1 ) {
                continue;
            }
            $key = trim(substr($line, 0, $pos));
            $val = trim(substr($line, $pos + 1, strlen($line)));
            //$val = $this->lti_parse_custom_parameter($toolproxy, $tool, $params, $val, $islti2);
            $key2 = $this->lti_map_keyname($key);
            $retval['custom_'.$key2] = $val;
            if (($islti2 || ($tool->ltiversion === LTI_VERSION_1P3)) && ($key != $key2)) {
                $retval['custom_'.$key] = $val;
            }
        }        
        return $retval;
    }

    public function lti_map_keyname($key, $tolower = true) {
        if ($tolower) {
            $newkey = '';
            $key = strtolower(trim($key));
            foreach (str_split($key) as $ch) {
                if ( ($ch >= 'a' && $ch <= 'z') || ($ch >= '0' && $ch <= '9') ) {
                    $newkey .= $ch;
                } else {
                    $newkey .= '_';
                }
            }
        } else {
            $newkey = $key;
        }
        return $newkey;
    }

    public function lti_get_services() {

        $services = array();
        //$definedservices = core_component::get_plugin_list('ltiservice');
        $definedservices = array(
            "basicoutcomes"=>
            "/var/www/html/mod/lti/service/basicoutcomes",
            "gradebookservices"=>
            "/var/www/html/mod/lti/service/gradebookservices",
            "memberships"=>
            "/var/www/html/mod/lti/service/memberships",
            "profile"=>
            "/var/www/html/mod/lti/service/profile",
            "toolproxy"=>
            "/var/www/html/mod/lti/service/toolproxy",
            "toolsettings"=>
            "/var/www/html/mod/lti/service/toolsettings"
        );
        
        foreach ($definedservices as $name => $location) {
            $classname = "\\ltiservice_{$name}\\local\\service\\{$name}";
            $services[] = new $classname();
        }
    
        return $services;
    
    }
    
}
