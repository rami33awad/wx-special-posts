<?php 
   
   $linkInputs = "sp_flexible_content[".$key."][url][link]";
   $textInputs = "sp_flexible_content[".$key."][url][text]";
   $actionInputs = "sp_flexible_content[".$key."][url][action]";

   $data = $sp_flexible_content_item[$layout];

   $link = $data['link'] ?? '';
   $text = $data['text'] ?? '';
   $action = $data['action'] ?? '';

?>
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
            <input required class="form-control" type="text" name="<?php echo $linkInputs; ?>" value="<?php echo $link;?>">
         </div>
      </div>
      <div class="col">
         <div class="form-group">
            <label for="">Link Text</label>
            <input required class="form-control" type="text" name="<?php echo $textInputs; ?>" value="<?php echo $text;?>">
         </div>
      </div>
      <div class="col-12">
         <div class="form-check pl-5">
            <input <?php if( $action == 1){ echo 'checked="checked'; } ?> required class="form-check-input" type="checkbox" value="1" name="<?php echo $actionInputs; ?>">
            <label class="form-check-label" for="">
            Open link in a new tab
            </label>
         </div>
      </div>
   </div>
</div>