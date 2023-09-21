<div class="card">
	<div class="card-header">
		<label>Photo Gallery</label>
		<a class="btn btn-danger float-end removeFlexibleElm">
			<i class="fa-solid fa-trash-can"></i>
		</a>
	</div>
	<div class="card-body d-flex">
		<div class="row">
			<?php 
				$photo_gallery = $sp_flexible_content_item[$layout] ?? [];	
				foreach( $photo_gallery as $photo_gallery_item )
				{
					if( $photo_gallery_item )
					{
						$image = wp_get_attachment_image_src($photo_gallery_item,'thumbnail');
						if( $image[0] )
						{
							$gallery_input_field = 'sp_flexible_content['.$key.'][photo_gallery][]';
							?>
								<div class="col-md-4">								
									<div class="gallery-attachment p-1" data-id="1">
										<input type="hidden" name="<?php echo $gallery_input_field; ?>" value="<?php echo $photo_gallery_item; ?>">
										<div class="margin">
											<div class="thumbnail">
												<img src="<?php echo $image[0]; ?>" alt="" title="">
											</div>
										</div>
										<div class="actions">
											<a class="" href="#" title="Remove" data-id="1"></a>
										</div>
									</div>
								</div>
								<?php
						}
					}
				}
			?>
		</div>
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