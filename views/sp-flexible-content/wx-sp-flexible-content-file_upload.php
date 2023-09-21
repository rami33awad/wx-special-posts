<?php 

   $file_id = $sp_flexible_content_item[$layout];
   $file_url = wp_get_attachment_url($file_id) ?? '';
   $attachment_title = get_the_title($file_id);
?>

<div class="card file-upload-sp-flexible-content">
   <div class="card-header">
      <label>File Upload</label>
      <button class="btn btn-danger float-end removeFlexibleElm">
      <i class="fa-solid fa-trash-can"></i>
      </button>
   </div>
   <div class="card-body row">
      <div class="form-group sp_flexible_content_upload_file">
         <a <?php if($file_url){ echo 'style="display:none;"';} ?> class="btn btn-primary">Add File</a>
         <div class="file col-2 wx_hide" <?php if($file_url){ echo 'style="display:block;"';} ?>>
            <div class="file-icon">
               <img src="<?php echo Wx_SPECIAL_POSTS_DIR_URL.'images/document.png'; ?>" alt="">
               <input type="hidden" name="<?php echo $field_name; ?>" value="<?php echo $file_id;?>">
            </div>
            <div class="file-action">
               <code><?php echo $attachment_title; ?></code>
            </div>
         </div>
      </div>
   </div>
</div>