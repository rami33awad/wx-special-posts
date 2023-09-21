<?php 
    $wx_special_posts_form_settings_options = $args['wx_special_posts_form_settings_options'] ?? '';
    $field_name = $args['name'] ?? '';
    $field_type = $args['type'] ?? '';
    $field_val = $wx_special_posts_form_settings_options[$field_name] ?? [];

    if( $field_type == "checkbox"){
        $counter = 1;
        foreach(wp_roles()->roles as $role_key=>$role){
            if( !in_array($role_key,$this->wx_get_restricted_roles())){
            $checked = ( in_array($role_key,$field_val) )?'checked':'';
            ?>
                <label for="<?php echo  $field_name.$role_key ?>">
                    <input <?php echo $checked; ?> 
                    name="wx_special_posts_form_settings_options[<?php echo $field_name; ?>][]" 
                    type="checkbox" 
                    id="<?php echo  $field_name.$role_key ?>" 
                    value="<?php echo $role_key; ?>"> <?php echo $role['name'] ?? ''; ?>				
                </label>
            <?php
            if( $counter == 10 ){ $counter = 0; echo '<br/>'; }
            $counter++;
            }
        }
    }

    if($field_type == "image"){
        $image_thumbnail = '';
            $attachment_id = $field_val ?? 0;
            if( is_numeric($attachment_id) ){
                $image = wp_get_attachment_image_src($attachment_id,'thumbnail');
                if($image){
                    $image_thumbnail = $image[0] ?? '';
                }
            }else{
                $image_thumbnail = $attachment_id;
            }
            ?>
                <div class="wx-image-component">
                    <div class="form-group wx-admin-add-image-component wx-social">
                        <div class=" <?php if($image_thumbnail){ echo 'wx_hide'; } ?>">
                            <a class="button button-primary wx-admin-add-image">Add Image</a>
                        </div>
                        <img src="<?php echo $image_thumbnail;?>" alt="" class="wx-admin-add-image <?php if(!$image_thumbnail){ echo 'wx_hide'; } ?>">
                        <input  type="hidden" name="wx_special_posts_form_settings_options[<?php echo $field_name; ?>]" value="<?php echo $attachment_id; ?>">
                    </div>
                </div>
            <?php
    }

    if($field_type == "text"){
        $field_val = $wx_special_posts_form_settings_options[$field_name] ?? '';
        ?>
            <input type="<?php echo $field_type; ?>" 
            class="field_width_50"
            name="wx_special_posts_form_settings_options[<?php echo $field_name; ?>]" 
            value="<?php echo $field_val; ?>">
        <?php
    }
    