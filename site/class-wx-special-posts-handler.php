<?php
namespace Wx_Special_Posts\Site;
class Class_Wx_Special_Posts_Handler {
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
	 * Register shortcode for Special Posts Dashboard View
	 *
	 * @since    1.0.0
	 */
	public function wx_special_posts_dashboard_view(){
		
		if ( is_file( Wx_SPECIAL_POSTS_DIR_PATH . 'views/dashboard/wx-special-posts-dashboard.php' ) ) {
            ob_start();
            include Wx_SPECIAL_POSTS_DIR_PATH . 'views/dashboard/wx-special-posts-dashboard.php';
            return ob_get_clean();
        }
	}

	/**
	 * Register shortcode for Special Posts Add View
	 *
	 * @since    1.0.0
	 */
	public function wx_special_post_add_form_fun(){
		$current_user 	= 	wp_get_current_user();
		$group_ids 		= 	$this->wx_bp_get_user_group_ids($current_user->ID);
		$user_friends   = 	$this->wx_bp_get_friends();
		
		$all_group_ids 		= 	$this->wx_get_all_bb_groups_ids();
		$all_group_members	= 	$this->wx_bp_get_groups_users($all_group_ids,$current_user->ID);

		if ( is_file( Wx_SPECIAL_POSTS_DIR_PATH . 'views/dashboard/wx-special-posts-add.php' ) ) {
            ob_start();
            include Wx_SPECIAL_POSTS_DIR_PATH . 'views/dashboard/wx-special-posts-add.php';
            return ob_get_clean();
        }
	}

	/**
	 * Ajax Hook to save special post
	 *
	 * @since    1.0.0
	 */
	public function wx_create_special_post(){
		$post_data = array();
        parse_str($_POST['formData'], $post_data); 

        // wp_nonce security.
        if ( ! wp_verify_nonce( $post_data['wx_create_special_post_nonce_field'] ,'wx_create_special_post') ) {
            wp_send_json_error([ 'message' => 'You are not allowed to perform this action.'],422);
        }
	

		$wx_special_post_title 			= $post_data['wx_special_post_title'] ?? '';
		$wx_special_post_category 		= $post_data['wx_special_post_category'] ?? 0;
		$wx_special_post_sub_category	=	$post_data['wx_special_post_sub_category'] ?? 0;

	
		$wx_sp_tags 					=	$post_data['wx_special_post_tags'] ?? [];
		$wx_sp_assigned_group 			= 	$post_data['wx_special_post_assigned_group'] ?? 0;
		$wx_sp_assigned_group_members 	= 	$post_data['wx_special_post_assigned_group_members'] ?? [];
		$wx_sp_friends 					= 	$post_data['wx_special_post_friends'] ?? [];
		$wx_sp_all_group_members 		= 	$post_data['wx_special_post_all_group_members'] ?? [];
		$sp_flexible_content 			= 	$post_data['sp_flexible_content'] ?? [];

		$current_user = wp_get_current_user();

		$args = [
			'post_type'			=> 	Wx_SPECIAL_POSTS_SLUG,
			'post_title'		=> 	$wx_special_post_title,
			'comment_status'	=>	'open',
			'ping_status'		=>	'open',
			'post_status'   	=> 	'publish',
		];

		$args['post_author'] = $current_user->ID;

		$special_post_id = wp_insert_post($args);

		//* Insert special posts Terms 
		$special_post_terms = [(int)$wx_special_post_category,(int)$wx_special_post_sub_category];
		wp_set_object_terms($special_post_id,$special_post_terms,Wx_SPECIAL_POSTS_TAXONOMY_SLUG);

		//* Insert special posts tags 
		$wx_special_post_tags_final = [];
		foreach($wx_sp_tags as $tag){
			$wx_special_post_tags_final[] = (int)$tag;
		}
		wp_set_post_tags((int)$special_post_id,$wx_special_post_tags_final);


		//* Save Post Meta 
		update_post_meta($special_post_id,'wx_special_post_assigned_group',$wx_sp_assigned_group);
		update_post_meta($special_post_id,'wx_special_post_assigned_group_members',$wx_sp_assigned_group_members);
		update_post_meta($special_post_id,'wx_special_post_friends',$wx_sp_friends);
		update_post_meta($special_post_id,'wx_special_post_all_group_members',$wx_sp_all_group_members);
		update_post_meta($special_post_id,'wx_sp_flexible_content',$sp_flexible_content);

		$tagged_members = [];
		foreach($wx_sp_assigned_group_members as $member){
			if( !in_array($member,$tagged_members) ){
				$tagged_members[] = $member;
			}
		}
		foreach($wx_sp_all_group_members as $member){
			if( !in_array($member,$tagged_members) ){
				$tagged_members[] = $member;
			}
		}
		foreach($wx_sp_friends as $member){
			if( !in_array($member,$tagged_members) ){
				$tagged_members[] = $member;
			}
		}

		//* Special Post Create Notification 
		foreach($tagged_members as $member){
			bp_notifications_add_notification( array(
				'user_id'           =>	$member,
				'item_id'           => 	$special_post_id,
				'component_name'    => 	'special_posts',
				'component_action'  => 	'special_posts_created',
				'date_notified'     => 	bp_core_current_time(),
				'is_new'            => 	1,
			)); 
		}

		wp_send_json_success([
			'msg' 	=> Wx_SPECIAL_POSTS_NAME.' created successfully',
			'id'	=>	$special_post_id,
		]);

	}

