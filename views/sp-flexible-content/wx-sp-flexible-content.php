<?php 
   $form_permissions = get_option('wx_special_posts_form_settings_options');
  
   $rep_title_heading   =   $form_permissions['rep_title_heading'] ?? [];
   $rep_paragraph       =   $form_permissions['rep_paragraph'] ?? [];
   $rep_image           =   $form_permissions['rep_image'] ?? [];
   $rep_photo_gallery   =   $form_permissions['rep_photo_gallery'] ?? [];
   $rep_embed_video     =   $form_permissions['rep_embed_video'] ?? [];
   $rep_file_upload     =   $form_permissions['rep_file_upload'] ?? [];
   $rep_url_link        =   $form_permissions['rep_url_link'] ?? [];

   $current_user = wp_get_current_user();
   $current_user_roles = $current_user->roles;

?>
<!-- Flexible Content Starts -->
<div class="sp_flexible_content_container_wrap">
   <h3 class="sub_heading"><?php echo $wx_sp_flexible_content_section_title; ?></h3>
   <div class="wx_sp_flexible_content_wrap wx_sp_flexible_content_sortable" id="">
      <?php
         if( is_array($wx_sp_flexible_content) ){
            foreach( $wx_sp_flexible_content as $key=>$sp_flexible_content_item){
               $layout = key($sp_flexible_content_item);
               $field_name = "sp_flexible_content[".$key."][".$layout."]";

               if ( is_file( Wx_SPECIAL_POSTS_DIR_PATH . 'views/sp-flexible-content/wx-sp-flexible-content-'.$layout.'.php' ) ) {
                     include Wx_SPECIAL_POSTS_DIR_PATH . 'views/sp-flexible-content/wx-sp-flexible-content-'.$layout.'.php';
               }               
            }
         }          
      ?>
   </div>
   <div class="text-center d-flex sp_flexible_content_options_wrap mt-5">
      <div class="col">
         <label for="sp_flexible_content_options" class="btn btn-primary wx_add_sp_flexible_content">Add Content</label>
      </div>
      <div class="col">
         <div class="wx_add_sp_flexible_content_options wx_hide">
            <div class="input-group">
               <select class="form-control" id="sp_flexible_content_options" name="wx_add_sp_flexible_content_options">
                  <option value="">Select content type</option>

                  <?php if( wx_check_user_roles_perms($current_user_roles,$rep_title_heading) ) : ?>
                     <option value="title_heading">Title Heading</option>
                  <?php endif; ?>

                  <?php if( wx_check_user_roles_perms($current_user_roles,$rep_paragraph) ) : ?>
                     <option value="paragraph">Paragraph</option>
                  <?php endif; ?>

                  <?php if( wx_check_user_roles_perms($current_user_roles,$rep_image) ) : ?>
                     <option value="image">Image</option>
                  <?php endif; ?>

                  <?php if( wx_check_user_roles_perms($current_user_roles,$rep_photo_gallery) ) : ?>
                     <option value="photo_gallery">Photo Gallery</option>
                  <?php endif; ?>

                  <?php if( wx_check_user_roles_perms($current_user_roles,$rep_embed_video) ) : ?>
                     <option value="embed_video">Embed Video</option>
                  <?php endif; ?>

                  <?php if( wx_check_user_roles_perms($current_user_roles,$rep_file_upload) ) : ?>
                     <option value="file_upload">File Upload</option>
                  <?php endif; ?>

                  <?php if( wx_check_user_roles_perms($current_user_roles,$rep_url_link) ) : ?>
                     <option value="url_link">Link</option>
                  <?php endif; ?>
                  
               </select>
            </div>
         </div>
      </div>
   </div>
   <section class="d-none sp-flexible-title-heading-clone">
      <div class="card">
         <div class="card-header">
            <label>Title Heading</label>
            <a class="btn btn-danger float-end removeFlexibleElm">
            <i class="fa-solid fa-trash-can"></i>
            </a>
         </div>
         <div class="card-body">
            <input required disabled type="text" class="form-control" name="">
         </div>
      </div>
   </section>
   <section class="d-none sp-flexible-paragraph-clone">
      <div class="card">
         <div class="card-header">
            <label>Paragraph</label>
            <a class="btn btn-danger float-end removeFlexibleElm">
            <i class="fa-solid fa-trash-can"></i>
            </a>
         </div>
         <div class="card-body">
            <textarea disabled name="" class="form-control" id="wx_tinymce_textarea_dynamic_id" cols="30" rows="10" ></textarea>
         </div>
      </div>
   </section>
   <section class="d-none sp-flexible-image-clone">
      <div class="card">
         <div class="card-header">
            <label>Image</label>
            <button class="btn btn-danger float-end removeFlexibleElm">
            <i class="fa-solid fa-trash-can"></i>
            </button>
         </div>
         <div class="card-body row d-flex">
            <div class="form-group sp_flexible_content_add_image">
               <a class="btn btn-primary">Add Image</a>
               <img src="" alt="" class="wx_hide" >
               <input  type="hidden" name="" value="">
            </div>
         </div>
      </div>
   </section>
   <section class="d-none sp-flexible-photo-gallery-clone">
      <div class="card">
         <div class="card-header">
            <label>Photo Gallery</label>
            <a class="btn btn-danger float-end removeFlexibleElm">
            <i class="fa-solid fa-trash-can"></i>
            </a>
         </div>
         <div class="card-body d-flex">
            <div class="row"></div>
         </div>
         <div class="card-footer">
            <a class="btn btn-primary add_photo_gallery_images">Add Images</a>                          
         </div>
         <section class="wx_hide img_clone">
            <div class="col-md-4">
               <div class="gallery-attachment p-1" data-id="1">
                  <input type="hidden" name="" value="">
                  <div class="margin">
                     <div class="thumbnail">
                        <img src="" alt="" title="">
                     </div>
                  </div>
                  <div class="actions">
                     <a class="" href="#" title="Remove" data-id="1"></a>
                  </div>
               </div>
            </div>
         </section>
      </div>
   </section>
   <section class="d-none sp-flexible-embed-video-clone">
      <div class="card">
         <div class="card-header">
            <label>Embed Video Url</label>
            <button class="btn btn-danger float-end removeFlexibleElm">
            <i class="fa-solid fa-trash-can"></i>
            </button>
         </div>
         <div class="card-body row d-flex">
            <div class="form-group">
               <input required disabled type="text" class="form-control" name="" value="">
            </div>
         </div>
      </div>
   </section>
   <section class="d-none sp-flexible-file-upload-clone">
      <div class="card file-upload-sp-flexible-content">
         <div class="card-header">
            <label>File Upload</label>
            <button class="btn btn-danger float-end removeFlexibleElm">
            <i class="fa-solid fa-trash-can"></i>
            </button>
         </div>
         <div class="card-body row">
            <div class="form-group sp_flexible_content_upload_file">
               <a class="btn btn-primary">Add File</a>
               <div class="file col-2 wx_hide">
                  <div class="file-icon">
                     <img src="<?php echo Wx_SPECIAL_POSTS_DIR_URL.'images/document.png'; ?>" alt="">
                     <input type="hidden" name="" value="">
                  </div>
                  <div class="file-action">
                     <code>File Name</code>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
   <section class="d-none sp-flexible-url-link-clone">
      <div class="card">
         <div class="card-header">
            <label>Link</label>
            <button class="btn btn-danger float-end removeFlexibleElm">
            <i class="fa-solid fa-trash-can"></i>
            </button>
         </div>
         <div class="card-body row d-flex">
            <div class="col">
               <div class="form-group">
                  <label for="">Url</label>
                  <input required disabled class="form-control" type="text" data-name-link="">
               </div>
            </div>
            <div class="col">
               <div class="form-group">
                  <label for="">Link Text</label>
                  <input required disabled class="form-control" type="text" data-name-text="">
               </div>
            </div>
            <div class="col-12">
               <div class="form-check pl-5">
                  <input required disabled class="form-check-input" type="checkbox" value="1" data-name-action="">
                  <label class="form-check-label" for="">
                  Open link in a new tab
                  </label>
               </div>
            </div>
         </div>
      </div>
   </section>
</div>
<!-- Flexible Content Ends -->