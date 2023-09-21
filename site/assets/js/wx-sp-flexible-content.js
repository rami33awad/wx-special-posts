/**
 * 
 * @FlexibleContent 
 * 
 * */
(function($) {
    'use strict';

        $(document).on('click','.wx_add_sp_flexible_content',function(){
            var parent = $(this).closest('.sp_flexible_content_container_wrap');
            parent.find(".wx_add_sp_flexible_content_options").show();
        });

        $(document).on("change","select[name='wx_add_sp_flexible_content_options']",function(){
            var content_option = $(this).val();
            $(this).prop('selectedIndex', 0);

            var parent = $(this).closest('.sp_flexible_content_container_wrap');

            parent.find(".wx_add_sp_flexible_content_options").hide();
            build_sp_flexible_content('wx_sp_flexible_content_wrap',content_option,parent);
        });

        function replace_disabled_from_clone_html(html){
            return html.replaceAll('disabled','');
        }
        function build_sp_flexible_content(flexibleContentWrapper,contentOption,parent)
        {
            var outputHtml = '';
            var d = new Date();

            var time  = d.getTime();

            if( contentOption == "title_heading")
            {
                var titleHeadingCloneHtml = parent.find(".sp-flexible-title-heading-clone").html();
                outputHtml = titleHeadingCloneHtml.replaceAll('name=""',"name='sp_flexible_content["+time+"][title_heading]'");
                outputHtml = replace_disabled_from_clone_html(outputHtml);
            }

            if( contentOption == "paragraph")
            {
                var paragraphCloneHtml = parent.find(".sp-flexible-paragraph-clone").html();
                var namedParagraphHtml = paragraphCloneHtml.replaceAll('name=""',"name='sp_flexible_content["+time+"][paragraph]'");
                var id = 'wx_tinymce_textarea'+time;
                outputHtml = namedParagraphHtml.replaceAll('wx_tinymce_textarea_dynamic_id',id); 
                outputHtml = replace_disabled_from_clone_html(outputHtml);   
                init_tinymce(id);
            }
            
            if( contentOption == "image")
            {
                var imageCloneHtml = parent.find(".sp-flexible-image-clone").html();
                outputHtml = imageCloneHtml.replaceAll('name=""',"name='sp_flexible_content["+time+"][image]'");
                outputHtml = replace_disabled_from_clone_html(outputHtml);
            }

            if( contentOption == "photo_gallery")
            {
                var photoGalleryCloneHtml = parent.find(".sp-flexible-photo-gallery-clone").html();
                outputHtml = photoGalleryCloneHtml.replaceAll('name=""',"name='sp_flexible_content["+time+"][photo_gallery][]'");
                outputHtml = replace_disabled_from_clone_html(outputHtml);
            }

            if( contentOption == "embed_video")
            {
                var embedVideoCloneHtml = parent.find(".sp-flexible-embed-video-clone").html();
                outputHtml = embedVideoCloneHtml.replaceAll('name=""',"name='sp_flexible_content["+time+"][embed_video]'");
                outputHtml = replace_disabled_from_clone_html(outputHtml);
            }

            if( contentOption == "file_upload")
            {
                var fileUploadCloneHtml = parent.find(".sp-flexible-file-upload-clone").html();
                outputHtml = fileUploadCloneHtml.replaceAll('name=""',"name='sp_flexible_content["+time+"][file_upload]'");
                outputHtml = replace_disabled_from_clone_html(outputHtml);
            }

            if( contentOption == "url_link" )
            {
                var urlCloneHtml = parent.find(".sp-flexible-url-link-clone").html();
                var urlLinkCloneHtml = urlCloneHtml.replaceAll('data-name-link=""',"name='sp_flexible_content["+time+"][url][link]'");
                var urlTextCloneHtml = urlLinkCloneHtml.replaceAll('data-name-text=""',"name='sp_flexible_content["+time+"][url][text]'");
                outputHtml = urlTextCloneHtml.replaceAll('data-name-action=""',"name='sp_flexible_content["+time+"][url][action]'");
                outputHtml = replace_disabled_from_clone_html(outputHtml);
            }
            
            

            parent.find("."+flexibleContentWrapper).append(outputHtml);

            init_sortable();

            // jQuery('#modalTopicTemplate').animate({ scrollTop: jQuery('#modalTopicTemplate').height()}, 'slow');
        }


        var file_frame; // variable for the wp.media file_frame
        $(document.body).on('click','.sp_flexible_content_add_image',function(event){
            event.preventDefault();

            var anchorTag = $(this).find('a'); 
            var imageTag = $(this).find('img'); 
            var inputTag = $(this).find('input'); 
            
            // if ( file_frame ) {
            //     file_frame.open();
            //     return;
            // } 

            file_frame = wp.media.frames.file_frame = wp.media({
                title: 'Add Image',
                button: {
                    text: 'Upload Image',
                },
                library: {
                    type: [ 'image']
                },
                multiple: false // set this to true for multiple file selection
            });

            file_frame.on( 'select', function() {
                var attachment = file_frame.state().get('selection').first().toJSON();
                imageTag.attr('src',(attachment.sizes.thumbnail !== undefined) ? attachment.sizes.thumbnail.url : attachment.sizes.full.url ).show();
                inputTag.val(attachment.id);
                anchorTag.hide();
                
            });

            file_frame.open();

        });

        $(document.body).on('click','.removeFlexibleElm',function(event){
            $(this).closest('.card').remove();
        });

        var custom_uploader;
        $(document.body).on('click','.add_photo_gallery_images',function(event){
            event.preventDefault();
            var parent = $(this).closest('.card');

            custom_uploader  = wp.media.frames.file_frame = wp.media({
                title: 'Add Image',
                button: {
                    text: 'Upload Image',
                },
                library: {
                    type: [ 'image']
                },
                multiple: true // set this to true for multiple file selection
            });

            custom_uploader.on('select', function() {
              var selection = custom_uploader.state().get('selection');
              selection.map( function( attachment ) {
                attachment = attachment.toJSON();
                var img_html = parent.find('.img_clone').html();

                var img_Src = (attachment.sizes.thumbnail !== undefined) ? attachment.sizes.thumbnail.url : attachment.sizes.full.url;

                var img_tag = img_html.replaceAll('src=""','src="'+img_Src+'"');
                var inputVal = img_tag.replaceAll('value=""','value="'+attachment.id+'"');
                parent.find('.row').append(inputVal);
              });
            });

            custom_uploader.open();

        });

        $(document.body).on('click','.sp_flexible_content_upload_file',function(event){
            event.preventDefault();

            var parent = $(this).closest('.card');


            file_frame = wp.media.frames.file_frame = wp.media({
                title: 'Add File',
                button: {
                    text: 'Upload file',
                },
                library: {
                    type: [ 'application','text']
                },          
                multiple: false // set this to true for multiple file selection
            });

            file_frame.on( 'select', function() {
                var attachment = file_frame.state().get('selection').first().toJSON();
                parent.find('code').text(attachment.filename);
                parent.find('input').val(attachment.id);
                parent.find('a').hide();
                parent.find('.file').show();
            });

            file_frame.open();
        });  
        
        

        Fancybox.bind('[data-fancybox="gallery"]', {
            animated: false,
            showClass: false,
            hideClass: false,
          
            Toolbar: false,
          
            closeButton: "top",
            click: false,
            dragToClose: false,
          
            Carousel: {
              // Disable content slide animation
              friction: 0,
          
              // Disable touch guestures
              Panzoom: {
                touch: false,
              },
          
              Navigation: false,
            },
          
            Image: {
              // Disable animation from/to thumbnail on start/close
              zoom: false,
          
              // Disable zoom on scroll event
              wheel: false,
          
              // Disable zoom on image click
              click: false,
          
              // Fit image horizontally only
              fit: "contain-w",
            },
          
            // Center thumbnails only if draggable
            Thumbs: {
              minScreenHeight: 0,
              Carousel: {
                center: function () {
                  return this.elemDimWidth > this.wrapDimWidth;
                },
              },
            },
          });



})(jQuery);

function init_sortable(){
    jQuery( ".wx_sp_flexible_content_sortable" ).sortable({
        change: function( event, ui ) {
        }
    }); 
}
init_sortable();


// tinymce @init
function init_tinymce(id)
{
    setTimeout(function(){
        tinymce.init({
            selector: '#'+id
        });
    },100);

}
