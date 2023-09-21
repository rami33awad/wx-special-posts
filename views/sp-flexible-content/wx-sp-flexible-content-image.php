<?php 
   $image_thumbnail = '';
   $image_id = $sp_flexible_content_item[$layout];
   if( $image_id ){
      $image = wp_get_attachment_image_src($image_id,'thumbnail');
      if($image){
         $image_thumbnail = $image[0] ?? '';
      }
   }
?>

<div class="card">
   <div class="card-header">
      <label>Image</label>
      <button class="btn btn-danger float-end removeFlexibleElm">
      <i class="fa-solid fa-trash-can"></i>
      </button>
   </div>
   <div class="card-body row d-flex">
      <div class="form-group sp_flexible_content_add_image">
         <a <?php if($image_thumbnail){ echo "style='display:none;'";}?> class="btn btn-primary">Add Image</a>
         <img src="<?php echo $image_thumbnail; ?>" alt="" class="wx_hide" <?php if($image_thumbnail){ echo "style='display:block;'";}?> >
         <input  type="hidden" name="<?php echo $field_name; ?>" value="<?php echo $sp_flexible_content_item[$layout]; ?>">
      </div>
   </div>
</div>