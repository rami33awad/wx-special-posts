<?php 
   $paragraph_id = "wx_tinymce_textarea".time();
?>
<div class="card">
   <div class="card-header">
      <label>Paragraph</label>
      <a class="btn btn-danger float-end removeFlexibleElm">
      <i class="fa-solid fa-trash-can"></i>
      </a>
   </div>
   <div class="card-body">
      <textarea required name="<?php echo $field_name; ?>" class="form-control" id="<?php echo $paragraph_id; ?>" cols="30" rows="10" ><?php echo $sp_flexible_content_item[$layout]; ?></textarea>
   </div>
</div>

<script type="text/javascript">
   jQuery(document).ready(function(){
      setTimeout(function(){
         init_tinymce("<?php echo $paragraph_id; ?>");
      },2000);
   });
</script>