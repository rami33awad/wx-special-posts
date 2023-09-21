<?php
namespace Wx_Special_Posts\Site\Traits;

trait Trait_Wx_Special_Posts_Common{

    /**
	 * Get Loader html
	 * @since    1.0.0
	 */
	public function wx_get_edit_form_loader_html(){
		ob_start();
		?>
			<div class="d-flex justify-content-center">
	            <div class="spinner-border text-info courseModalSpinner" role="status">
	              <span class="visually-hidden">Loading...</span>
	            </div>
	        </div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Get Support Datatable columns
	 * @since    1.0.0
	 */
	public function wx_get_special_posts_dashboard_cols(){
		return [
			[
				'data'			=>	'title',
				'name'			=> 	'Title',
				'title'			=> 	'Title',
				'sortable'		=>	true,
				'orderby_name'	=>	'title'
			],
			// [
			// 	'data'			=>	'description',
			// 	'name'			=> 	'Description',
			// 	'title'			=> 	'Description',
			// 	'sortable'		=>	false,
			// 	'orderby_name'	=>	''
			// ],
			[
				'data'			=>	'category',
				'name'			=> 	'category',
				'title'			=> 	'Category',
				'sortable'		=>	true,
				'orderby_name'	=>	''
			],
			[
				'data'			=>	'sub_category',
				'name'			=> 	'sub_category',
				'title'			=> 	'Sub Category',
				'sortable'		=>	true,
				'orderby_name'	=>	''
			],
			[
				'data'			=>	'created_on',
				'name'			=> 	'Created on',
				'title'			=> 	'Created on',
				'sortable'		=>	true,
				'orderby_name'	=>	'date'
			],
			[
				'data'			=>	'created_by',
				'name'			=> 	'Created By',
				'title'			=> 	'Created By',
				'sortable'		=>	false,
				'orderby_name'	=>	''
			],
			[
				'data'			=>	'modified_on',
				'name'			=> 	'Modified on',
				'title'			=> 	'Modified on',
				'sortable'		=>	false,
				'orderby_name'	=>	'modified'
			],
			[
				'data'			=>	'modified_by',
				'name'			=> 	'Modified by',
				'title'			=> 	'Modified by',
				'sortable'		=>	false,
				'orderby_name'	=>	''
			],
			[
				'data'			=>	'actions',
				'name'			=> 	'Actions',
				'title'			=> 	'Actions',
				'sortable'		=>	false,
				'orderby_name'	=>	''
			]
			
		]; 
	}

	/**
	 * Get Special Post Taxonomy Terms
	 * @since    1.0.0
	 */
	public function wx_get_special_posts_taxonomy_terms(){
		return get_terms(Wx_SPECIAL_POSTS_TAXONOMY_SLUG,array(
			'hide_empty' 	=>	false,
			'parent' 		=> 	0,
			'orderby' 		=> 	'name',
			'order' 		=> 	'ASC',
		));
	}

	/**
	 * Get Special Post Taxonomy Terms
	 * @since    1.0.0
	 */
	public function wx_get_special_posts_taxonomy_terms_datatable(){
		$terms =  $this->wx_get_special_posts_taxonomy_terms();
		$output = [];
		foreach($terms as $term){
			$output [] = [
				'id'	=>	$term->term_id,
				'name'	=>	$term->name,
			];
		}
		return $output;
	}


	/**
	 * Get Special Post Taxonomy Sub Terms
	 * @since    1.0.0
	 */
	public function wx_get_special_posts_taxonomy_sub_terms(){
		$output = [];
		$terms = get_terms(Wx_SPECIAL_POSTS_TAXONOMY_SLUG,array(
			'hide_empty' 	=>	false,
			'orderby' 		=> 	'name',
			'order' 		=> 	'ASC',
		));
		foreach($terms as $term){
			if($term->parent){
				$output[] = $term;
			}
		}
		return $output;
	}
	
	/**
	 * Get Special Post Taxonomy Sub Terms Datatable
	 * @since    1.0.0
	 */
	public function wx_get_special_posts_taxonomy_sub_terms_datatable(){
		$terms =  $this->wx_get_special_posts_taxonomy_sub_terms();
		$output = [];
		foreach($terms as $term){
			$output [] = [
				'id'	=>	$term->term_id,
				'name'	=>	$term->name,
			];
		}
		return $output;
	}

	/**
	 * Get Special Post Taxonomy Terms
	 * @since    1.0.0
	 */
	public function wx_get_special_posts_taxonomy_tags_terms(){
		$tag_terms =  get_terms('post_tag',array(
			'hide_empty' 	=>	false,
			'orderby' 		=> 	'name',
			'order' 		=> 	'ASC',
		));
		return $tag_terms;
	}

	/**
	 * Get User BP Group Ids
	 * @since    1.0.0
	 */
	public function wx_bp_get_user_group_ids($user_id){
		$groups = groups_get_user_groups($user_id);
		$group_ids = $groups['groups'] ?? [];
		return $group_ids;
	}

	/**
	 * Get BP Groups Users
	 * @since    1.0.0
	 */
	public function wx_bp_get_groups_users($group_ids,$user_id){
		global $table_prefix, $wpdb;
		$bp_groups_members = $table_prefix . 'bp_groups_members';

		if(!is_array($group_ids)){
			return [];
		}

		if( !count($group_ids) ){
			return [];
		}

		$group_str = implode(',',$group_ids);
		$current_user 	= wp_get_current_user();

		$sqlQuery = "SELECT GROUP_CONCAT(DISTINCT user_id) as member_ids FROM `{$bp_groups_members}` 
		WHERE `group_id` IN({$group_str})";
		$row = $wpdb->get_row($sqlQuery);

		if($row && $row->member_ids){
			$member_ids_arr = explode(',',$row->member_ids);

			
			$key = array_search($user_id,$member_ids_arr);
			
			if( $key === false ){
			}else{
				unset($member_ids_arr[$key]);
			}			

			if( !count($member_ids_arr) ){
				return [];
			}

			$user_query =  new \WP_User_Query([ 'include' => $member_ids_arr  ]);
			return $user_query->get_results();
		}
		return [];
	}

	/**
	 * Get all groups members
	 * @since    1.0.0
	 */
	public function wx_get_all_bb_groups_ids(){
		global $table_prefix, $wpdb;
		$bp_groups = $table_prefix . 'bp_groups';
		$row =  $wpdb->get_row("SELECT GROUP_CONCAT(id) as ids FROM `{$bp_groups}`");
		if($row && $row->ids){
			return explode(',',$row->ids);
		}
		return [];
	}

	/**
	 * Get BP User Friends
	 * @since    1.0.0
	 */
	public function wx_bp_get_friends(){
		$current_user 	= wp_get_current_user();
		$member_ids_arr = friends_get_friend_user_ids($current_user->ID);
		if( count($member_ids_arr) ){
			$user_query =  new \WP_User_Query([ 'include' => $member_ids_arr  ]);
			return $user_query->get_results();
		}
		return [];
	}

	/**
	 * Get All BB Groups
	 * @since    1.0.0
	 */
	public function wx_get_special_posts_bb_groups(){
		global $table_prefix, $wpdb;
		$bp_groups = $table_prefix . 'bp_groups';
		return $wpdb->get_results("SELECT * FROM `{$bp_groups}`");
	}
	/**
	 * Get All BB Groups For Datatable
	 * @since    1.0.0
	 */
	public function wx_get_special_posts_bb_groups_datatable(){
		$output = [];
		$bb_groups = $this->wx_get_special_posts_bb_groups();
		foreach($bb_groups as $bb_group){
			$output[] = [
				'id'	=>	$bb_group->id,
				'name'	=>	$bb_group->name,
			];
		}
		return $output;
	}

	/**
	 * Get ALL Special Posts Author Ids
	 * @since    1.0.0
	 */
	public function wx_get_special_posts_author_ids(){
		global $table_prefix, $wpdb;
		$post_type_slug = Wx_SPECIAL_POSTS_SLUG;
		$sqlQuery="SELECT GROUP_CONCAT(DISTINCT `post_author`) as author_ids FROM `wp_posts` WHERE `post_type` = '{$post_type_slug}' AND `post_status` = 'publish'";
		$row = $wpdb->get_row($sqlQuery);
		if($row && $row->author_ids){
			return explode(',',$row->author_ids);
		}
		return [];
	}

	/**
	 * Get Authors For  Datatable
	 * @since    1.0.0
	 */
	public function wx_get_special_posts_authors_for_datatable(){
		$output = [];
		$user_ids = $this->wx_get_special_posts_author_ids();
		if( !count($user_ids) ){
			return $output;
		}
		$users = get_users([ 'include' => $user_ids ]);
		foreach($users as $user){
			$output[] = [
				'id'	=>	$user->ID,
				'name'	=>	$user->display_name,
			];
		}
		return $output;
	}

}