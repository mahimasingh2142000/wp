<?php
/**
 * @wordpress plugin
 * Plugin Name: Custom API Plugin
 * Description: This is a custom api plugin
 * Author: Mahima Singh
 */


//api for create user---------
add_action('rest_api_init', function () {
  register_rest_route('myplugin/v1', '/author/(?P<id>\d+)', array(
    'methods' => 'GET',
    'callback' => 'my_awesome_func',
    'args' => array(
      'id' => array(
        'validate_callback' => function ($param, $request, $key) {
          return is_numeric($param);
        }
      ),
    ),
  ));
});
function my_awesome_func(WP_REST_Request $request)
{
  $id = $request['id'];
  //  return new WP_REST_Response(array('status'=>'success','id'=>$id),200);
  $user_id = wp_insert_user(array(
    'user_login' => $request->get_param('email'),
    'first_name' => $request->get_param('fname'),
    'last_name' => $request->get_param('lname'),
    'user_email' => $request->get_param('email'),
    'user_pass' => $request->get_param('password')
  ));
  $user = get_user_by('id', $user_id);
  return new WP_REST_Response(array($user));
}



//api for state----------
add_action('rest_api_init', function () {
  register_rest_route('state/v1', '/author/(?P<id>\d+)', array(
    'methods' => 'GET',
    'callback' => 'my_state_func',
    'args' => array(
      'id' => array(
        'validate_callback' => function ($param, $request, $key) {
          return is_numeric($param);
        }
      ),
    ),
  ));
});
function my_state_func(WP_REST_Request $request)
{
  $id = $request['id'];
  global $wpdb, $table_prefix;
  $table = $table_prefix . 'state';
  $state = $wpdb->get_results("select s_name from $table");
  return new WP_REST_Response($state, $id);
}



//api for city-----
add_action('rest_api_init', function () {
  register_rest_route('city/v1', '/author/(?P<id>\d+)', array(
    'methods' => 'GET',
    'callback' => 'my_city_func',
    'args' => array(
      'id' => array(
        'validate_callback' => function ($param, $request, $key) {
          return is_numeric($param);
        }
      ),
    ),
  ));
});
function my_city_func(WP_REST_Request $request)
{
  global $wpdb, $table_prefix;
  $table = $table_prefix . 'city';
  $id = $request['id'];
  // return new WP_REST_Response(array('status' => 'success', 'id' => $id), 200);
  if ($id) {
    $city = $wpdb->get_results("select c_name from $table where s_id=$id");
    return new WP_REST_Response($city, $id);
  }
}
