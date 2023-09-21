<?php 
    
    $cats = get_terms(Wx_SPECIAL_POSTS_TAXONOMY_SLUG,array(
        'hide_empty' 	=>	false,
        'orderby' 		=> 	'name',
        'order' 		=> 	'ASC',
    ));

    global $table_prefix, $wpdb;
    $bp_groups = $table_prefix . 'bp_groups';
    $bb_groups =  $wpdb->get_results("SELECT * FROM `{$bp_groups}`");

    function wx_get_special_posts_author_ids(){
		global $table_prefix, $wpdb;
		$post_type_slug = Wx_SPECIAL_POSTS_SLUG;
		$sqlQuery="SELECT GROUP_CONCAT(DISTINCT `post_author`) as author_ids FROM `wp_posts` WHERE `post_type` = '{$post_type_slug}' AND `post_status` = 'publish'";
		$row = $wpdb->get_row($sqlQuery);
		if($row && $row->author_ids){
			return explode(',',$row->author_ids);
		}
		return [];
	}

    function wx_get_special_posts_authors_for_datatable(){
		$output = [];
		$user_ids = wx_get_special_posts_author_ids();
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


    get_header(); 
?>
<div class="row bb-courses-directory mt-3">
    <!-- nav bar -->
    <div class="bb-secondary-list-tabs flex align-items-center" id="subnav" aria-label="Members directory secondary navigation" role="navigation">
        <input type="hidden" id="course-order" name="order" value="desc">
        <form action="" id="wx-special-posts-filter-form">
            <div class="sfwd-courses-filters flex push-left">
                <div class="select-wrap">
                    <select  name="wx_special_post_cat_filter" class="wx_special_post_cat_filter">
                        <option value="">Select Category</option>
                        <?php foreach($cats as $cat): if( $cat->parent == 0): ?>
                            <option value="<?php echo $cat->term_id; ?>"><?php echo $cat->name; ?></option>
                        <?php endif; endforeach; ?>
                    </select>
                </div> 
                <div class="select-wrap">
                    <select  name="wx_special_post_sub_cat_filter" class="wx_special_post_sub_cat_filter">
                        <option value="">Select Sub Category</option>
                        <?php foreach($cats as $cat): if( $cat->parent ): ?>
                            <option value="<?php echo $cat->term_id; ?>"><?php echo $cat->name; ?></option>
                        <?php endif; endforeach; ?>
                    </select>
                </div>
                <div class="select-wrap">
                    <select  name="wx_special_post_group" class="wx_special_post_group">
                        <option value="">Select Group</option>
                        <?php foreach($bb_groups as $bb_group): ?>
                            <option value="<?php echo $bb_group->id; ?>"><?php echo $bb_group->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="select-wrap">
                    <select  name="wx_special_post_author" class="wx_special_post_author">
                        <option value="">Select Author</option>
                        <?php foreach(wx_get_special_posts_authors_for_datatable() as $author): ?>
                            <option value="<?php echo $author['id']; ?>"><?php echo $author['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>            
                <div class="select-wrap">
                    <button class="btn btn-primary wxAddPostSbmtBtn">Apply Filter</button>
                </div>  
            </div>
        </form>
        <div class="grid-filters push-right" data-view="ld-course">
            <a href="#" class="layout-view layout-view-course layout-grid-view bp-tooltip" data-view="grid" data-bp-tooltip-pos="up" data-bp-tooltip="Grid View">
            <i class="dashicons dashicons-screenoptions" aria-hidden="true"></i>
            </a>
            <a href="#" class="layout-view layout-view-course layout-list-view bp-tooltip active" data-view="list" data-bp-tooltip-pos="up" data-bp-tooltip="List View">
            <i class="dashicons dashicons-menu" aria-hidden="true"></i>
            </a>
        </div>
    </div>
    
    <!-- nav bar  -->
    <div class="grid-view bb-grid wx-special-posts-page-container">
        <!-- Content Starts -->
        <div id="course-dir-list" class="course-dir-list bs-dir-list">  
            <ul class="bb-card-list bb-course-items grid-view bb-grid " aria-live="assertive" aria-relevant="all">
                <?php 
                    if( have_posts() ){
                        while(have_posts()){
                            the_post();  

                                $created_by_name = $created_by_profile_link = $created_by_profile_img = '';
                                $author_id = get_post_field ('post_author',get_the_ID());
                                if($author_id){
                                    $created_by_name = get_the_author_meta( 'display_name' , $author_id );
                                    $created_by_profile_link = bp_core_get_userlink( $author_id ,false,true);  
                                    $created_by_profile_img = esc_url( get_avatar_url( $author_id ,['size' => '25']) );
                                }                  
                            ?>
                                <li class="bb-course-item-wrap">
                                    <div class="bb-cover-list-item ">
                                        <div class="bb-course-cover">
                                            <a title="<?php the_title(); ?>" href="<?php the_permalink(); ?>" class="bb-cover-wrap">
                                                <div class="ld-status ld-status-progress ld-primary-background">View</div>
                                            </a>
                                        </div>
                                        <div class="bb-card-course-details bb-card-course-details--hasAccess">
                                            <!-- <div class="course-lesson-count">0 Lessons</div> -->
                                            <h2 class="bb-course-title ">
                                                <a title="<?php the_title(); ?>" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                            </h2>
                                            <div class="bb-course-meta">
                                                <a class="item-avatar" href="<?php echo $created_by_profile_link;?>">
                                                <img alt="" src="<?php echo $created_by_profile_img; ?>" srcset="http://127.0.0.1/wiliex-lms/wp-content/plugins/buddyboss-platform/bp-core/images/profile-avatar-buddyboss-50.png 2x" class="avatar avatar-80 photo" height="80" width="80" loading="lazy">	</a>
                                                <strong>
                                                <a href="<?php echo $created_by_profile_link;?>"><?php echo $created_by_name; ?></a>
                                                </strong>
                                            </div>
                                            <div class="course-progress-wrap">
                                                <div class="ld-progress
                                                ld-progress-inline">
                                                <div class="ld-progress-heading">
                                                </div>
                                                <div class="ld-progress-bar">
                                                    <div class="ld-progress-bar-percentage ld-secondary-background" style="width:0%"></div>
                                                </div>
                                                <div class="ld-progress-stats">
                                                    <div class="ld-progress-percentage ld-secondary-color course-completion-rate">
                                                        <!-- 0% Complete				 -->
                                                    </div>
                                                    <div class="ld-progress-steps">
                                                        <!-- 0/0 Steps				 -->
                                                    </div>
                                                </div>
                                                <!--/.ld-progress-stats-->
                                                </div>
                                                <!--/.ld-progress-->
                                            </div>
                                            <div class="bb-course-excerpt">
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            <?php
                        }
                    }
                ?>
            </ul>
        </div>
        <!-- Content Ends -->
    </div>
</div>
<?php get_footer(); ?>