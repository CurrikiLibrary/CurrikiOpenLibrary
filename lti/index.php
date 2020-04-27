<?php
/**
 * rating - Rating: an example LTI tool provider
 *
 * @author  Stephen P Vickers <svickers@imsglobal.org>
 * @copyright  IMS Global Learning Consortium Inc
 * @date  2016
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, Version 3.0
 */
 
/*
 * This page displays a list of items for a resource link.  Students are able to rate
 * each item; staff may add, edit, re-order and delete items.
 */

  use IMSGlobal\LTI\ToolProvider;
  use IMSGlobal\LTI\ToolProvider\DataConnector;

  require_once('lib.php');
  require_once('app/tp_grading_helper.php');
  
  if( !class_exists("ToolConsumerModel") )
  {
      require_once('app/modules/search/classes/ToolConsumerModel.php');
  }
  
// Initialise session and database
  $db = NULL;
  $ok = init($db, TRUE);
// Initialise parameters
  
  $model = new ToolConsumerModel();
  $model->db = $db;
  
  $id = 0;
  if ($ok) {
          
        $ok = TRUE;
        $data_connector = DataConnector\DataConnector::getDataConnector(DB_TABLENAME_PREFIX, $db);
        $consumer = ToolProvider\ToolConsumer::fromRecordId($_SESSION['consumer_pk'], $data_connector);
        
        $resource_link = null;
        if (is_null($_SESSION['resource_pk'])) {
          $resource_link = ToolProvider\ResourceLink::fromConsumer($consumer, $_SESSION['resource_id']);
          $ok = $resource_link->save();
        } else {            
            $resource_link = ToolProvider\ResourceLink::fromRecordId($_SESSION['resource_pk'], $data_connector);
        }   
        
        if( $resource_link )
        {       
            
            $title = APP_NAME;
            $unserInfo = unserialize($_SESSION['unserInfo']);
            $data = $data_body_start = $data_body_end = array();
            $data["page_title"] = $title;
            $data_body_start["unser_info"] = $_SESSION['unserInfo'];

            $resource_link_id = $resource_link->getId();

            $resource_link_settings = $resource_link->getSettings();

            $context = $resource_link->getContext();
            $context_id = $context->ltiContextId;


            //$user = ToolProvider\User::fromResourceLink($resource_link, $userinfo->ltiUserId);

            $has_user_context_roles = isset($_SESSION['isInstructor']) || isset($_SESSION['isLearner']) || isset($_SESSION['isAdmin']);
            $data_body_start["has_user_context_roles"]= $has_user_context_roles;
            $user_current_role = null;
            if(isset($_SESSION['isLearner']) && $_SESSION['isLearner'] === true)
                $user_current_role="Learner";
            if(isset($_SESSION['isInstructor']) && $_SESSION['isInstructor'] === true)
                $user_current_role="Instructor";
            if(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] === true)
                $user_current_role="Admin";
            
            if( isset($_GET["ajax"]) && $_GET["ajax"] == 1 )
            {
                require_once 'ajax-loader.php';
            }else{
                require_once 'views/index.php';                
                    
                //**** Updating user resource *******
                $model->consumer_pk = $_SESSION['consumer_pk'];
                $row_consumer_user = $model->getConsumerUser();                    
                $model->userid = $row_consumer_user->userid;
                $row_user = $model->getUser();

                if($row_consumer_user && $row_user && $row_user->source==null)
                {                        
                    $source_val = "LTI-".$consumer->getKey();
                    $model->updateUserSource($source_val);                       
                }  else {
                    //echo "user's source is already updated";
                }
                
            }
        }
  }
  /*
  if(isset($_SESSION['isContentItem']) && $_SESSION['isContentItem']===true){
       
                $placement = NULL;
                $documentTarget = '';
                if (in_array('overlay', $_SESSION['document_targets'])) {
                  $documentTarget = 'overlay';
                } else if (in_array('popup', $_SESSION['document_targets'])) {
                  $documentTarget = 'popup';
                } else if (in_array('iframe', $_SESSION['document_targets'])) {
                  $documentTarget = 'iframe';
                } else if (in_array('frame', $_SESSION['document_targets'])) {
                  $documentTarget = 'frame';
                }

                if (!empty($documentTarget)) {
                  $placement = new ToolProvider\ContentItemPlacement(NULL, NULL, $documentTarget, NULL);
                }
                
                $item = new ToolProvider\ContentItem('LtiLinkItem', $placement);
                $item->setMediaType(ToolProvider\ContentItem::LTI_LINK_MEDIA_TYPE);
                $item->setTitle($_SESSION['title']);
                $item->setText($_SESSION['text']);
                $item->icon = new ToolProvider\ContentItemImage(getAppUrl() . 'images/icon50.png', 50, 50);
                $item->custom = array('content_item_id' => $_SESSION['resource_id']);
                
                $form_params['content_items'] = ToolProvider\ContentItem::toJson($item);
                
                if (!is_null($_SESSION['data'])) {
                  $form_params['data'] = $_SESSION['data'];
                }
                $data_connector = DataConnector\DataConnector::getDataConnector(DB_TABLENAME_PREFIX, $db);
                $consumer = ToolProvider\ToolConsumer::fromRecordId($_SESSION['consumer_pk'], $data_connector);
                $form_params = $consumer->signParameters($_SESSION['return_url'], 'ContentItemSelection', $_SESSION['lti_version'], $form_params);                                                 
                $page = ToolProvider\ToolProvider::sendForm($_SESSION['return_url'], $form_params);
                echo $page;                
  }  
*/
?>
