<?php 

$args = array(
    'nopaging'       =>     true,  
    'post_type'      =>     Wx_SPECIAL_POSTS_SLUG,
    'post_status'    =>     'publish',
    'order'          =>     'DESC',
    'orderby'        =>     'modified',
    'meta_query'     =>     [
        [
            'key'     => 'wx_special_post_assigned_group',
            'value'   => $bb_group_id,
            'compare' => '=',
        ]
    ] 
);

$query = new \WP_Query($args); 

?>
<div class="row bb-courses-directory mt-3">
    <div class="bb-secondary-list-tabs flex align-items-center" id="subnav" aria-label="Members directory secondary navigation" role="navigation">
        <div class="grid-filters float-end" data-view="ld-course">
            <a href="#" class="layout-view layout-view-course layout-grid-view bp-tooltip" data-view="grid" data-bp-tooltip-pos="up" data-bp-tooltip="Grid View">
            <i class="dashicons dashicons-screenoptions" aria-hidden="true"></i>
            </a>
            <a href="#" class="layout-view layout-view-course layout-list-view bp-tooltip active" data-view="list" data-bp-tooltip-pos="up" data-bp-tooltip="List View">
            <i class="dashicons dashicons-menu" aria-hidden="true"></i>
            </a>
        </div>
    </div>
    <div class="grid-view bb-grid wx-special-courses-page-container">
        <div id="course-dir-list" class="course-dir-list bs-dir-list">  
            <ul class="bb-card-list bb-course-items grid-view bb-grid " aria-live="assertive" aria-relevant="all">
                <?php
                    if($query->have_posts()){
                        while($query->have_posts()){
                            $query->the_post(); 
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
                        }wp_reset_postdata();
                    }
                ?>
            </ul>
        </div>
    </div>
</div>