	/**
	 * Ajax Hook to return edit Special Post View
	 *
	 * @since    1.0.0
	 */
	public function wx_load_edit_special_post(){
		$html = '';

		$wx_special_post_id = $_POST['wx_special_post_id'] ?? 0;

		$special_post = get_post($wx_special_post_id);

		$current_user 	= wp_get_current_user();
		$group_ids 		= $this->wx_bp_get_user_group_ids($current_user->ID);
		$user_friends   = $this->wx_bp_get_friends();
		
		$all_group_ids 		= 	$this->wx_get_all_bb_groups_ids();
		$all_group_members	= 	$this->wx_bp_get_groups_users($all_group_ids,$current_user->ID);

		$wx_sp_assigned_group 				= 	get_post_meta($wx_special_post_id,'wx_special_post_assigned_group',true);
		$wx_sp_assigned_group_members 		= 	get_post_meta($wx_special_post_id,'wx_special_post_assigned_group_members',true);
		$wx_sp_friends						=	get_post_meta($wx_special_post_id,'wx_special_post_friends',true);
		$wx_sp_all_group_members			=   get_post_meta($wx_special_post_id,'wx_special_post_all_group_members',true);
		

		$group_members	= [];
		if($wx_sp_assigned_group){
			$group_members	= $this->wx_bp_get_groups_users([$wx_sp_assigned_group],$current_user->ID);
		}

		if ( is_file( Wx_SPECIAL_POSTS_DIR_PATH . 'views/dashboard/wx-special-posts-edit.php' ) ) {
            ob_start();
            include Wx_SPECIAL_POSTS_DIR_PATH . 'views/dashboard/wx-special-posts-edit.php';
            $html =  ob_get_clean();
        }

		wp_send_json_success([
			'html'	=> $html
		]);
	}

	/**
	 * Ajax Hook to update special post
	 *
	 * @since    1.0.0
	 */
	public function wx_edit_special_post(){
		$post_data = array();
        parse_str($_POST['formData'], $post_data); 

        // wp_nonce security.
        if ( ! wp_verify_nonce( $post_data['wx_edit_special_post_nonce_field'] ,'wx_edit_special_post') ) {
            wp_send_json_error([ 'message' => 'You are not allowed to perform this action.'],422);
        }

		
		$current_user = wp_get_current_user();

		$wx_special_post_id				=	$post_data['wx_special_post_id'] ?? 0;
		$wx_special_post_title			=	$post_data['wx_special_post_title'] ?? '';
		$wx_special_post_category		=	$post_data['wx_special_post_category'] ?? 0;
		$wx_special_post_sub_category	=	$post_data['wx_special_post_sub_category'] ?? 0;


		$wx_sp_tags 					=	$post_data['wx_special_post_tags'] ?? [];
		$wx_sp_assigned_group 			= 	$post_data['wx_special_post_assigned_group'] ?? 0;
		$wx_sp_assigned_group_members 	= 	$post_data['wx_special_post_assigned_group_members'] ?? [];
		$wx_sp_friends 					= 	$post_data['wx_special_post_friends'] ?? [];
		$wx_sp_all_group_members 		= 	$post_data['wx_special_post_all_group_members'] ?? [];
		$sp_flexible_content 			= 	$post_data['sp_flexible_content'] ?? [];

		$special_post = get_post($wx_special_post_id);
		if(!$special_post){
			wp_send_json_error([
				'msg'	=>	'Invalid '.Wx_SPECIAL_POSTS_NAME,
			]);
		}

		$args = [
			'ID'			=> $wx_special_post_id,
			'post_title'	=> $wx_special_post_title,
			'post_status'   => 'publish',
		];
		$special_post_id	 = wp_update_post($args); 

		//* Modified By 
		update_post_meta($wx_special_post_id,'wx_special_post_modified_by',$current_user->ID);

		//* Insert special posts Terms 
		$special_post_terms = [(int)$wx_special_post_category,(int)$wx_special_post_sub_category];
		wp_set_object_terms($wx_special_post_id,$special_post_terms,Wx_SPECIAL_POSTS_TAXONOMY_SLUG);

		//* Insert special posts tags 
		$wx_special_post_tags_final = [];
		foreach($wx_sp_tags as $tag){
			$wx_special_post_tags_final[] = (int)$tag;
		}
		wp_set_post_tags((int)$wx_special_post_id,$wx_special_post_tags_final);


		//* Save Post Meta 
		update_post_meta($special_post_id,'wx_special_post_assigned_group',$wx_sp_assigned_group);
		update_post_meta($special_post_id,'wx_special_post_assigned_group_members',$wx_sp_assigned_group_members);
		update_post_meta($special_post_id,'wx_special_post_friends',$wx_sp_friends);
		update_post_meta($special_post_id,'wx_special_post_all_group_members',$wx_sp_all_group_members);
		update_post_meta($special_post_id,'wx_sp_flexible_content',$sp_flexible_content);

		wp_send_json_success([
			'msg' => Wx_SPECIAL_POSTS_NAME.' updated successfully'
		]);
		
	}

