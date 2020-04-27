<?php
namespace CurrikiLti\Core\Controllers;
use CurrikiLti\Core\Entity\Lti;
use CurrikiLti\Core\Entity\LtiType;
use CurrikiLti\Core\Entity\LtiTypeConfig;
use Doctrine\ORM\EntityManager;

class Toolsettings
{
    public $entityManager = null;
    public $render_view = true;

    public function tool_delete()
    {
        global $entityManager;
        if($entityManager === null){
            $entityManager = $this->entityManager;
        }

        $lti_id = $_GET['id'];        
        $lti_type = $entityManager->getRepository('CurrikiLti\Core\Entity\LtiType')->find($lti_id);
        $lti = $lti_type->getLti();
        $entityManager->remove($lti_type);
        $entityManager->remove($lti);
        $entityManager->flush();
        if($this->render_view){
            require_once __DIR__.'/../../views/tool_delete.php';
        }    
    }

    public function tool_add()
    {                

        if (isset($_POST['submit_lti_tool']) && $_POST['submit_lti_tool'] === 'Add' ){            
            global $entityManager;

            if($entityManager === null){
                $entityManager = $this->entityManager;
            }
    
            $lti = new Lti();
            $lti->name = trim($_POST['lti_typename']) . ' - LTI';

            $lti_type = new LtiType();            
            $lti_type->name = trim($_POST['lti_typename']);    
            $lti_type->baseurl = trim($_POST['lti_toolurl']);    
            $lti_type->tooldomain = "";    
            $lti_type->description = trim($_POST['lti_description']);    
            $lti_type->icon = trim($_POST['lti_icon']);    
            $lti_type->secureicon = trim($_POST['lti_secureicon']);
            $lti_type->clientid = trim($_POST['lti_clientid']);
            $lti_type->ltiversion = trim($_POST['lti_ltiversion']);
            $lti_type->setLti($lti);                                        
            $lti->setLtiType($lti_type);

            $lti_config_1 = new LtiTypeConfig();
            $lti_config_1->name = "publickey";
            $lti_config_1->value = $_POST['lti_publickey'];            
            $lti_config_1->setLtiType($lti_type);
            $lti_type->addToTypeConfigList($lti_config_1);            

            $lti_config_2 = new LtiTypeConfig();
            $lti_config_2->name = "initiatelogin";
            $lti_config_2->value = $_POST['lti_initiatelogin'];            
            $lti_config_2->setLtiType($lti_type);
            $lti_type->addToTypeConfigList($lti_config_2);            
            
            $lti_config_3 = new LtiTypeConfig();
            $lti_config_3->name = "redirectionuris";
            $lti_config_3->value = $_POST['lti_redirectionuris'];            
            $lti_config_3->setLtiType($lti_type);
            $lti_type->addToTypeConfigList($lti_config_3);            

            $lti_config_4 = new LtiTypeConfig();
            $lti_config_4->name = "customparameters";
            $lti_config_4->value = $_POST['lti_customparameters'];            
            $lti_config_4->setLtiType($lti_type);
            $lti_type->addToTypeConfigList($lti_config_4);            

            $lti_config_5 = new LtiTypeConfig();
            $lti_config_5->name = "coursevisible";
            $lti_config_5->value = 1;            
            $lti_config_5->setLtiType($lti_type);
            $lti_type->addToTypeConfigList($lti_config_5);            

            $lti_config_6 = new LtiTypeConfig();
            $lti_config_6->name = "launchcontainer";
            $lti_config_6->value = 3;            
            $lti_config_6->setLtiType($lti_type);
            $lti_type->addToTypeConfigList($lti_config_6);            

            $lti_config_7 = new LtiTypeConfig();
            $lti_config_7->name = "contentitem";
            $lti_config_7->value = isset($_POST['lti_contentitem']) ? isset($_POST['lti_contentitem']) : 0;            
            $lti_config_7->setLtiType($lti_type);
            $lti_type->addToTypeConfigList($lti_config_7);            

            $lti_config_8 = new LtiTypeConfig();
            $lti_config_8->name = "ltiservice_gradesynchronization";
            $lti_config_8->value = 0;            
            $lti_config_8->setLtiType($lti_type);
            $lti_type->addToTypeConfigList($lti_config_8);            

            $lti_config_9 = new LtiTypeConfig();
            $lti_config_9->name = "ltiservice_memberships";
            $lti_config_9->value = 1;            
            $lti_config_9->setLtiType($lti_type);
            $lti_type->addToTypeConfigList($lti_config_9);            

            $lti_config_10 = new LtiTypeConfig();
            $lti_config_10->name = "ltiservice_toolsettings";
            $lti_config_10->value = 1;            
            $lti_config_10->setLtiType($lti_type);
            $lti_type->addToTypeConfigList($lti_config_10);            

            $lti_config_11 = new LtiTypeConfig();
            $lti_config_11->name = "sendname";
            $lti_config_11->value = 1;            
            $lti_config_11->setLtiType($lti_type);
            $lti_type->addToTypeConfigList($lti_config_11);            

            $lti_config_12 = new LtiTypeConfig();
            $lti_config_12->name = "sendemailaddr";
            $lti_config_12->value = 1;            
            $lti_config_12->setLtiType($lti_type);
            $lti_type->addToTypeConfigList($lti_config_12);            

            $lti_config_13 = new LtiTypeConfig();
            $lti_config_13->name = "acceptgrades";
            $lti_config_13->value = 1;            
            $lti_config_13->setLtiType($lti_type);
            $lti_type->addToTypeConfigList($lti_config_13);            

            $lti_config_14 = new LtiTypeConfig();
            $lti_config_14->name = "organizationid";
            $lti_config_14->value = "";            
            $lti_config_14->setLtiType($lti_type);
            $lti_type->addToTypeConfigList($lti_config_14);            

            $lti_config_15 = new LtiTypeConfig();
            $lti_config_15->name = "organizationurl";
            $lti_config_15->value = "";            
            $lti_config_15->setLtiType($lti_type);
            $lti_type->addToTypeConfigList($lti_config_15);            

            $lti_config_16 = new LtiTypeConfig();
            $lti_config_16->name = "forcessl";
            $lti_config_16->value = 0;            
            $lti_config_16->setLtiType($lti_type);
            $lti_type->addToTypeConfigList($lti_config_16);            

            $lti_config_17 = new LtiTypeConfig();
            $lti_config_17->name = "servicesalt";
            $lti_config_17->value = "5d19ee9808cfc2.16905989";            
            $lti_config_17->setLtiType($lti_type);
            $lti_type->addToTypeConfigList($lti_config_17);   
                        
            $lti_config_18 = new LtiTypeConfig();
            $lti_config_18->name = "resourcekey";
            $lti_config_18->value = trim($_POST['lti_resourcekey']);            
            $lti_config_18->setLtiType($lti_type);
            $lti_type->addToTypeConfigList($lti_config_18); 

            $lti_config_19 = new LtiTypeConfig();
            $lti_config_19->name = "password";
            $lti_config_19->value = trim($_POST['lti_password']);            
            $lti_config_19->setLtiType($lti_type);
            $lti_type->addToTypeConfigList($lti_config_19);            

            $entityManager->persist($lti_type);
            $entityManager->flush();

        }        

        if($this->render_view){
            require_once __DIR__.'/../../views/tool_add.php';
        } else {
            if(isset($lti) && is_object($lti)){
                return $lti->id;
            }            
        }
    }

