<?php
if (!class_exists('class-wp-list-table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}
class WpTableListClass extends WP_List_Table
{
    public function prepare_items()
    {
        if (isset($_POST['s'])) {
            $search_term = $_POST['s'];
            // print_r($search_term);
        } else {
            $search_term = '';
        }
        $datas = $this->wlt_list_table_data($search_term);
        //Pagination feature -----
        $per_page = 1;
        $current_page = $this->get_pagenum();
        $total_items = count($datas);
        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page' => $per_page
        ));
        $this->items = array_slice($datas, (($current_page - 1) * $per_page), $per_page);
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        //  $this->items=$this->wlt_list_table_data($order_by,$order,$search_form, $this->users_data);
    }
    public function get_columns()
    {
        $columns = array(
            'id' => 'ID',
            'title' => 'Title',
            'content' => 'Content',
            'post_date' => 'Post Date',
        );
        return $columns;
    }
    public function column_default($item, $column_name)
    {
        // echo '<pre>'; print_r($item); echo '</pre>';
        switch ($column_name) {
            case 'id':
            case 'title':
            case 'content':
            case 'post_date':
                return $item[$column_name];
            default:
                return 'no value';
        }
    }
    public function column_cb($item)
    {
        $checkbox = '<input type="checkbox"/>';
        return $checkbox;
    }
    public function get_hidden_columns()
    {
        return array('name');
    }
    public function get_sortable_columns()
    {
        return array(
            "title" => array("title", true)
        );
    }
    public function wlt_list_table_data($search_term = '')
    {
        //global $wpdb;
        // if (!empty($search_term)) {
        //    // print_r($search_term);
        //     $all_posts = $wpdb->get_results(
        //         "SELECT * from ".$wpdb->posts." WHERE post_type = 'news' AND post_status = 'publish' AND (post_title LIKE '%$search_term%' OR post_content LIKE '%$search_term%' )"
        //     );
        //     //print_r($all_posts);
        // } else {
        //     $all_posts = get_posts(array(
        //         "post_type" => "news",
        //         "post_status" => "publish",
        //     ));
        //     //print_r($all_posts);die();
        // }
        $args = array(
            "post_type" => "news",
            "post_status" => "publish",
        );
        if (!empty($search_term)) {
            $args['s'] = $search_term;    //add a key value in $args array 
        }
        $all_posts = get_posts($args);
        $post_array = array();
        if (count($all_posts) > 0) {
            foreach ($all_posts as $index => $post) {
                $post_array[] = array(                       //push a array in $post_array
                    "id" => $post->ID,
                    "title" => $post->post_title,
                    "content" => ($post->post_content == '') ? 'Null' : $post->post_content,
                    "post_date" => $post->post_date,
                );
            }
        }
        return $post_array;
    }
}


echo "<h1>This is custom table list</h1>";
echo "<hr/>";
echo "<h1>Add New   <button type='button' id='insert-media-button' class='button insert-media add_media' data-editor='content'><span class='wp-media-buttons-icon'></span><a href='http://localhost/jamtech/wordpress/wp-admin/post-new.php?post_type=news' class='page-title-action'>Add New</a></button></h1>";
$obj = new WpTableListClass();
$obj->prepare_items();
echo "<form name='frm_search_post' method='post' action='" . $_SERVER['PHP_SELF'] . "?page=wp-list-table'>";
$obj->search_box("Search Post(s)", "search_post_id");  //first argument label
echo "</form>";
$obj->display();