	/**
	 * Ajax Hook to delete special post
	 *
	 * @since    1.0.0
	 */
	public function wx_delete_special_post(){
		$wx_special_post_id	=	$_POST['wx_special_post_id'] ?? 0;

		wp_delete_post($wx_special_post_id);

		wp_send_json_success([
			'msg' => Wx_SPECIAL_POSTS_NAME.' deleted successfully',
		]);
	}

	/**
	 * Ajax Hook to load sub categories of main category
	 *
	 * @since    1.0.0
	 */
	public function wx_special_post_load_sub_categories(){
		$cat_id	=	$_POST['cat_id'] ?? 0;
		$terms = get_terms(Wx_SPECIAL_POSTS_TAXONOMY_SLUG,array(
			'hide_empty' 	=>	false,
			'parent' 		=> 	$cat_id,
			'orderby' 		=> 	'name',
			'order' 		=> 	'ASC',
		));
		$html = '<option value="">Select Sub Category</option>';
		foreach($terms as $term){
			$html .= '<option value="'.$term->term_id.'">'.$term->name.'</option>';
		}
		wp_send_json_success([
			'html'	=>	$html,
		]);
	}

	/**
	 * Ajax Hook to load group members
	 *
	 * @since    1.0.0
	 */
	public function wx_special_post_group_load_members(){
		$current_user 	= wp_get_current_user();
		$group_id	=	$_POST['group_id'] ?? 0;
		$group_members	= $this->wx_bp_get_groups_users([$group_id],$current_user->ID);
		$html = '';
		foreach($group_members as $group_member){
			$html .= '<option value="'.$group_member->ID.'">'.$group_member->data->display_name.'</option>';
		}
		wp_send_json_success([
			'html'	=>	$html
		]);
	}

	public function archive_template_special_post($tpl){
		if ( is_post_type_archive ( Wx_SPECIAL_POSTS_SLUG ) ) {
			$tpl = Wx_SPECIAL_POSTS_DIR_PATH . 'views/dashboard/wx-special-posts-archive.php';
		}
		return $tpl;
	}

