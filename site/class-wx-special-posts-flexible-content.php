<?php

namespace Wx_Special_Posts\Site;
class Class_Wx_Special_Posts_Flexible_Content {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.1.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.1.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.1.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register shortcode for flexible content fields
	 *
	 * @since    1.0.0
	 */
	public function wx_sp_flexible_content_shortcode_fun($attr)
	{
		$wx_sp_flexible_content_section_title = $attr['title'] ?? 'Flexible Content';
		$post_id = $attr['post_id'] ?? 0;

		$wx_sp_flexible_content = get_post_meta($post_id,'wx_sp_flexible_content',true) ?? [];

		if ( is_file( Wx_SPECIAL_POSTS_DIR_PATH . 'views/sp-flexible-content/wx-sp-flexible-content.php' ) ) {
            ob_start();
            include Wx_SPECIAL_POSTS_DIR_PATH . 'views/sp-flexible-content/wx-sp-flexible-content.php';
            return ob_get_clean();
        }
	}

	/**
	 * Set Flexible Content Output for all
	 *
	 * @since    1.1.0
	 */
	public function wx_sp_flexible_content_output_fun($args){
		$post_id = $args['post_id'] ?? 0;
		$user_id = $args['user_id'] ?? 0;
		$wx_sp_flexible_content = get_post_meta($post_id,'wx_sp_flexible_content',true) ?? [];
		if ( is_file( Wx_SPECIAL_POSTS_DIR_PATH . 'views/sp-flexible-content/wx-sp-flexible-content-output.php' ) ) {
            ob_start();
            	if( is_array($wx_sp_flexible_content) ){
            		// wx_print_a($wx_sp_flexible_content);
            		include Wx_SPECIAL_POSTS_DIR_PATH . 'views/sp-flexible-content/wx-sp-flexible-content-output.php';
            	}
            return  ob_get_clean();
        }
	}
}