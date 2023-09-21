<!-- Content Starts -->
<div id="course-dir-list" class="course-dir-list bs-dir-list">  
    <ul class="bb-card-list bb-course-items grid-view bb-grid " aria-live="assertive" aria-relevant="all">
        <?php 
            if( $c_q->have_posts() ){
                while($c_q->have_posts()){
                    $c_q->the_post();  

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
            }else{
                ?>
                    <div class="alert alert-warning" role="alert">
                        No <?php echo Wx_SPECIAL_POSTS_NAME; ?> are found.
                    </div>
                <?php
            }
        ?>
    </ul>
</div>
<!-- Content Ends -->