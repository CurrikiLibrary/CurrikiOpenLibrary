<?php

require_once(__DIR__ . '/../../../wp-load.php');
include_once(__DIR__ . '/../functions.php');
include_once(__DIR__ . '/../../plugins/curriki_manage/curriki_manage.php');

/**
 * Resource_Controller
 * 
 * 
 * @author     Fahad Farrukh <fahad.curriki@nxvt.com>
 */

class Resource_Controller extends WP_REST_Controller
{
  /**
   * Register the routes for the objects of the controller.
   */
  public function register_routes()
  {
    $namespace = 'genesis-curriki/v1';
    $path = 'resources/(?P<resource_id>\d+)';

    register_rest_route($namespace, '/' . $path, [
      array(
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => array($this, 'create_item'),
        'permission_callback' => array($this, 'create_item_permissions_check'),
        'args' => array(
          'custom_group_id' => array (
            'required' => true,
            'validate_callback' => function($param, $request, $key) {
              return is_numeric( $param );
            }
          ),
          'groupid' => array (
            'required' => true,
            'validate_callback' => function($param, $request, $key) {
              return is_numeric( $param );
            }
          ),
          'title' => array (
            'required' => true
          ),
          'description' => array (
            'required' => true
          ),
          'education_levels' => array (
            'required' => true
          ),
          // 'subjectareaids' => array (
          //   'required' => true
          // ),
          // 'resource_type' => array (
          //   'required' => true
          // ),
          'content' => array (
            'required' => true
          ),
        ),
      ),
      array(
        'methods'             => WP_REST_Server::DELETABLE,
        'callback'            => array($this, 'delete_item'),
        'permission_callback' => array($this, 'delete_item_permissions_check'),
        'args' => array(
          'resource_id' => array(
            'validate_callback' => function($param, $request, $key) {
              return is_numeric( $param );
            }
          ),
        ),
      )
    ]);
  }

  /**
   * Create one item from the collection
   *
   * @param WP_REST_Request $request Full data about the request.
   * @return WP_Error|WP_REST_Request
   */
  public function create_item( $request ) {
    $_SESSION['api_call'] = true;      

    try {
      ajax_create_resource();
    }
    catch(Exception $e) {
      echo $e->getMessage();
      wp_die();
    }
  }

  /**
   * Check if a given request has access to create items
   *
   * @param WP_REST_Request $request Full data about the request.
   * @return WP_Error|bool
   */
  public function create_item_permissions_check( $request ) {
    global $wpdb;

    return true;

    /*
    if (is_user_logged_in()) {
      $userRoles = $wpdb->get_results(
        $wpdb->prepare("SELECT name FROM custom_user_roles WHERE id IN ( SELECT role_id FROM custom_group_user_roles WHERE group_id = %d AND user_id = %d )", array($request->get_param('custom_group_id'), get_current_user_id()))
      );

      foreach ($userRoles as $userRole) {
        if ($userRole->name == 'admin' || $userRole->name == 'branch_admin') {
          return true;
        }
      }
    }

    return false;
    */
  }

  /**
   * Delete one item from the collection
   *
   * @param WP_REST_Request $request Full data about the request.
   * @return WP_Error|WP_REST_Request
   */
  public function delete_item( $request ) {
    $brokenLinkResourcesIds[] = $request->get_param('resource_id');

    deleteResourcesData($brokenLinkResourcesIds);
 
    return new WP_REST_Response( true, 200 );
  }

  /**
   * Check if a given request has access to delete a specific item
   *
   * @param WP_REST_Request $request Full data about the request.
   * @return WP_Error|bool
   */
  public function delete_item_permissions_check( $request ) {
    global $wpdb;

    if (is_user_logged_in()) {
      $userResource = $wpdb->get_row(
        $wpdb->prepare("SELECT * FROM resources where resourceid = %d AND contributorid = %d", array($request->get_param('resource_id'), get_current_user_id()))
      );

      $userRoles = $wpdb->get_results(
        $wpdb->prepare("SELECT name FROM custom_user_roles WHERE id IN ( SELECT role_id FROM custom_group_user_roles WHERE group_id = ( SELECT group_id FROM custom_group_resources WHERE resource_id = %d) AND user_id = %d )", array($request->get_param('resource_id'), get_current_user_id()))
      );

      if ($userResource) {
        return true;
      }

      foreach ($userRoles as $userRole) {
        if ($userRole->name == 'admin' || $userRole->name == 'branch_admin') {
          return true;
        }
      }
    }

    return false;
  }
}
