<?php

namespace Wx_Special_Posts\Includes;
class Class_Wx_Special_Posts_Cpt_Tax {
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.6.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.6.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.6.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

    /**
	 * @since    1.8.0
	 * Register Plugin's Post Types
	 * 
	 */ 
	public function register_wx_special_posts_types(){

		$wx_special_post_type_name = Wx_SPECIAL_POSTS_NAME;
		$wx_special_post_type_slug = Wx_SPECIAL_POSTS_SLUG;

		$wx_special_post_taxonomy_name = Wx_SPECIAL_POSTS_TAXONOMY_NAME;
		$wx_special_post_taxonomy_slug = Wx_SPECIAL_POSTS_TAXONOMY_SLUG;

        $labels = [
			"name" => __("{$wx_special_post_type_name}", "wx-special-posts" ),
			"singular_name" => __("{$wx_special_post_type_name}", "wx-special-posts" ),
		];
	
		$args = [
			"label" => __("{$wx_special_post_type_name}", "wx-special-posts" ),
			"labels" => $labels,
			"description" => "",
			"public" => true,
			"publicly_queryable" => true,
			"show_ui" => true,
			"show_in_rest" => true,
			"rest_base" => "",
			"rest_controller_class" => "WP_REST_Posts_Controller",
			"rest_namespace" => "wp/v2",
			"has_archive" => true,
			"show_in_menu" => true,
			"show_in_nav_menus" => true,
			"delete_with_user" => false,
			"exclude_from_search" => false,
			"capability_type" => "post",
			"map_meta_cap" => true,
			"hierarchical" => false,
			"can_export" => false,
			"rewrite" => [ "slug" => "{$wx_special_post_type_slug}", "with_front" => true ],
			"query_var" => true,
			"supports" => [ "title", "editor", "thumbnail","comments" ],
			"taxonomies" => ["post_tag"],
			"show_in_graphql" => false,
		];
	
		register_post_type("{$wx_special_post_type_slug}", $args );

	}
    
    /**
	 * @since    1.8.0
	 * Register Plugin's taxonomies
	 * 
	 */ 
	public function register_wx_special_posts_taxonomies(){
		$wx_special_post_type_name = Wx_SPECIAL_POSTS_NAME;
		$wx_special_post_type_slug = Wx_SPECIAL_POSTS_SLUG;

		$wx_special_post_taxonomy_name = Wx_SPECIAL_POSTS_TAXONOMY_NAME;
		$wx_special_post_taxonomy_slug = Wx_SPECIAL_POSTS_TAXONOMY_SLUG;

		$labels = [
			"name" => __("{$wx_special_post_taxonomy_name}", "wx-special-posts" ),
			"singular_name" => __("{$wx_special_post_taxonomy_name}", "wx-special-posts" ),
		];
		
		$args = [
			"label" => __("{$wx_special_post_taxonomy_name}", "wx-special-posts" ),
			"labels" => $labels,
			"public" => true,
			"publicly_queryable" => true,
			"hierarchical" => true,
			"show_ui" => true,
			"show_in_menu" => true,
			"show_in_nav_menus" => true,
			"query_var" => true,
			"rewrite" => [ 'slug' =>"{$wx_special_post_taxonomy_slug}", 'with_front' => true, ],
			"show_admin_column" => true,
			"show_in_rest" => true,
			"show_tagcloud" => false,
			"rest_base" => "{$wx_special_post_taxonomy_slug}",
			"rest_controller_class" => "WP_REST_Terms_Controller",
			"show_in_quick_edit" => true,
			"show_in_graphql" => false,
		];
		register_taxonomy("{$wx_special_post_taxonomy_slug}", ["$wx_special_post_type_slug"], $args );
	}
}