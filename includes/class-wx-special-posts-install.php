<?php

namespace Wx_Special_Posts\Includes;

class Class_Wx_Special_Posts_Install {
    /**
	 * Register Plugin pages
	 *
	 * @since    1.0.0
	 */
    public static function wx_special_posts_pages(){
        $pages = [
            [
                'title'         =>  Wx_SPECIAL_POSTS_NAME.' Dashboard',
                'shortcode'     =>  '[wx-special-posts-dashboard]',
                'menu_page'     =>  false,
                'permissions'  =>  ['administrator','admin'],
                'icon'  => []
            ]          
        ];
        return $pages;
    }

    public static function wx_special_posts_create_pages(){
        $pages = self::wx_special_posts_pages();
        foreach ($pages as $key => $page) {

            $title      = $page['title'] ?? '';
            $shortcode  = $page['shortcode'] ?? '';
            $page_content = "";

            if($shortcode){
                $page_content = "<!-- wp:shortcode -->
                $shortcode
                <!-- /wp:shortcode -->";
            }

            $page_title = $title;
            $slug  = $page['slug'] ?? '';
            $template = $page['page_template'] ?? '';
            if($slug){
                $is_page_exits = wx_get_page_by_slug($slug);
            }else{
                $is_page_exits = wx_get_page_by_title($title);
            }
            if(!$is_page_exits){
                $args = [
                    'post_title' => $page_title,
                    'post_type' => 'page',
                    'post_status' => 'publish',
                    'post_content' => $page_content
                ];
                if(array_key_exists('slug',$page)){
                    $args['post_name'] = $page['slug'];
                }
                $page_id = wp_insert_post($args);
                if($template){
                    update_post_meta($page_id,'_wp_page_template',$template);
                }
            }

        }
    }
}