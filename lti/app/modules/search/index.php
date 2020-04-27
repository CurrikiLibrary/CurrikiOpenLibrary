<?php
require_once 'classes/search.php';
//require_once('./lib.php');  
// Initialise session and database
//$db = NULL;
//$ok = init($db, TRUE);
  
use IMSGlobal\LTI\ToolProvider;
use IMSGlobal\LTI\ToolProvider\DataConnector;

//var_dump($ok);
//die;

if( isset($_GET["ajax"]) && $_GET["ajax"] == 1 && isset($_GET["do_ajax"]) &&  $_GET["do_ajax"] === "add_resource")
{
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
    if (!empty($documentTarget)) 
    {
      $placement = new ToolProvider\ContentItemPlacement(NULL, NULL, $documentTarget, NULL);
    }
    $item = new ToolProvider\ContentItem('LtiLinkItem', $placement);
    $item->setMediaType(ToolProvider\ContentItem::LTI_LINK_MEDIA_TYPE);
    $item->setTitle($_GET["title"]);
    $item->setText($_GET["title"]);
    $item->setUrl($_GET["url"]);
    //$item->icon = new ToolProvider\ContentItemImage(getAppUrl() . 'images/icon50.png', 50, 50);
    $item->custom = array('content_item_id' => $_SESSION['resource_id']);
    $form_params['content_items'] = ToolProvider\ContentItem::toJson($item);
    if (!is_null($_SESSION['data'])) {
      $form_params['data'] = $_SESSION['data'];
    }    
    $form_params = $consumer->signParameters($_SESSION['return_url'], 'ContentItemSelection', $_SESSION['lti_version'], $form_params);
    //var_dump($form_params);die;
    $page = ToolProvider\ToolProvider::sendForm($_SESSION['return_url'], $form_params);
    echo $page;
    exit;
}

if( (isset($_GET["ajax"]) && $_GET["ajax"] == 1) && (isset($_GET["action"]) && $_GET["action"] === "searchpagination")  )
{
    //echo "<pre>";
    //var_dump($_GET); 
    
    $search = new search();
    $search->search_term = urlencode($_GET["search_term"]);
    $search->searchRequestURL = $_GET["request_url"];
    $search->execute();
    require_once 'views/search_result_resources.php';
    require_once 'views/pagination.php';
    die();
}elseif( (isset($_GET["ajax"]) && $_GET["ajax"] == 1) && (isset($_REQUEST["action"]) && $_REQUEST["action"] === "search_form_submit")  )
{
    
    $request_url = parse_url($_REQUEST["request_url"]) ;
    $request_query = $request_url["query"];
    $request_params = array();
    parse_str($request_query, $request_params);    
    
    $search = new search();
    $search->search_term = urlencode($request_params["phrase"]);
    $search->searchRequestURL = $_REQUEST["request_url"];    
    $search->execute();
    require_once 'views/search_result_resources.php';
    require_once 'views/pagination.php';
    die();    
}else{
    
    $search = new search();
    //$search->search_term = urlencode($_SESSION["context_title"]);    
    if( isset($_SESSION["custom_search_term"]) && strlen($_SESSION["custom_search_term"]) > 0 )
    {
        $search->search_term = $_SESSION["custom_search_term"];
    }else{
        $search->search_term = "";
    }
    
    $search->searchRequestURL = "https://www.curriki.org/search/?type=Resource&phrase={$search->search_term}&language=&start=0&partnerid=1&searchall=&viewer=&branding=common&sort=rank1+desc&output=json&t=".time();
    $search->execute();

    require_once 'views/search_title_widget.php';
    require_once 'views/search_input_widget.php';
    echo '<div id="resources-wrapper">';
        require_once 'views/search_result_resources.php';
        require_once 'views/pagination.php';
    echo '</div>';    
}