    public function tool_edit()
    {
        global $entityManager;

        if($entityManager === null){
            $entityManager = $this->entityManager;
        }

        if(isset($_POST['tool_id'])){
            
            $lti_type = $entityManager->getRepository('CurrikiLti\Core\Entity\LtiType')->find((int)$_POST['tool_id']);            
            $lti_type->name = trim($_POST['lti_typename']);    
            $lti_type->baseurl = trim($_POST['lti_toolurl']);    
            $lti_type->description = trim($_POST['lti_description']);    
            $lti_type->icon = trim($_POST['lti_icon']);    
            $lti_type->secureicon = trim($_POST['lti_secureicon']);    
            $lti_type_config = $lti_type->getLtiTypeConfigList();

            foreach ($lti_type_config as $type_config) {        
                if($type_config->name === 'customparameters'){
                    $type_config->value = $_POST['lti_customparameters'];                    
                    $type_config->setLtiType($lti_type);
                }
                if($type_config->name === 'publickey' && isset($_POST['lti_publickey'])){
                    $type_config->value = $_POST['lti_publickey'];                    
                    $type_config->setLtiType($lti_type);
                }
                if($type_config->name === 'initiatelogin' && isset($_POST['lti_initiatelogin'])){
                    $type_config->value = $_POST['lti_initiatelogin'];                    
                    $type_config->setLtiType($lti_type);
                }
                if($type_config->name === 'redirectionuris' && isset($_POST['lti_redirectionuris'])){
                    $type_config->value = $_POST['lti_redirectionuris'];                    
                    $type_config->setLtiType($lti_type);
                }
                if($type_config->name === 'contentitem'){
                    $lti_contentitem = isset($_POST['lti_contentitem']) && $_POST['lti_contentitem'] == 1 ? $_POST['lti_contentitem']:0;
                    $type_config->value = intval($lti_contentitem);                    
                    $type_config->setLtiType($lti_type);
                }
            }
            $entityManager->flush();  
                          
        }

        $lti_id = $_GET['id']; 
        $lti_configuration = new \CurrikiLti\Core\Services\Lti\LtiConfiguration($entityManager);
        $lti_type = $entityManager->getRepository('CurrikiLti\Core\Entity\LtiType')->find($lti_id);        
        $lti = $lti_type->getLti();        
        $toolconfig = $lti_configuration->getTypeConfig($lti->typeid);
        $launchcontainer = $lti_configuration->getLaunchContainer($lti, $toolconfig);        
        $config = $lti_configuration->getTypeTypeConfig($lti->typeid);

        if($this->render_view){
            require_once __DIR__.'/../../views/tool_edit.php';
        }else{
            return $config;
        }       
    }
}