<?php

require_once(__DIR__ . '/../../../wp-load.php');
include_once(__DIR__ . '/../functions.php');

/**
 * User_Controller
 * 
 * 
 * @author     Fahad Farrukh <fahad.curriki@nxvt.com>
 */

class User_Controller extends WP_REST_Controller
{
  /**
   * Register the routes for the objects of the controller.
   */
  public function register_routes()
  {
    $namespace = 'genesis-curriki/v1';
    $path = 'users';

    register_rest_route($namespace, '/' . $path, [
      array(
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => array($this, 'create_item'),
        'permission_callback' => array($this, 'create_item_permissions_check'),
        'args' => array(
          // 'group_id' => array (
          //   'required' => true,
          //   'validate_callback' => function($param, $request, $key) {
          //     return is_numeric( $param );
          //   }
          // ),
          'firstname' => array (
            'required' => true
          ),
          'lastname' => array (
            'required' => true
          ),
          'username' => array (
            'required' => true
          ),
          'email' => array (
            'required' => true
          ),
          'pwd' => array (
            'required' => true
          ),
          'confirm_pwd' => array (
            'required' => true
          ),
        ),
      )
    ]);

    register_rest_route($namespace, '/' . $path . '/(?P<user_id>\d+)', [
      array(
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => array($this, 'edit_item'),
        'permission_callback' => array($this, 'edit_item_permissions_check'),
        'args' => array(
          'user_id' => array(
            'validate_callback' => function($param, $request, $key) {
              return is_numeric( $param );
            }
          ),
          'firstname' => array (
            'required' => true
          ),
          'lastname' => array (
            'required' => true
          ),
          'subjectarea' => array (
            'required' => true
          ),
          'educationlevel' => array (
            'required' => true
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
    remove_action('register_new_user', 'wp_send_new_user_notifications');
    ob_start();
    cur_ajax_curriki_signup();
    $returnValue = ob_get_contents();
    ob_end_clean();

    if (trim($returnValue) != '1') {
      return new WP_Error( 'cant-create', __( $returnValue, 'text-domain' ), array( 'status' => 400 ) );
    } else {
      return new WP_REST_Response( true, 200 );
    }
  }

  /**
   * Check if a given request has access to create items
   *
   * @param WP_REST_Request $request Full data about the request.
   * @return WP_Error|bool
   */
  public function create_item_permissions_check( $request ) {
    /*global $wpdb;

    if (is_user_logged_in()) {
      $userRoles = $wpdb->get_results(
        $wpdb->prepare("SELECT name FROM custom_user_roles WHERE id IN ( SELECT role_id FROM custom_group_user_roles WHERE group_id = %d AND user_id = %d )", array($request->get_param('group_id'), get_current_user_id()))
      );

      foreach ($userRoles as $userRole) {
        if ($userRole->name == 'admin' || $userRole->name == 'branch_admin') {*/
          return true;
        /*}
      }
    }

    return false;*/
  }

  /**
   * Edit one item from the collection
   *
   * @param WP_REST_Request $request Full data about the request.
   * @return WP_Error|WP_REST_Request
   */
  public function edit_item( $request ) {
    $whitelist = array(
        'firstname',
        'lastname',
        'subjectarea',
        'educationlevel'
    );

    $userData = array_intersect_key( $_POST, array_flip( $whitelist ) );

    $errors = updateUserData($userData);

    if(count($errors) != 0) {
      return new WP_Error( 'cant-edit', __( implode(', ', $errors), 'text-domain' ), array( 'status' => 400 ) );
    } else {
      return new WP_REST_Response( true, 200 );
    }
  }

  /**
   * Check if a given request has access to edit items
   *
   * @param WP_REST_Request $request Full data about the request.
   * @return WP_Error|bool
   */
  public function edit_item_permissions_check( $request ) {
    if (is_user_logged_in()) {
      return true;
    }

    return false;
  }
}