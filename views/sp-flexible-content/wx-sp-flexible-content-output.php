<div class="wx_sp_flexible_content_wrap_site wx_sp_flexible_content_output">

	<?php foreach($wx_sp_flexible_content as $key=>$sp_flexible_content_item) : ?>
		<?php $layout = key($sp_flexible_content_item); ?>

		<?php if( $layout == "title_heading") : ?>
			<div class="wx_sp_flexible_content_heading_section">
				<div class="row">
					<div class="col-md-12">
						<h2><?php echo $sp_flexible_content_item[$layout]; ?></h2>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<?php if( $layout == "paragraph") : ?>
			<div class="wx_sp_flexible_content_paragraph_section">
				<div class="row">
					<div class="col-md-12">
						<?php echo $sp_flexible_content_item[$layout]; ?>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<?php if( $layout == "image") : ?>
			<div class="wx_sp_flexible_content_image_section">
				<div class="row">
					<div class="col-md-12">
						<?php 
							$image_id = $sp_flexible_content_item[$layout];
							if( $image_id ){
								$image = wp_get_attachment_image_src($image_id,'thumbnail');
								$image_url  = wp_get_attachment_url($image_id);
								if($image){
									?>
										<div class="fancybox_image">
											<a href="<?php echo wp_get_attachment_url($image_id); ?>" data-fancybox data-caption="">
												<img src="<?php echo $image_url; ?>" alt="" class="img-thumbnail"/>
											</a>
										</div>
									<?php
								}
							}
						?>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<?php if( $layout == "photo_gallery") : ?>
			<div class="wx_sp_flexible_content_photo_gallery_section">
				<div class="row">
					<?php 
						$photo_gallery = $sp_flexible_content_item[$layout] ?? [];	
							// dd($photo_gallery);					
						foreach( $photo_gallery as $image_id )
						{
							if( $image_id )
							{
								$image = wp_get_attachment_image_src($image_id,'thumbnail');
								$image_url  = wp_get_attachment_url($image_id);
								if($image){
									?>
										<div class="col-md-4">
											<a data-fancybox="gallery" href="<?php echo $image_url; ?>">
												<img class="rounded" src="<?php echo $image[0] ?? ''; ?>"/>
											</a>
										</div>
									<?php
								}
							}
						}
					?>
				</div>
			</div>
		<?php endif; ?>

		<?php if( $layout == "embed_video") : ?>
			<div class="wx_sp_flexible_content_embed_video_section">
				<div class="row">
					<div class="col-md-12">
						<?php 
							$embed_video_url = $sp_flexible_content_item[$layout];
							if( $embed_video_url )
							{
								$values = explode('?v=',$embed_video_url);
								$video_id = $values[1] ?? '';
								$video_url = "https://www.youtube.com/embed/".$video_id;
								?>
									<iframe src="<?php echo $video_url; ?>" id="myIframe" width="560" height="315" frameborder="0" allowfullscreen></iframe>
								<?php

							}
						?>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<?php if( $layout == "file_upload") : ?>
			<div class="wx_sp_flexible_content_file_upload_section">
				<div class="row">
					<div class="col-md-12">
						<?php 
							$file_id = $sp_flexible_content_item[$layout];
							$file_url = wp_get_attachment_url($file_id);
							if( $file_url ){
								$file_name =  basename(get_attached_file($file_id));
								?>
									<a href="<?php echo $file_url; ?>">
										<img src="<?php echo Wx_SPECIAL_POSTS_DIR_URL.'images/document.png'; ?>" alt="">
										<br>
										<span><?php echo $file_name; ?></span>
									</a>
								<?php
							}
						?>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<?php if( $layout == "url") : ?>
			<div class="wx_sp_flexible_content_url_section">
				<div class="row">
					<div class="col-md-12">
						<?php 
							$url = $sp_flexible_content_item[$layout] ?? [];
							if( is_array($url) )
							{		
								$url_action = ( $url['action'] ) ? 'target="_blank"':'';
								?>
									<a <?php echo $url_action; ?> href="<?php echo $url['link']; ?>"><?php echo $url['text']; ?></a>
								<?php	
							}
						?>
					</div>
				</div>
			</div>
		<?php endif; ?>

	<?php endforeach; ?>	
	
</div>
