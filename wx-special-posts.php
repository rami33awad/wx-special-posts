<?php

/**
 * Plugin Name:       Wx Special Posts
 * Plugin URI:        
 * Description:       This is a special posts wp plugin
 * Version:           1.0.0
 * Author:            Saurabh Kumar
 * Author URI:        https://github.com/phpdev-saurabh
 * License:           GPL-2.0+
 * License URI:       
 * Text Domain:       wx-special-posts
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

//*Inlcude Config File
require_once plugin_dir_path( __FILE__ ).'config.php';

//? Set Dev False During Production  
$dev = 1;
if( $dev ){
    $plugin_version = rand(1,10000000);
}else{
    $plugin_version = '1.0.0';
}

define( 'Wx_SPECIAL_POSTS_VERSION',$plugin_version);
define( 'Wx_SPECIAL_POSTS_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'Wx_SPECIAL_POSTS_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'Wx_SPECIAL_POSTS_ASSETS_DIR_URL', plugin_dir_url( __FILE__ ).'site/assets/' );
define( 'Wx_SPECIAL_POSTS_ADMIN_ASSETS_DIR_URL', plugin_dir_url( __FILE__ ).'admin/assets/' );

//*Autoload Every Class in Plugin
require_once Wx_SPECIAL_POSTS_DIR_PATH.'autoloading.php';

//* Includes Helpers 
require_once Wx_SPECIAL_POSTS_DIR_PATH.'helpers/helper.php';

//* BP Special Posts create notification 
require_once Wx_SPECIAL_POSTS_DIR_PATH.'helpers/BP_Speical_Posts_Notification.php';

//* BP Group Custom Extension 
require_once Wx_SPECIAL_POSTS_DIR_PATH.'helpers/BP_Special_Posts_Group_Tab.php';

use Wx_Special_Posts\Includes\Class_Wx_Special_Posts as Wx_Special_Posts;
use Wx_Special_Posts\Includes\Class_Wx_Special_Posts_Activator as Wx_Special_Posts_Activator;
use Wx_Special_Posts\Includes\Class_Wx_Special_Posts_Deactivator as Wx_Special_Posts_Deactivator;

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function activate_wx_special_posts() {
	Wx_Special_Posts_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
function deactivate_wx_special_posts() {
	Wx_Special_Posts_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wx_special_posts' );
register_deactivation_hook( __FILE__, 'deactivate_wx_special_posts' );


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wx_special_posts() {
	$plugin = new Wx_Special_Posts();
	$plugin->run();
}
add_action( 'plugins_loaded','run_wx_special_posts');
