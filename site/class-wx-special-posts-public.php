<?php
namespace Wx_Special_Posts\Site;
class Class_Wx_Special_Posts_Public {
	use Traits\Trait_Wx_Special_Posts_Common;

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$sp_enqueue_css = true;
		$sp_enqueue_css = apply_filters('wx_sp_enqueue_css',$sp_enqueue_css);

		if( !$sp_enqueue_css ){
			return;
		}

		wp_enqueue_style( 'bootstrap-5-css', Wx_SPECIAL_POSTS_ASSETS_DIR_URL . 'css/bootstrap.min.css', array(),false, 'all' );
		wp_enqueue_style( '-fa6-css', Wx_SPECIAL_POSTS_ASSETS_DIR_URL . 'css/fa-6/css/all.min.css', array(),false, 'all' );
		wp_enqueue_style( 'google-fonts-Montserrat','https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap', array(),false, 'all' );
		wp_enqueue_style( 'datatables.min.css', Wx_SPECIAL_POSTS_ASSETS_DIR_URL . 'css/datatables.min.css', array(),false, 'all' );
		wp_enqueue_style( 'editor.dataTables.min.css', Wx_SPECIAL_POSTS_ASSETS_DIR_URL . 'css/editor.dataTables.min.css', array(),false, 'all' );
		wp_enqueue_style( 'jquery-ui-css', Wx_SPECIAL_POSTS_ASSETS_DIR_URL . 'css/jquery-ui.css', array(),false, 'all' );
		// wp_enqueue_style( 'fancybox-css', Wx_SPECIAL_POSTS_ASSETS_DIR_URL . 'css/fancybox.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, Wx_SPECIAL_POSTS_ASSETS_DIR_URL . 'css/wx-special-posts-public.css', array(), $this->version, 'all' );
		wp_enqueue_media();

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$sp_enqueue_js = true;
		$sp_enqueue_js = apply_filters('wx_sp_enqueue_js',$sp_enqueue_js);
		
		if( !$sp_enqueue_js ){
			return;
		}

		wp_enqueue_script( 'jquery' );

		wp_enqueue_script( 'bootstrap-5', Wx_SPECIAL_POSTS_ASSETS_DIR_URL . 'js/bootstrap.bundle.min.js', array( 'jquery' ),false, true );
		
		wp_enqueue_script( 'datatables-js', Wx_SPECIAL_POSTS_ASSETS_DIR_URL . 'js/datatable/datatables.min.js', array( 'jquery' ),false, true );
		wp_enqueue_script( 'dataTables.buttons.min.js', Wx_SPECIAL_POSTS_ASSETS_DIR_URL . 'js/datatable/dataTables.buttons.min.js', array( 'jquery' ),false, true );
		wp_enqueue_script( 'jszip.min.js', Wx_SPECIAL_POSTS_ASSETS_DIR_URL . 'js/datatable/jszip.min.js', array( 'jquery' ),false, true );
		wp_enqueue_script( 'pdfmake.min.js', Wx_SPECIAL_POSTS_ASSETS_DIR_URL . 'js/datatable/pdfmake.min.js', array( 'jquery' ),false, true );
		wp_enqueue_script( 'vfs_fonts.js', Wx_SPECIAL_POSTS_ASSETS_DIR_URL . 'js/datatable/vfs_fonts.js', array( 'jquery' ),false, true );
		wp_enqueue_script( 'buttons.html5.min', Wx_SPECIAL_POSTS_ASSETS_DIR_URL . 'js/datatable/buttons.html5.min.js', array( 'jquery' ),false, true );
		wp_enqueue_script( 'buttons.print.min.js', Wx_SPECIAL_POSTS_ASSETS_DIR_URL . 'js/datatable/buttons.print.min.js', array( 'jquery' ),false, true );	
		wp_enqueue_script( 'dataTables.editor.min.js', Wx_SPECIAL_POSTS_ASSETS_DIR_URL . 'js/datatable/dataTables.editor.min.js', array( 'jquery' ),false, true );	


		wp_enqueue_script( 'jquery-validate-js', Wx_SPECIAL_POSTS_ASSETS_DIR_URL . 'js/jquery.validate.js', array( 'jquery' ),false, true );
		wp_enqueue_script( 'jquery-validate-methods-js', Wx_SPECIAL_POSTS_ASSETS_DIR_URL . 'js/additional-methods.js', array( 'jquery' ),false, true );
		
		wp_enqueue_script( 'jquery-ui-js', Wx_SPECIAL_POSTS_ASSETS_DIR_URL . 'js/jquery-ui.min.js', array( 'jquery' ),false, true );
		
		if( !in_array(bp_current_component() ,['messages']) ){
			wp_enqueue_script( 'tinymce.min.js', Wx_SPECIAL_POSTS_ASSETS_DIR_URL . 'js/tinymce/tinymce.min.js', array( 'jquery' ),false, true );
		}
		
		wp_enqueue_script( $this->plugin_name.'-sweet-alert', Wx_SPECIAL_POSTS_ASSETS_DIR_URL . 'js/sweetalert.min.js', array( 'jquery' ),false, true );
		wp_enqueue_script( $this->plugin_name.'-fancybox-js', Wx_SPECIAL_POSTS_ASSETS_DIR_URL . 'js/fancybox.umd.js', array( 'jquery' ),false, true );
		wp_enqueue_script( $this->plugin_name.'-select2-js', Wx_SPECIAL_POSTS_ASSETS_DIR_URL . 'js/select2/select2.min.js', array( 'jquery' ),false, true );
		
		wp_enqueue_script( $this->plugin_name.'-sp-flexible-content-js', Wx_SPECIAL_POSTS_ASSETS_DIR_URL . 'js/wx-sp-flexible-content.js', array( 'jquery' ), $this->version, true );

		wp_enqueue_script( $this->plugin_name, Wx_SPECIAL_POSTS_ASSETS_DIR_URL . 'js/wx-special-posts.js', array( 'jquery' ), $this->version, true );
		wp_localize_script( $this->plugin_name,'wx_special_posts_object',
	        array( 
	            'wx_support_ajaxurl'  				=> 	admin_url( 'admin-ajax.php' ),
				'edit_form_loader_html' 			=> 	$this->wx_get_edit_form_loader_html(),
				'Wx_special_posts_datatable_cols' 	=> 	$this->wx_get_special_posts_dashboard_cols(),
				'wx_special_post_name'				=>	Wx_SPECIAL_POSTS_NAME,
				'wx_special_post_slug'				=>	Wx_SPECIAL_POSTS_SLUG,
				'wx_special_post_category_name'		=>	Wx_SPECIAL_POSTS_TAXONOMY_NAME,
				'Wx_SPECIAL_POSTS_TAXONOMY_SLUG'	=>	Wx_SPECIAL_POSTS_TAXONOMY_SLUG,
	        )
	    );
	}


	/**
	 * Hook Into Other Plugin's Assets
	 * @since    1.0.0
	 */
	public function wx_hook_into_other_plugins_assests(){
		global $post;
		$post_content = $post->post_content ?? '';

		$my_plugin_shortcodes = array(
			'wx-sp-flexible-content',
			'wx-sp-flexible-content-output',
			'wx-special-posts-dashboard',
			'wx-add-special-post',
			'wx-group-special-posts',
		);
		

		foreach( $my_plugin_shortcodes as $shortcode){
			if (has_shortcode($post_content, $shortcode)) {
				wx_disallow_ai_assets();
				wx_disallow_emails_assets();
				break;
			}
		}
		
	}

}
