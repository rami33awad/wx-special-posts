<?php 
    $form_permissions = get_option('wx_special_posts_form_settings_options');
    
    $form_cat_add           =   $form_permissions['form_cat_add'] ?? [];
    $form_sub_cat_add       =   $form_permissions['form_sub_cat_add'] ?? [];
    $form_tag_add           =   $form_permissions['form_tag_add'] ?? [];
    $form_assign_group      =   $form_permissions['form_assign_group'] ?? [];
    $form_tag_group_members =   $form_permissions['form_tag_group_members'] ?? [];
    $form_tag_friends       =   $form_permissions['form_tag_friends'] ?? [];
    $form_tag_all_members   =   $form_permissions['form_tag_all_members'] ?? [];

    $current_user = wp_get_current_user();
    $current_user_roles = $current_user->roles;
 ?>
 
<?php
    $special_post_sel_cats = wp_get_post_terms($special_post->ID,Wx_SPECIAL_POSTS_TAXONOMY_SLUG);
    $sel_cats_ids = [];
    if( count($special_post_sel_cats) ){
        $sel_cats_ids = array_column($special_post_sel_cats,'term_id');
    }

    $special_post_sel_tags = wp_get_post_terms($special_post->ID,'post_tag');
    $sel_tags_ids = [];
    if( count($special_post_sel_tags) ){
        $sel_tags_ids = array_column($special_post_sel_tags,'term_id');
    }
?>

