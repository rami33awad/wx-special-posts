<?php
namespace Wx_Special_Posts\Admin\Traits;

trait Trait_Wx_Special_Posts_Form{
    /**
	 * Init course icons section
	 * @since    1.0.0
	 */
    public function init_form_settings_options(){
        
        if( false == get_option( 'wx_special_posts_form_settings_options' ) ) {
			$default_array = $this->default_form_settings_options();
			add_option( 'wx_special_posts_form_settings_options', $default_array );
		}

				
        add_settings_section(
            'wx_special_posts_form_settings_options_section',			            // ID used to identify this section and with which to register options
			__( '', 'wx-special-posts' ),		        // Title to be displayed on the administration page
			array( $this, 'wx_special_posts_form_settings_options_callback'),	    // Callback used to render the description of the section
			'wx_special_posts_form_settings_options'		                // Page on which to add this section of options
		);
        
        $wx_special_posts_form_settings_options = get_option('wx_special_posts_form_settings_options');

		$fields_data = $this->default_form_settings_fields();
		foreach($fields_data as $field_key=>$field_data){
			$label = $field_data['label'] ?? '';
			$type = $field_data['type'] ?? '';
			add_settings_field(
				$field_key,						        // ID used to identify the field throughout the theme
				__( $label, 'wx-special-posts' ),					// The label to the left of the option interface element
				array( $this, 'wx_form_settings_callback'),	// The name of the function responsible for rendering the option interface
				'wx_special_posts_form_settings_options',	            // The page on which this option will be displayed
				'wx_special_posts_form_settings_options_section',			        // The name of the section to which this field belongs
				array(								        // The array of arguments to pass to the callback. In this case, just a description.
					'wx_special_posts_form_settings_options' => $wx_special_posts_form_settings_options ,
					'name'  => $field_key,
					'type'	=> $type
				)   
			);
		}
        
		
        // Finally, we register the fields with WordPress
		register_setting(
			'wx_special_posts_form_settings_options',
			'wx_special_posts_form_settings_options',
			array( $this, 'validate_wx_special_posts_form_settings_options')
		);
    }

    public function default_form_settings_options(){
		
		$default_roles = [];
		foreach(wp_roles()->roles as $role_key=>$role){
            if( !in_array($role_key,$this->wx_get_restricted_roles())){
				$default_roles[] = $role_key;
			}
		}

        $defaults = [
            'form_cat_add'  			=> $default_roles,
            'form_sub_cat_add'  		=> $default_roles,			
            'form_tag_add'  			=> $default_roles,		
            'form_assign_group'  		=> $default_roles,		
            'form_tag_group_members'  	=> $default_roles,		
            'form_tag_friends'  		=> $default_roles,		
            'form_tag_all_members'  	=> $default_roles,		

            'rep_title_heading'  		=> $default_roles,
            'rep_paragraph'  			=> $default_roles,
            'rep_image'  				=> $default_roles,
            'rep_photo_gallery'  		=> $default_roles,
            'rep_embed_video'  			=> $default_roles,
            'rep_file_upload'  			=> $default_roles,
            'rep_url_link'  			=> $default_roles,
        ];

		// $defaults =  apply_filters('wx_special_posts_form_settings_defaults',$defaults);
		return $defaults;
    }

	public function default_form_settings_fields(){
		$dashboard_settings_fields = [
            'form_cat_add'        		=> [ 'type'	=>	'checkbox',	'label' => 'Who can add Categories?' 		],
            'form_sub_cat_add'        	=> [ 'type'	=>	'checkbox',	'label' => 'Who can add Sub Categories?' 	],
            'form_tag_add'        		=> [ 'type'	=>	'checkbox',	'label' => 'Who can add Tags?' 				],
            'form_assign_group'        	=> [ 'type'	=>	'checkbox',	'label' => 'Who can assign Group?' 			],
            'form_tag_group_members'    => [ 'type'	=>	'checkbox',	'label' => 'Who can Tag Group Members?' 	],
            'form_tag_friends'    		=> [ 'type'	=>	'checkbox',	'label' => 'Who can Tag Friends?' 			],
            'form_tag_all_members'    	=> [ 'type'	=>	'checkbox',	'label' => 'Who can Tag Members?' 			],

            'rep_title_heading'        	=> [ 'type'	=>	'checkbox',	'label' => 'Who can add Title Heading?' 	],
            'rep_paragraph'        		=> [ 'type'	=>	'checkbox',	'label' => 'Who can add Paragraph?' 		],
            'rep_image'        			=> [ 'type'	=>	'checkbox',	'label' => 'Who can add Image?' 			],
            'rep_photo_gallery'        	=> [ 'type'	=>	'checkbox',	'label' => 'Who can add Photo Gallery?' 	],
            'rep_embed_video'        	=> [ 'type'	=>	'checkbox',	'label' => 'Who can add Embed Video?' 		],
            'rep_file_upload'        	=> [ 'type'	=>	'checkbox',	'label' => 'Who can add File Upload?' 		],
            'rep_url_link'        		=> [ 'type'	=>	'checkbox',	'label' => 'Who can add Url Link?' 			],
        ];

		// $dashboard_settings_fields = apply_filters('wx_special_posts_form_settings_fields',$dashboard_settings_fields);
		return $dashboard_settings_fields;
	}

	
    public function wx_special_posts_form_settings_options_callback(){

    }

    public function wx_form_settings_callback($args){
        if ( is_file( Wx_SPECIAL_POSTS_DIR_PATH . 'admin/views/permissions/form-fields.php' ) ) {
            include Wx_SPECIAL_POSTS_DIR_PATH . 'admin/views/permissions/form-fields.php';
        }
    }

    public function validate_wx_special_posts_form_settings_options($input){
        // Create our array for storing the validated options
		$output = array();

		// Loop through each of the incoming options
		foreach( $input as $key => $value ) {

			// Check to see if the current option has a value. If so, process it.
			if( isset( $input[$key] ) ) {

				if( !is_array($input[$key]) ){
					// Strip all HTML and PHP tags and properly handle quoted strings
					$output[$key] = strip_tags( stripslashes( $input[ $key ] ) );
				}else{
					// For array save 
					$output[$key] = $input[$key];
				} 

			} // end if

		} // end foreach

		// Return the array processing any additional functions filtered by this action
		return apply_filters( 'validate_wx_special_posts_form_settings_options', $output, $input );
    }
}