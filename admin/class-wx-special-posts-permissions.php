<?php
namespace Wx_Special_Posts\Admin;
class Class_Wx_Special_Posts_Permissions {
    use Traits\Trait_Wx_Special_Posts_Form;

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

    /**
	 * Hook to render icons page
	 * @since    1.0.0
	 */
    public function wx_special_posts_settings_page(){
		/*
		add_menu_page(
			Wx_SPECIAL_POSTS_NAME.' Settings',// The title to be displayed in the browser window for this page.
			Wx_SPECIAL_POSTS_NAME.' Settings',// The text to be displayed for this menu item
			'manage_options',// Which type of users can see this menu item
			'wx_special_posts_settings',// The unique ID - that is, the slug - for this menu item
			array( $this, 'render_special_posts_options_page'),// The name of the function to call when rendering this menu's page
			'', // icon
			20, // position
		);
		*/

		add_submenu_page(
			'edit.php?post_type='.Wx_SPECIAL_POSTS_SLUG, //Parent Slug 
			Wx_SPECIAL_POSTS_NAME.' Settings',// The title to be displayed in the browser window for this page.
			Wx_SPECIAL_POSTS_NAME.' Settings',// The text to be displayed for this menu item
			'manage_options',// Which type of users can see this menu item
			'wx_special_posts_settings',// The unique ID - that is, the slug - for this menu item
			array( $this, 'render_special_posts_options_page'),// The name of the function to call when rendering this menu's page
			'', // icon
			20, // position
		);

    }

	/**
	 * Hook to render options page
	 * @since    1.0.0
	 */
    public function render_special_posts_options_page(){
        if ( is_file( Wx_SPECIAL_POSTS_DIR_PATH . 'admin/views/wx-special-posts.php' ) ) {
            include Wx_SPECIAL_POSTS_DIR_PATH . 'admin/views/wx-special-posts.php';
        }
    }

	/**
	 * Function to restrict user roles from listing
	 */
	public function wx_get_restricted_roles(){
		return [	
			'editor','contributor','bbp_moderator',
			'bbp_keymaster','bbp_participant','bbp_spectator',
			'bbp_blocked','group_leader','subscriber','customer','shop_manager'
		];
	}

	/**
	 * Register all settings sections here
	 * @since    1.0.0
	 */
	public function wx_init_special_posts_settings_tabs(){
		// Dashboard Tab Fields 
		$this->init_form_settings_options();
	}

}