	/**
	 * Special Posts Datatable
	 *
	 * @since    1.0.0
	 */
	public function wx_get_special_posts_datatable(){
		$data = json_decode(file_get_contents('php://input'), true);

		$category 	=    isset($data['columns'][2]['search']['value']) 
                    && !empty($data['columns'][2]['search']['value']) 
                    ? [$data['columns'][2]['search']['value']] : false;  
        $category = preg_replace('/[@\$\^\;\" "]+/', '', $category);

		$sub_category 	=    isset($data['columns'][3]['search']['value']) 
                    && !empty($data['columns'][3]['search']['value']) 
                    ? [$data['columns'][3]['search']['value']] : false;  
        $sub_category = preg_replace('/[@\$\^\;\" "]+/', '', $sub_category);

		$bb_group 	=    isset($data['columns'][4]['search']['value']) 
                    && !empty($data['columns'][4]['search']['value']) 
                    ? [$data['columns'][4]['search']['value']] : false;  
        $bb_group = preg_replace('/[@\$\^\;\" "]+/', '', $bb_group);


		$author_id 	=    isset($data['columns'][5]['search']['value']) 
                    && !empty($data['columns'][5]['search']['value']) 
                    ? [$data['columns'][5]['search']['value']] : false;  
        $author_id = preg_replace('/[@\$\^\;\" "]+/', '', $author_id);

		
		$search = isset($data['search']['value']) 
                    && !empty($data['search']['value']) 
                    ? $data['search']['value'] : false;


		$offset = $data['start'] ?? 0;
        $numberposts = $data['length'] ?? 10;
        $draw = $data['draw'] ?? 1;

        $page = ($offset / $numberposts );

        $paged = ($page == 0 )? 1 : ($page+1);

        $output = [];

        $args = array(
            'posts_per_page' => $numberposts,
            'post_type'      => Wx_SPECIAL_POSTS_SLUG,
            'post_status'    => 'publish',
            'order'          => 'DESC',
            'orderby'        => 'modified',
        );

		//* Order argument
		$order_dir = $data['order'][0]['dir'] ?? '';
		$order = ($order_dir == "asc")?'ASC':'DESC';
		$order_column = $data['order'][0]['column'] ?? 0;

				
		//* Search Argument
		if ($search) {
            $args['s'] = $search;
        } 

		//* Taxonomy Filter 
		$args['tax_query'] = [
			'relation' => 'AND',
		]; 

		//* Meta Query
		$args['meta_query'] = [
			'relation' => 'AND',
		]; 


		//* Category Sub Category Filter 
		if ($category || $sub_category) {
			$term_ids = [];
			if($category){
				$term_ids[] = $category[0];
			}
			if($sub_category){
				$term_ids[] = $sub_category[0];
			}

			array_push($args['tax_query'],[
				'taxonomy' 	=> Wx_SPECIAL_POSTS_TAXONOMY_SLUG,
				'field' 	=> 'term_id',          
				'terms' 	=> $term_ids,
				'operator' 	=> 'IN',
			]);            
        }


		//* BB Group Filter  
		if($bb_group){
			array_push($args['meta_query'],array(
				'key'     => '_related_groups',
				'value'   => $bb_group[0],
				'compare' => '='
			));
		}

		//* Author Id
		if($author_id){
			$args['author'] = $author_id[0];
		} 

		//* Order By Cloumns
		if($order_column == 0 || $order_column == 1  || $order_column == 2){
			$args['orderby'] = 'name';
			$args['order']   = $order;
		} 
		if($order_column == 3){	
			$args['orderby'] = 'date';
			$args['order']   = $order;
		}


		$date_format = get_option('date_format');

		$query = new \WP_Query($args);  
        $found_post = $query->found_posts ?? 0;
        if($query->have_posts()){
            while($query->have_posts()){
                $query->the_post();

				$special_post_id = get_the_ID();

				//* Created BY
				$modified_by_name = '';
				$author_id = get_post_meta($special_post_id,'wx_special_post_modified_by',true);
				if($author_id){
					$modified_by_name = get_the_author_meta( 'display_name' , $author_id ); 
				}

				//* Created BY
				$created_by_name = '';
				$author_id = get_post_field ('post_author', $special_post_id);
				if($author_id){
					$created_by_name = get_the_author_meta( 'display_name' , $author_id ); 
				}

				//* Category Names
				$special_post_sel_cats = wp_get_post_terms($special_post_id,Wx_SPECIAL_POSTS_TAXONOMY_SLUG);
				
				$main_cats_names 	= [];
				$sub_cats_names		= [];
				foreach($special_post_sel_cats as $cat){
					if($cat->parent){
						$sub_cats_names[] = $cat->name;
					}else{
						$main_cats_names[] = $cat->name;
					}
				}

				$output[] = [
					'title'				=> 	get_the_title(),
					// 'description'		=> 	get_the_excerpt($special_post_id),
					'category'			=> 	implode(',',$main_cats_names),
					'sub_category'		=>	implode(',',$sub_cats_names),
					'created_on'		=> 	get_the_date($date_format),
					'created_by'		=>	$created_by_name,
					'modified_on'		=> 	get_the_modified_date($date_format),
					'modified_by'		=> 	$modified_by_name,
					'actions' 			=> 	$this->wx_get_datatable_action($special_post_id),
				];

			}
			wp_reset_postdata();
		}

		wp_send_json([
            'paged'	 						=>	$paged,
            'data' 							=> 	$output,
            'draw' 							=> 	(int)$draw,
            'recordsFiltered' 				=> 	(int)$found_post,
            'recordsTotal' 					=> 	(int)$found_post,
			'wx_special_post_cats' 			=>	$this->wx_get_special_posts_taxonomy_terms_datatable(),
			'wx_special_post_sub_cats'		=> 	$this->wx_get_special_posts_taxonomy_sub_terms_datatable(),
			'wx_special_post_bb_groups'		=>	$this->wx_get_special_posts_bb_groups_datatable(),
			'wx_special_post_authors'		=>	$this->wx_get_special_posts_authors_for_datatable(),
        ]);

	}
	/**
	 * Wx get Datatable Actions
	 * @since    1.1.0
	 */
	public function wx_get_datatable_action($special_post_id){
        ob_start();
		$link = get_the_permalink($special_post_id);
		?>
			<div class="d-flex">
				<div class="col">
					<a href="<?php echo $link; ?>" class="btn btn-info">
						<i class="fas fa-eye"></i>
					</a>
				</div>
				<div class="col">
					<a href="#" class="btn btn-primary wxEditSpecialPost" data-id="<?php echo $special_post_id; ?>">
						<i class="fas fa-edit"></i>
					</a>
				</div>
			</div>			
		<?php
		return ob_get_clean();
	}

	

