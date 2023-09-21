<?php get_header(); 
$wx_special_post_id = get_the_ID();
$special_post_sel_cats = wp_get_post_terms($wx_special_post_id,Wx_SPECIAL_POSTS_TAXONOMY_SLUG);
$wx_special_post_friends			=   get_post_meta($wx_special_post_id,'wx_special_post_friends',true);
$wx_special_post_all_group_members	=	get_post_meta($wx_special_post_id,'wx_special_post_all_group_members',true);
$related_group						=	get_post_meta($wx_special_post_id,'wx_special_post_assigned_group',true);
$wx_sp_assigned_group_members 		= 	get_post_meta($wx_special_post_id,'wx_special_post_assigned_group_members',true);


$wx_special_post_friends            = ( is_array($wx_special_post_friends) )?$wx_special_post_friends:[];
$wx_special_post_all_group_members  = ( is_array($wx_special_post_all_group_members) )?$wx_special_post_all_group_members:[];
$wx_special_post_group_members      = ( is_array($wx_special_post_group_members) )?$wx_special_post_group_members:[];

$buddyboss_theme_options = get_option('buddyboss_theme_options');

$page_title = Wx_SPECIAL_POSTS_NAME.' Dashboard';
$is_page_exits = wx_get_page_by_title($page_title);
$sp_link = '#';
if($sp_link){
    $sp_link = get_page_link($is_page_exits->ID);
}
?>

