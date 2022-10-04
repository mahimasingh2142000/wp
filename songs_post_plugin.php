<?php

/**
 * Plugin Name: Texonomy Plugin
 * Description: Songs post taxonomy
 */


function wp_custom_enqueue_admin_script()
{
    $page = array('my-new-menu-page','assign-user');
    if (in_array(@$_GET['page'], $page)) {

        // CSS File.
        wp_enqueue_style('bootstrap-min-css', plugin_dir_url(__FILE__) . 'css/bootstrap.min.css', array(), '3.4.1');
        wp_enqueue_style('bootstrap-icon-css', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css', array(), '3.4.1');
        wp_enqueue_style('custom-css', plugin_dir_url(__FILE__) . 'css/style.css', array(), '1.0.0');

        // JS File
        wp_enqueue_script('jquery-min-js', plugin_dir_url(__FILE__) . 'js/jquery.min.js', array(), '2.0.0', true);
        wp_enqueue_script('bootstrap-js', plugin_dir_url(__FILE__) . 'js/bootstrap.min.js', array(), '3.4.1', true); 
        wp_enqueue_script('jquery-validate-js', plugin_dir_url(__FILE__) . 'js/jquery-validate.js', array(), '2.0.0', true);
        wp_enqueue_script('bootstrap-sweet-js',plugin_dir_url(__FILE__) . 'js/sweet_allert.min.js', array(), '2.1.2', true);
        wp_enqueue_script('custom-js', plugin_dir_url(__FILE__) . 'js/custom.js', array(),'1.0.0',true);
    }
}
add_action('admin_enqueue_scripts', 'wp_custom_enqueue_admin_script');

//  add role of user
add_role(
    'manager',
    __( 'Manager', 'testdomain' ),
    array(
        'read'         => true,  // true allows this capability
        'edit_posts'   => true,
        'delete_posts' => true, // Use false to explicitly deny
    )
);

add_role(
    'employee',
    __( 'Employee', 'testdomain' ),
    array(
        'read'         => true,  // true allows this capability
        'edit_posts'   => false,
        'delete_posts' => false, // Use false to explicitly deny
    )
);
add_action("wp_ajax_registration", "insert_data_into_custom_registration_table");
function insert_data_into_custom_registration_table()
{
    global $wpdb, $table_prefix;
    $table = $table_prefix . 'registration';
    $old_data = $wpdb->get_results("select * from $table");
    $flag = 0;
    foreach ($old_data as $inner_array) {
        foreach ($inner_array as $key => $value) {
            if ($key == 'email' && $value == $_POST['email']) {
                $flag = 1;
                break;
            }
        }
    }
    if ($flag == 0) {
        $result = $wpdb->insert($table, [
            "fname" => $_POST["fname"],
            "lname" => $_POST["lname"],
            "gender" => $_POST["gender"],
            "contact" => $_POST["mobile"],
            "email" => $_POST["email"],
            "password" => $_POST['password']
        ]);
        wp_send_json_success($result);
    } else {
        wp_send_json_error(0);
    }
}
add_action("wp_ajax_user", "insert_data_into_wp_user_table");
function insert_data_into_wp_user_table()
{
    $plain_password = $_POST['password'];
    if (!username_exists($_POST['email'])) {
        $user_id = wp_create_user($_POST['email'], $plain_password, $_POST['email']);
        if (!is_wp_error($user_id)) {
            $user= get_user_by('ID',$user_id);
            update_user_meta($user_id, 'fname', $_POST['fname']);
            update_user_meta($user_id, 'lname', $_POST['lname']);
            update_user_meta($user_id, 'gender', $_POST['gender']);
            update_user_meta($user_id, 'mobile', $_POST['mobile']);
            update_user_meta($user_id, 'state', $_POST['state']);
            update_user_meta($user_id, 'city', $_POST['city']);
            $user->set_role($_POST['role']);
            wp_send_json_success(1);  
        }
    }
}
// add_action("wp_ajax_select_option", "select_state_and_city");
// function select_state_and_city()
// {  
//     $api_object = wp_remote_get('http://localhost/jamtech/wordpress/wp-json/myplugin/v1/author/44');
//     $state= wp_remote_retrieve_body( $api_object ); 
//     wp_send_json_success($state);  
// }
/* Custom Post Type Start */
function create_posttype_songs()
{
    register_post_type(
        'songs',
        // CPT Options
        array(
            'labels' => array(
                'name' => __('songs'),
                'singular_name' => __('Songs')
            ),
            'public' => true,
            'has_archive' => false,
            'rewrite' => array('slug' => 'songs'),
        )
    );
}
// Hooking up our function to theme setup
add_action('init', 'create_posttype_songs');
/* Custom Post Type End */


//custom taxonomy in songs post type  --------
function wporg_register_taxonomy_album()
{
    $labels = array(
        'name'              => _x('Album', 'taxonomy general name'),
        'singular_name'     => _x('Album', 'taxonomy singular name'),
        'search_items'      => __('Search Songs'),
        'all_items'         => __('All Songs'),
        'parent_item'       => __('Parent Songs'),
        'parent_item_colon' => __('Parent Songs:'),
        'edit_item'         => __('Edit Songs'),
        'update_item'       => __('Update Songs'),
        'add_new_item'      => __('Add New Songs'),
        'new_item_name'     => __('New Song Name'),
        'menu_name'         => __('Album'),
    );
    $args   = array(
        'hierarchical'      => true, // make it hierarchical (like categories)
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => ['slug' => 'album'],
    );
    register_taxonomy('Album', ['songs'], $args);
}
add_action('init', 'wporg_register_taxonomy_album');
//end custom taxonamy--------

add_action('Album_add_form_fields', 'rudr_add_term_fields', 10, 1);
function rudr_add_term_fields($taxonomy)
{
    $arg = array(
        'post_type' => 'songs',
        'post_status' => 'publish'
    );
    $all_songs = get_posts($arg);
    $html = '<div class="form-field">
        <label>Select Options</label>
        <select name="songs[]" multiple>
            <option value="">Select</option>';
    foreach ($all_songs as $value) {
        $html .= '<option value="' . $value->post_title . '">' . $value->post_title . '</option>';
    }
    $html .= '</select>
        <p>You Can Select Mulitaple Options</p>
    </div>
    <div class="form-field">
        <label>Image Field</label>
        <input type="file" name="img">
   </div>';
    echo $html;
}
add_action("create_Album", 'custom_field_data', 10, 2);
function custom_field_data($term_id, $tt_id)
{
    if (isset($_POST['songs'])) {
        $data = $_POST['songs'];
        update_term_meta($term_id, "songs", serialize($data));
    }
    //   if(isset($_FILES['img'])){
    //         $img = $_FILES['img'];
    //        print_r($_FILES['img']);die();
    //        update_term_meta( $term_id,"songs",serialize($img));
    //    }
}
/**
 * Register a custom menu page.
 */
add_action('admin_menu', 'wpdocs_register_my_new_menu_page');
function wpdocs_register_my_new_menu_page()
{
    add_menu_page(
        'New Menu Title',
        'New Menu Title',
        'manage_options',
        'my-new-menu-page',
        'wpdocs_register_my_new__menu_page_cb',
    );
}
function wpdocs_register_my_new__menu_page_cb()
{
    echo "<h1>This is my new menu page</h1>";
    ob_start();
    include_once plugin_dir_path(__FILE__) . 'include/html.php';
    $template = ob_get_contents();
    ob_end_clean();
    echo $template;
}
/**
 * Register a custom sub menu page.
 */
add_action('admin_menu', 'wpdocs_register_sub_menu_page');
function wpdocs_register_sub_menu_page()
{
add_submenu_page(
    'my-new-menu-page',
    'Sub Menu Title',
    'Sub Menu Title',
    10,
    'sub-menu-page',
    'sub_menu_page_cb'
);
}
function sub_menu_page_cb(){
    echo "<h1>This is a Sub menu Page</h1>";
}
/**
 * Register a custom  menu page.
 */
add_action('admin_menu', 'wpdocs_register_assign_user_menu_page');
function wpdocs_register_assign_user_menu_page()
{
add_menu_page(
    'Assign User Title',
    'Assign User ',
    'manage_options',
    'assign-user',
    'wpdocs_register_assign_user_menu_page_cb'
);
}
function wpdocs_register_assign_user_menu_page_cb(){
    // echo "<h1>Assign Users</h1>";
    ob_start();
    include_once plugin_dir_path(__FILE__) . 'include/assign_user.php';
    $template = ob_get_contents();
    ob_end_clean();
    echo $template;
}