	/**
     * Display Group's Special Posts
     */
	public function wx_special_post_of_group($args){
		$bb_group_id = $args['bb_group_id'] ?? 0;

		if ( is_file( Wx_SPECIAL_POSTS_DIR_PATH . 'views/group/wx-spcial-posts-tab.php' ) ) {
            ob_start();
            include Wx_SPECIAL_POSTS_DIR_PATH . 'views/group/wx-spcial-posts-tab.php';
            return ob_get_clean();
        }
	}

	/**
     * Override special post tpl
     */
	public function wx_override_special_post_template($template){ 
		$post_types = array(Wx_SPECIAL_POSTS_SLUG);

        if (  is_singular($post_types) && is_file( Wx_SPECIAL_POSTS_DIR_PATH . 'views/dashboard/wx-special-posts-single.php' ) ) {
            $template = Wx_SPECIAL_POSTS_DIR_PATH . 'views/dashboard/wx-special-posts-single.php';
        }

        return $template;
	}

	/**
     * Archive page ajax filter
     */
	public function wx_apply_special_posts_archive_page_filter(){
		$post_data = array();
        parse_str($_POST['formData'], $post_data);

		$category 		= 	$post_data['wx_special_post_cat_filter'] ?? 0;
		$sub_category 	= 	$post_data['wx_special_post_sub_cat_filter'] ?? 0;
		$bb_group 		= 	$post_data['wx_special_post_group'] ?? 0;
		$author_id 		= 	$post_data['wx_special_post_author'] ?? 0;


		$args = array(
            'nopaging'       =>   	true,
            'post_type'      => 	Wx_SPECIAL_POSTS_SLUG,
            'post_status'    => 	'publish',
            'order'          => 	'DESC',
            'orderby'        => 	'modified',
        );

		//* Taxonomy Filter 
		$args['tax_query'] = [
			'relation' => 'AND',
		]; 

		//* Meta Query
		$args['meta_query'] = [
			'relation' => 'AND',
		]; 


		//* Category Sub Category Filter 
		if ($category || $sub_category) {
			$term_ids = [];
			if($category){
				$term_ids[] = $category;
			}
			if($sub_category){
				$term_ids[] = $sub_category;
			}

			array_push($args['tax_query'],[
				'taxonomy' 	=> Wx_SPECIAL_POSTS_TAXONOMY_SLUG,
				'field' 	=> 'term_id',          
				'terms' 	=> $term_ids,
				'operator' 	=> 'IN',
			]);            
        }


		//* BB Group Filter  
		if($bb_group){
			array_push($args['meta_query'],array(
				'key'     => '_related_groups',
				'value'   => $bb_group,
				'compare' => '='
			));
		}

		//* Author Id
		if($author_id){
			$args['author'] = $author_id;
		} 

		$c_q = new \WP_Query($args);

		if ( is_file( Wx_SPECIAL_POSTS_DIR_PATH . 'views/dashboard/wx-special-posts-archive-ajax.php' ) ) {
			ob_start();
			include Wx_SPECIAL_POSTS_DIR_PATH . 'views/dashboard/wx-special-posts-archive-ajax.php';
			$html .= ob_get_clean();
		}

        wp_send_json_success([
        'html'  =>$html,
        ]);


	}
	

}