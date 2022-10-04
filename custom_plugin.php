<?php

/**
 * Plugin Name: Custom Plugin
 * Description:   Handle the custom function for the site
 */



add_action('admin_menu', 'wpdocs_register_my_custom_menu_page');

/**
 * Register a custom menu page.
 */
function wpdocs_register_my_custom_menu_page()
{
    add_menu_page(
        'Custom Menu Title',
        'Custom Menu Title',
        'manage_options',
        'wp-list-table',
        'wpdocs_register_my_custom_menu_page_cb',
    );
}
function wpdocs_register_my_custom_menu_page_cb()
{
    // echo "WP-List-Table";


    ob_start();

    include_once plugin_dir_path(__FILE__) . 'views/wp_table_list.php';
    $template = ob_get_contents();

    ob_end_clean();

    echo $template;
}




//create a custom post news
/* Custom Post Type Start */
function create_posttype()
{
    register_post_type(
        'news',
        // CPT Options
        array(
            'labels' => array(
                'name' => __('News'),
                'singular_name' => __('News')
            ),
            'public' => true,
            'has_archive' => false,
            'rewrite' => array('slug' => 'news'),
        )
    );
}
// Hooking up our function to theme setup
add_action('init', 'create_posttype');
/* Custom Post Type End */

//gives the features to our custom post all news page
/*Custom Post type start*/
function cw_post_type_news()
{
    $supports = array(
        'title', // post title
        'editor', // post content
        'author', // post author
        'thumbnail', // featured images
        'excerpt', // post excerpt
        'custom-fields', // custom fields
        'comments', // post comments
        'revisions', // post revisions
        'post-formats', // post formats
    );
    $labels = array(
        'name' => _x('news', 'plural'),
        'singular_name' => _x('news', 'singular'),
        'menu_name' => _x('news', 'admin menu'),
        'name_admin_bar' => _x('news', 'admin bar'),
        'add_new' => _x('Add New', 'add new'),
        'add_new_item' => __('Add New news'),
        'new_item' => __('New news'),
        'edit_item' => __('Edit news'),
        'view_item' => __('View news'),
        'all_items' => __('All news'),
        'search_items' => __('Search news'),
        'not_found' => __('No news found.'),
    );
    $args = array(
        'supports' => $supports,
        'labels' => $labels,
        'public' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'news'),
        'has_archive' => true,
        'hierarchical' => false,
    );
    register_post_type('news', $args);
}
add_action('init', 'cw_post_type_news');
/*Custom Post type end*/




// Creating a Meta Box
function custom_meta_box_markup($object)
{
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");

?>
    <div>
        <label for="meta-box-text">Text</label>
        <input name="meta-box-text" type="text" value="<?php echo get_post_meta($object->ID, "meta-box-text", true); ?>">

        <br>

        <label for="meta-box-dropdown">Dropdown</label>
        <select name="meta-box-dropdown">
            <?php
            $option_values = array(1, 2, 3);

            foreach ($option_values as $key => $value) {
                if ($value == get_post_meta($object->ID, "meta-box-dropdown", true)) {
            ?>
                    <option selected><?php echo $value; ?></option>
                <?php
                } else {
                ?>
                    <option><?php echo $value; ?></option>
            <?php
                }
            }
            ?>
        </select>

        <br>

        <label for="meta-box-checkbox">Check Box</label>
        <?php
        $checkbox_value = get_post_meta($object->ID, "meta-box-checkbox", true);

        if ($checkbox_value == "") {
        ?>
            <input name="meta-box-checkbox" type="checkbox" value="true">
        <?php
        } else if ($checkbox_value == "true") {
        ?>
            <input name="meta-box-checkbox" type="checkbox" value="true" checked>
        <?php
        }
        ?>
    </div>
<?php
}

function add_custom_meta_box()
{
    add_meta_box("demo-meta-box", "My Custom Meta Box", "custom_meta_box_markup", "post", "side", "high", null);
}

add_action("add_meta_boxes", "add_custom_meta_box");


function save_custom_meta_box($post_id, $post, $update)
{
    if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
        return $post_id;

    if (!current_user_can("edit_post", $post_id))
        return $post_id;

    if (defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;

    $slug = "post";
    if ($slug != $post->post_type)
        return $post_id;

    $meta_box_text_value = "";
    $meta_box_dropdown_value = "";
    $meta_box_checkbox_value = "";

    if (isset($_POST["meta-box-text"])) {
        $meta_box_text_value = $_POST["meta-box-text"];
    }
    update_post_meta($post_id, "meta-box-text", $meta_box_text_value);

    if (isset($_POST["meta-box-dropdown"])) {
        $meta_box_dropdown_value = $_POST["meta-box-dropdown"];
    }
    update_post_meta($post_id, "meta-box-dropdown", $meta_box_dropdown_value);

    if (isset($_POST["meta-box-checkbox"])) {
        $meta_box_checkbox_value = $_POST["meta-box-checkbox"];
    }
    update_post_meta($post_id, "meta-box-checkbox", $meta_box_checkbox_value);
}

add_action("save_post", "save_custom_meta_box", 10, 3);


//save_post for a perticular post



// /* Register a hook to fire only when the "my-cpt-slug" post type is saved */
// add_action( 'save_post_news', 'save_custom_meta_box', 10, 3 );

// /* When a specific post type's post is saved, saves our custom data
//  * @param int     $post_ID Post ID.
//  * @param WP_Post $post    Post object.
//  * @param bool    $update  Whether this is an existing post being updated or not.
// */
// function myplugin_save_postdata( $post_id, $post, $update ) {
//   // print_r($post);
//   // die();
//   // verify if this is an auto save routine. 
//   // If it is our form has not been submitted, so we dont want to do anything
//   if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
//       return;

//   // verify this came from the our screen and with proper authorization,
//   // because save_post can be triggered at other times

//   if ( !wp_verify_nonce( $_POST['meta-box-nonce'], plugin_basename( __FILE__ ) ) )
//       return;


//   // Check permissions
//   if ( 'page' == $post->post_type ) 
//   {
//     if ( !current_user_can( 'edit_page', $post_id ) )
//         return;
//   }
//   else
//   {
//     if ( !current_user_can( 'edit_post', $post_id ) )
//         return;
//   }

//   // OK, we're authenticated: we need to find and save the data

//   $mydata = $_POST['meta-box-text'];

//   // Do something with $mydata 
//   // probably using add_post_meta(), update_post_meta(), or 
//   // a custom table (see Further Reading section below)

//    return $mydata;
// }

//custom taxonomy--------

function wporg_register_taxonomy_course()
{
    $labels = array(
        'name'              => _x('Courses', 'taxonomy general name'),
        'singular_name'     => _x('Course', 'taxonomy singular name'),
        'search_items'      => __('Search Courses'),
        'all_items'         => __('All Courses'),
        'parent_item'       => __('Parent Course'),
        'parent_item_colon' => __('Parent Course:'),
        'edit_item'         => __('Edit Course'),
        'update_item'       => __('Update Course'),
        'add_new_item'      => __('Add New Course'),
        'new_item_name'     => __('New Course Name'),
        'menu_name'         => __('Course'),
    );
    $args   = array(
        'hierarchical'      => true, // make it hierarchical (like categories)
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => ['slug' => 'course'],
    );
    register_taxonomy('course', ['post'], $args);
}
add_action('init', 'wporg_register_taxonomy_course');

//end custom taxonamy--------



?>