<?php
namespace Wx_Special_Posts\Includes;

use Wx_Special_Posts\Includes\Class_Wx_Special_Posts_Loader as Wx_Special_Posts_Loader;
use Wx_Special_Posts\Includes\Class_Wx_Special_Posts_i18n as Wx_Special_Posts_i18n;
use Wx_Special_Posts\Admin\Class_Wx_Special_Posts_Admin as Wx_Special_Posts_Admin;
use Wx_Special_Posts\Site\Class_Wx_Special_Posts_Public as Wx_Special_Posts_Public;
use Wx_Special_Posts\Includes\Class_Wx_Special_Posts_Cpt_Tax as Wx_Special_Posts_Cpt_Tax;
use Wx_Special_Posts\Site\Class_Wx_Special_Posts_Handler as Wx_Special_Posts_Handler;
use Wx_Special_Posts\Site\Class_Wx_Special_Posts_Flexible_Content as Wx_Special_Posts_Flexible_Content;


use Wx_Special_Posts\Admin\Class_Wx_Special_Posts_Permissions as Wx_Special_Posts_Permissions;



class Class_Wx_Special_Posts {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Plugin_Name_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'Wx_SPECIAL_POSTS_VERSION' ) ) {
			$this->version = Wx_SPECIAL_POSTS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'wx-special-posts';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_shortcode_ajax_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Plugin_Name_Loader. Orchestrates the hooks of the plugin.
	 * - Plugin_Name_i18n. Defines internationalization functionality.
	 * - Plugin_Name_Admin. Defines all hooks for the admin area.
	 * - Plugin_Name_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		$this->loader = new Wx_Special_Posts_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Plugin_Name_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wx_Special_Posts_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Wx_Special_Posts_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		//* Wiliex Icons page
		$wx_social_icons = new Wx_Special_Posts_Permissions($this->get_plugin_name(), $this->get_version());
		$this->loader->add_action( 'admin_menu', $wx_social_icons, 'wx_special_posts_settings_page' );
		//* Render Wiliex Icons page settings..
		$this->loader->add_action( 'admin_init', $wx_social_icons, 'wx_init_special_posts_settings_tabs' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Wx_Special_Posts_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		//* Init Custom Post Types and Taxonomies 
		$plugin_cpt_tax = new Wx_Special_Posts_Cpt_Tax( $this->get_plugin_name(), $this->get_version() );		
		$this->loader->add_action( 'init', $plugin_cpt_tax, 'register_wx_special_posts_types',1);
		$this->loader->add_action( 'init', $plugin_cpt_tax, 'register_wx_special_posts_taxonomies',1);

		//*Flexible Content Shortcode
		$plugin_sp_flexible_content = new Wx_Special_Posts_Flexible_Content( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_shortcode('wx-sp-flexible-content', $plugin_sp_flexible_content, 'wx_sp_flexible_content_shortcode_fun' );
		$this->loader->add_shortcode('wx-sp-flexible-content-output', $plugin_sp_flexible_content, 'wx_sp_flexible_content_output_fun' );
	 
		
	}

	/**
	 * Register all of the shortcode Hooks
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_shortcode_ajax_hooks() {
		//* Define Special Posts Dashboard Hooks
		$this->wx_special_posts_dashboard_shortcode_ajax_hooks(); 
	}

	/**
	 * Register Dashboard Shortcode Hooks 
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function wx_special_posts_dashboard_shortcode_ajax_hooks(){
		$plugin_sp_handler = new Wx_Special_Posts_Handler( $this->get_plugin_name(), $this->get_version() );

		//*Wx Special Posts Dashboard shortcode
		$this->loader->add_shortcode('wx-special-posts-dashboard',$plugin_sp_handler, 'wx_special_posts_dashboard_view');
		//*Wx Special Posts Add Form 
		$this->loader->add_shortcode('wx-add-special-post', $plugin_sp_handler, 'wx_special_post_add_form_fun' );
		//*Wx Special POsts from Group  
		$this->loader->add_shortcode('wx-group-special-posts', $plugin_sp_handler, 'wx_special_post_of_group' );


		//* Wx Ajax Special Posts Datatable 
		$this->loader->add_action('wp_ajax_wx_get_special_posts_datatable',$plugin_sp_handler, 'wx_get_special_posts_datatable' );
		//* Wx Ajax create special post 
		$this->loader->add_action('wp_ajax_wx_create_special_post',$plugin_sp_handler, 'wx_create_special_post' );
		//* Wx Ajax load edit special post
		$this->loader->add_action('wp_ajax_wx_load_edit_special_post',$plugin_sp_handler, 'wx_load_edit_special_post' );
		//* Wx Ajax update special post
		$this->loader->add_action('wp_ajax_wx_edit_special_post',$plugin_sp_handler, 'wx_edit_special_post' );
		//* Wx Ajax delete special post  
		$this->loader->add_action('wp_ajax_wx_delete_special_post',$plugin_sp_handler, 'wx_delete_special_post' );
		//* Wx Ajax Load Sub Categories of main category
		$this->loader->add_action('wp_ajax_wx_special_post_load_sub_categories',$plugin_sp_handler, 'wx_special_post_load_sub_categories' ); 
		//* Wx Ajax Load Special Post Group Members
		$this->loader->add_action('wp_ajax_wx_special_post_group_load_members',$plugin_sp_handler, 'wx_special_post_group_load_members' ); 

		//* Filter for archive template special posts
		$this->loader->add_filter('archive_template',$plugin_sp_handler, 'archive_template_special_post' );
		//* Single Content Override Override
		$this->loader->add_filter( 'template_include', $plugin_sp_handler, 'wx_override_special_post_template');

		//* Archive Page ajax Filter
		$this->loader->add_action('wp_ajax_wx_apply_special_posts_archive_page_filter',$plugin_sp_handler, 'wx_apply_special_posts_archive_page_filter' ); 

		 

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Plugin_Name_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