<form class="wx-special-posts-forms" id="wx-edit-special-post-form" method="POST" autocomplete="off">  
    <?php wp_nonce_field( 'wx_edit_special_post' ,'wx_edit_special_post_nonce_field' ); ?>
    <input type="hidden" name="wx_special_post_id" value="<?php echo $special_post->ID;?>">
    <div class="modal-header">
        <h3 class="modal-title" id="wx_title">Edit <?php echo Wx_SPECIAL_POSTS_NAME;?></h3>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        </button>
    </div>
    <div class="modal-body">
        <div class="container">
            <div class="add-special-post-wrap">
                    <!-- Fields Starts -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Title</label>
                                <input type="text" name="wx_special_post_title" class="form-control" value="<?php echo $special_post->post_title ?? ''; ?>">
                            </div>
                        </div>
                        <?php if( wx_check_user_roles_perms($current_user_roles,$form_cat_add) ) : ?>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Category</label>
                                    <select name="wx_special_post_category" class="form-control wx_special_post_category">
                                        <option value="">Select Category</option>
                                        <?php 
                                        $main_term_id = 0;
                                        foreach($this->wx_get_special_posts_taxonomy_terms() as $term ): ?>
                                            <?php 
                                                $cat_checked = ( in_array($term->term_id,$sel_cats_ids) )?"selected":''; 
                                                if(in_array($term->term_id,$sel_cats_ids)){
                                                    $main_term_id = $term->term_id;
                                                }
                                            ?>
                                            <option <?php echo $cat_checked; ?>  value="<?php echo $term->term_id; ?>"><?php echo $term->name; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if( wx_check_user_roles_perms($current_user_roles,$form_sub_cat_add) ) : ?>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Sub Category</label>
                                    <select name="wx_special_post_sub_category" class="form-control wx_special_post_sub_category">
                                        <option value="">Select Sub Category</option>
                                        <?php 
                                            $terms = get_terms(Wx_SPECIAL_POSTS_TAXONOMY_SLUG,array(
                                                'hide_empty' 	=>	false,
                                                'parent' 		=> 	$main_term_id,
                                                'orderby' 		=> 	'name',
                                                'order' 		=> 	'ASC',
                                            ));
                                            foreach($terms as $term){
                                                $cat_checked = ( in_array($term->term_id,$sel_cats_ids) )?"selected":'';
                                                echo  '<option '.$cat_checked.' value="'.$term->term_id.'">'.$term->name.'</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="row">  
                        <?php if( wx_check_user_roles_perms($current_user_roles,$form_tag_add) ) : ?>
                            <div class="col-md-12">
                                <div class="form-group" id="wx_special_post_tags_edit">
                                    <label for="">Tags</label>
                                    <select name="wx_special_post_tags[]" multiple="multiple" class="form-control wx_special_post_tags_edit">
                                        <?php foreach($this->wx_get_special_posts_taxonomy_tags_terms() as $tag ):
                                            $sel = ( in_array($tag->term_id,$sel_tags_ids) )?"selected":"";
                                            ?>
                                            <option <?php echo $sel; ?> value="<?php echo $tag->term_id; ?>"><?php echo $tag->name; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        <?php endif; ?>                        
                    </div>
                    <?php if( wx_check_user_roles_perms($current_user_roles,$form_tag_group_members) ) : ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Assign to group</label>
                                    <select name="wx_special_post_assigned_group" class="form-control wx_special_post_group">
                                        <option value="">Choose Group</option>
                                        <?php foreach($group_ids as $group_id): $group = groups_get_group($group_id);
                                            $sel = ( $wx_sp_assigned_group == $group_id )?"selected":"";
                                        ?>
                                            <option <?php echo $sel; ?> value="<?php echo $group_id; ?>"><?php echo $group->name; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" id="wx_special_post_group_members_edit">
                                    <label for="">Tag group members</label>
                                    <select name="wx_special_post_assigned_group_members[]" multiple="multiple" class="form-control wx_special_post_group_members_edit">                            
                                        <?php foreach($group_members as $member): 
                                            $sel = ( in_array($member->ID,$wx_sp_assigned_group_members) )?"selected":"";
                                            ?>
                                            <option <?php echo $sel; ?> value="<?php echo $member->ID;?>"><?php echo $member->data->display_name;?></option>
                                        <?php endforeach;?>
                                    </select>
                                </div>                
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="row">
                        <?php if( wx_check_user_roles_perms($current_user_roles,$form_tag_friends) ) : ?>
                            <div class="col-md-6">
                                <div class="form-group" id="wx_special_post_friends_edit">
                                    <label for="">Tag friends</label>
                                    <select name="wx_special_post_friends[]" multiple="multiple" class="form-control wx_special_post_friends_edit">
                                        <?php foreach( $user_friends as $user_friend): 
                                            $sel = ( in_array($user_friend->ID,$wx_sp_friends) )?"selected":"";
                                            ?>
                                            <option <?php echo $sel; ?> value="<?php echo $user_friend->ID; ?>"><?php echo $user_friend->data->display_name; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if( wx_check_user_roles_perms($current_user_roles,$form_tag_all_members) ) : ?>
                            <div class="col-md-6">
                                <div class="form-group" id="wx_special_post_all_group_members_edit">
                                    <label for="">Tag Members</label>
                                    <select name="wx_special_post_all_group_members[]" multiple="multiple" class="form-control wx_special_post_all_group_members_edit">
                                        <?php foreach($all_group_members as $group_member): 
                                            $sel = ( in_array($group_member->ID,$wx_sp_all_group_members) )?"selected":"";
                                            ?>
                                            <option <?php echo $sel; ?> value="<?php echo $group_member->ID; ?>"><?php echo $group_member->data->display_name;?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <?php 
                                echo do_shortcode("[wx-sp-flexible-content title='".Wx_SPECIAL_POSTS_NAME." Content' post_id='".$wx_special_post_id."']");
                            ?>
                        </div>
                    </div>
                    <!-- Fields Ends -->
            </div>
        </div>
    </div> 
    <div class="modal-footer">
        <div class="row">
            <div class="col-md-12">
                <div class="text-center mt-3">
                    <a href="#" class="btn btn-danger wxDelSpecialPost" data-post-type-name="<?php echo Wx_SPECIAL_POSTS_NAME; ?>"
                    data-id="<?php echo $wx_special_post_id; ?>">Delete</a>
                    <button type="submit" name="submit" value="save" class="btn btn-success wxAddPostSbmtBtn">Save</button>
                    <a class="btn btn-primary wxAddPostCancelBtn" data-type="add-form" data-bs-dismiss="modal">Close</a>
                </div>
            </div>
        </div>
        <div class="col-md-12 offset-2 text-center pt-2 wx_hide wxPostCreatedSuccess">
            <div class="alert alert-success" role="alert">
                <?php echo Wx_SPECIAL_POSTS_NAME;?> updated Successfully.
            </div>
        </div>
        <div class="col-md-12 offset-2 text-center pt-2 wx_hide wxPostCreatedError">
            <div class="alert alert-danger" role="alert">
                <?php echo Wx_SPECIAL_POSTS_NAME;?> update failed.
            </div>
        </div>
    </div> 
</form>