<div class="wx-special-post-single-page">
    <div class="row">
        <div class="col-md-12">
            <div class="" style="background: <?php echo $buddyboss_theme_options['accent_color'] ?? ''; ?>">
                <div class="bb-course-banner-info container">                    
                    <h3 class="p-0 m-0  text-light">
                        <a href="<?php echo $sp_link; ?>" class="btn btn-light float-left">
                            <i class="fas fa-arrow-left"></i>
                        </a>

                        <button class="btn btn-light dataTblActionBtn float-end"  
                        data-toggle="modal" style="color:<?php echo $buddyboss_theme_options['accent_color'] ?? ''; ?>"
                        data-page-action="page_reload"
                        data-target="#wxEditSpecialPosts" data-id="<?php echo $wx_special_post_id; ?>">Edit <?php echo Wx_SPECIAL_POSTS_NAME; ?></button> 
                    </h3>    
                </div> 
            </div>
            <div class="modal fade wxModals wxCourseModals" id="wxEditSpecialPosts" aria-labelledby="modalTemplate" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="add_course_wrap">
                                <div class="container">
                                    <!-- Dynamic content starts-->
                                        <div class="d-flex justify-content-center">
                                            <div class="spinner-border text-info courseModalSpinner" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    <!-- Dynamic content ends-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <header class="entry-header"> <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?> </header>
        </div>
        <div class="col-md-12">
            <div class="post-meta-wrapper-main">
                <div class="post-meta-wrapper">

                    <?php if( count($wx_special_post_friends) ): ?>
                        <div class="cat-links">
                            <i class="bb-icon-l bb-icon-users"></i>
                            <?php _e( 'Tagged Friends: ', 'buddyboss-theme' ); ?>
                            <div class="group-footer-wrap">
                                <div class="list-wrap">
                                    <div class="item">
                                        <div class="group-members-wrap">
                                            <?php foreach($wx_special_post_friends as $user_id): $user = new WP_User($user_id); ?>
                                                <span class="bs-group-members">
                                                    <span class="bs-group-member" data-bp-tooltip-pos="up-left" data-bp-tooltip="<?php echo $user->data->display_name; ?>">
                                                        <a href="<?php echo bp_core_get_userlink( $user->ID ,false,true); ?>">
                                                            <img src="<?php echo esc_url( get_avatar_url( $user->ID ,['size' => '25']) ); ?>" alt="<?php echo $user->data->display_name; ?>" class="round">
                                                        </a>
                                                    </span>                                            
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>                               
                            </div>   
                        </div>
                    <?php endif; ?>

                    <?php if( count($wx_special_post_all_group_members) ): ?>
                        <div class="cat-links">
                            <i class="bb-icon-l bb-icon-users"></i>
                            <?php _e( 'Tagged Members: ', 'buddyboss-theme' ); ?>
                            <div class="group-footer-wrap">
                                <div class="list-wrap">
                                    <div class="item">
                                        <div class="group-members-wrap">
                                            <?php foreach($wx_special_post_all_group_members as $user_id): $user = new WP_User($user_id); ?>
                                                <span class="bs-group-members">
                                                    <span class="bs-group-member" data-bp-tooltip-pos="up-left" data-bp-tooltip="<?php echo $user->data->display_name; ?>">
                                                        <a href="<?php echo bp_core_get_userlink( $user->ID ,false,true); ?>">
                                                            <img src="<?php echo esc_url( get_avatar_url( $user->ID ,['size' => '25']) ); ?>" alt="<?php echo $user->data->display_name; ?>" class="round">
                                                        </a>
                                                    </span>                                            
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>                               
                            </div>   
                        </div>
                    <?php endif; ?>

                    <?php if($related_group): 
                            $bb_group = groups_get_group($related_group);
                            global $groups_template;
                            $r = bp_nouveau_avatar_args();
                            $avatar = bp_get_group_avatar_url($bb_group);
                            
                        ?>
                        <div class="cat-links">
                            <i class="bb-icon-l bb-icon-brand-paidmembershipspro"></i>
                            <?php _e( 'Assigned Group: ', 'buddyboss-theme' ); ?>
                            <div class="group-footer-wrap">
                                <div class="list-wrap">
                                    <div class="item">
                                        <div class="group-members-wrap">
                                            <span class="bs-group-members">
                                                <span class="bs-group-member" data-bp-tooltip-pos="up-left" data-bp-tooltip="<?php echo bp_get_group_name($bb_group); ?>">
                                                    <a href="<?php echo bp_get_group_permalink($bb_group); ?>">
                                                        <img src="<?php echo $avatar; ?>" alt="<?php echo bp_get_group_name($bb_group); ?>" class="round">
                                                    </a>
                                                </span>                                            
                                            </span>
                                        </div>
                                    </div>
                                </div>                               
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if( count($wx_sp_assigned_group_members) ): ?>
                        <div class="cat-links">
                            <i class="bb-icon-l bb-icon-users"></i>
                            <?php _e( 'Tagged Group Members: ', 'buddyboss-theme' ); ?>
                            <div class="group-footer-wrap">
                                <div class="list-wrap">
                                    <div class="item">
                                        <div class="group-members-wrap">
                                            <?php foreach($wx_sp_assigned_group_members as $user_id): $user = new WP_User($user_id); ?>
                                                <span class="bs-group-members">
                                                    <span class="bs-group-member" data-bp-tooltip-pos="up-left" data-bp-tooltip="<?php echo $user->data->display_name; ?>">
                                                        <a href="<?php echo bp_core_get_userlink( $user->ID ,false,true); ?>">
                                                            <img src="<?php echo esc_url( get_avatar_url( $user->ID ,['size' => '25']) ); ?>" alt="<?php echo $user->data->display_name; ?>" class="round">
                                                        </a>
                                                    </span>                                            
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>                               
                            </div>   
                        </div>
                    <?php endif; ?>
                    <?php if ( count($special_post_sel_cats) ) : ?>
                        <div class="cat-links">
                            <i class="bb-icon-l bb-icon-folder"></i>
                            <?php _e( Wx_SPECIAL_POSTS_TAXONOMY_NAME.': ', 'buddyboss-theme' ); ?>
                            <span>
                                <?php 
                                    $cats_links = [];
                                    foreach($special_post_sel_cats as $term){
                                        $taxonomy = Wx_SPECIAL_POSTS_TAXONOMY_SLUG;
                                        $link = get_term_link( $term, $taxonomy );
                                        $cats_links[] = "<a href='{$link}'>{$term->name}</a>";
                                    }
                                    echo implode(',',$cats_links);
                                ?>
                            </span>
                        </div>
                    <?php endif;

                    if ( has_tag() ) : ?>
                        <div class="tag-links">
                            <i class="bb-icon-l bb-icon-tags"></i>
                            <?php _e( 'Tags: ', 'buddyboss-theme' ); ?>
                            <?php the_tags( '<span>', __( ', ', 'buddyboss-theme' ), '</span>' ); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php echo do_shortcode('[wx-sp-flexible-content-output post_id="'.$wx_special_post_id.'" ]'); ?>
        </div>
    </div>
    <div class="row">
        <!-- Comments Starts -->
        <?php 
            if( have_posts() ){
                while( have_posts() ){
                    the_post();
                    comments_template();
                }
            }
        ?>
        <!-- Comments Ends -->
    </div>
</div>

<?php get_footer(); ?>