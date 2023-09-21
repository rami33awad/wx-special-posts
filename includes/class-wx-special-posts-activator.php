<?php
namespace Wx_Special_Posts\Includes;

use Wx_Special_Posts\Includes\Class_Wx_Special_Posts_Install as Wx_Special_Posts_Install;

class Class_Wx_Special_Posts_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		//* Create plugin pages
		Wx_Special_Posts_Install::wx_special_posts_create_pages(); 
	}

}
