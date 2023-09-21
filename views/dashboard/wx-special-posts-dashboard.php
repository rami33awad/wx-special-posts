<div class="wx-special-posts-dashboard">
   <div class="container">
      <div class="card p-5 add_data_outer">
         <div class="row justify-content-between mb-4">
            <div class="col-md-4">
               <button data-bs-toggle="modal" data-bs-target="#wxAddNewSpecialPosts" type="button" class="btn btn-primary px-5 ml-4 " style="border-radius: 20px;">ADD NEW</button>
            </div>
         </div>
         <div class="table_outer table-responsive">
            <?php 
               
            ?>
            <table id="WX_Special_Posts_Dashboard" class="table table-striped custom_dash_table" style="width:100%">
            </table>
         </div>
      </div>
   </div>
</div>

<?php echo do_shortcode('[wx-add-special-post]'); ?>

<div class="modal  wxModals" id="wxEditSpecialPosts" tabindex="-1" aria-labelledby="wxEditSpecialPostsLabel" aria-hidden="true">
   <div class="modal-dialog modal-xl">
      <div class="modal-content">
         <?php echo $this->wx_get_edit_form_loader_html();?>
      </div>
   </div>
</div>
   