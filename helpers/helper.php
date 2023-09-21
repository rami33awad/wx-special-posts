<?php 

/**
 * Helper functions to print data in <pre> tags and die
 *
 * @since    1.0.0
 */
if (!function_exists('dd')) {
    function dd($arr)
    {
        echo '<pre>';
        print_r($arr);
        echo '</pre>';
        exit;
    }
}


/**
 * Helper functions to print data in <pre> tags and continue
 *
 * @since    1.0.0
 */
if (!function_exists('dump')) {
    function dump($arr)
    {
        echo '<pre>';
        print_r($arr);
        echo '</pre>';
    }
}

/**
 * Helper functions to get page by title
 *
 * @since    1.0.0
 */
if (!function_exists('wx_get_page_by_title')) {
    function wx_get_page_by_title($title)
    {   
        $posts = get_posts(
            array(
                'post_type'              => 'page',
                'title'                  => $title,
                'post_status'            => 'all',
                'numberposts'            => 1,
                'update_post_term_cache' => false,
                'update_post_meta_cache' => false,           
                'orderby'                => 'post_date ID',
                'order'                  => 'ASC',
            )
        );
         
        if ( ! empty( $posts ) ) {
            return $page_got_by_title = $posts[0];
        } else {
            return $page_got_by_title = null;
        }
    }
}


/**
 * Helper functions to get page by title
 *
 * @since    1.0.0
 */
if (!function_exists('wx_get_page_by_slug')) {
    function wx_get_page_by_slug($slug)
    {   
        $posts = get_posts(
            array(
                'post_type'              => 'page',
                'name'                   => $slug,
                'post_status'            => 'all',
                'numberposts'            => 1,
                'update_post_term_cache' => false,
                'update_post_meta_cache' => false,           
                'orderby'                => 'post_date ID',
                'order'                  => 'ASC',
            )
        );
         
        if ( ! empty( $posts ) ) {
            return $page_got_by_title = $posts[0];
        } else {
            return $page_got_by_title = null;
        }
    }
}

/**
 * Helper functions to check roles perms
 *
 * @since    1.0.0
 */
if (!function_exists('wx_check_user_roles_perms')) {
    function wx_check_user_roles_perms($roles,$perms_roles)
    {
        foreach($roles as $role){
            if( in_array($role,$perms_roles) ){
                return true;
            }
        }
        return false;
    }
}