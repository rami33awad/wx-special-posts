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

<div class="modal  wxModals" id="wxAddNewSpecialPosts" tabindex="-1" aria-labelledby="wxAddNewSpecialPostsLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <form class="wx-special-posts-forms" id="wx-add-special-post-form" method="POST" autocomplete="off">  
            <?php wp_nonce_field( 'wx_create_special_post' ,'wx_create_special_post_nonce_field' ); ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="wx_title">Add <?php echo Wx_SPECIAL_POSTS_NAME;?></h3>
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
                                        <input type="text" name="wx_special_post_title" class="form-control">
                                    </div>
                                </div>
                                <?php if( wx_check_user_roles_perms($current_user_roles,$form_cat_add) ) : ?>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Category</label>
                                            <select name="wx_special_post_category" class="form-control wx_special_post_category">
                                                <option value="">Select Category</option>
                                                <?php foreach($this->wx_get_special_posts_taxonomy_terms() as $term ): ?>
                                                    <option value="<?php echo $term->term_id; ?>"><?php echo $term->name; ?></option>
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
                                            </select>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="row">   
                                <?php if( wx_check_user_roles_perms($current_user_roles,$form_tag_add) ) : ?>                             
                                    <div class="col-md-12">
                                        <div class="form-group" id="wx_special_post_tags_add">
                                            <label for="">Tags</label>
                                            <select name="wx_special_post_tags[]" multiple="multiple" class="form-control wx_special_post_tags">
                                                <?php foreach($this->wx_get_special_posts_taxonomy_tags_terms() as $tag ): ?>
                                                    <option value="<?php echo $tag->term_id; ?>"><?php echo $tag->name; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if( wx_check_user_roles_perms($current_user_roles,$form_assign_group) ) : ?> 
                                    
                                <?php endif; ?>
                            </div>
                            <?php if( wx_check_user_roles_perms($current_user_roles,$form_tag_group_members) ) : ?> 
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Assign to group</label>
                                            <select name="wx_special_post_assigned_group" class="form-control wx_special_post_group">
                                                <option value="">Choose Group</option>
                                                <?php foreach($group_ids as $group_id): $group = groups_get_group($group_id); ?>
                                                    <option value="<?php echo $group_id; ?>"><?php echo $group->name; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" id="wx_special_post_group_members">
                                            <label for="">Tag group members</label>
                                            <select name="wx_special_post_assigned_group_members[]" multiple="multiple" class="form-control wx_special_post_group_members">                            
                                            </select>
                                        </div>                
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="row">
                                <?php if( wx_check_user_roles_perms($current_user_roles,$form_tag_friends) ) : ?>
                                    <div class="col-md-6">
                                        <div class="form-group" id="wx_special_post_friends">
                                            <label for="">Tag friends</label>
                                            <select name="wx_special_post_friends[]" multiple="multiple" class="form-control wx_special_post_friends">
                                                <?php foreach( $user_friends as $user_friend): ?>
                                                    <option value="<?php echo $user_friend->ID; ?>"><?php echo $user_friend->data->display_name; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if( wx_check_user_roles_perms($current_user_roles,$form_tag_all_members) ) : ?>
                                    <div class="col-md-6">
                                        <div class="form-group" id="wx_special_post_all_group_members">
                                            <label for="">Tag Members</label>
                                            <select name="wx_special_post_all_group_members[]" multiple="multiple" class="form-control wx_special_post_all_group_members">
                                                <?php foreach($all_group_members as $group_member): ?>
                                                    <option value="<?php echo $group_member->ID?>"><?php echo $group_member->data->display_name;?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>                            
                            <div class="row">
                                <div class="col-md-12">
                                    <?php echo do_shortcode("[wx-sp-flexible-content title='".Wx_SPECIAL_POSTS_NAME." Content']"); ?>
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
                                <button type="submit" name="submit" value="save" class="btn btn-success wxAddPostSbmtBtn">Save</button>
                                <a class="btn btn-primary wxAddPostCancelBtn" data-type="add-form" data-bs-dismiss="modal">Close</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 offset-2 text-center pt-2 wx_hide wxPostCreatedSuccess">
                        <div class="alert alert-success" role="alert">
                            <?php echo Wx_SPECIAL_POSTS_NAME;?> Created Successfully.
                        </div>
                    </div>
                    <div class="col-md-12 offset-2 text-center pt-2 wx_hide wxPostCreatedError">
                        <div class="alert alert-danger" role="alert">
                            <?php echo Wx_SPECIAL_POSTS_NAME;?> Creation failed.
                        </div>
                    </div>
                </div> 
            </div>
        </form>
    </div>
</div>