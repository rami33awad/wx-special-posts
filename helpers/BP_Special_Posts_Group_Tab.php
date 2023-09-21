<?php
if (!function_exists('wx_special_posts_group_extension')) {
    function wx_special_posts_group_extension(){
        if ( bp_is_active( 'groups' ) ){
            class BP_Special_Posts_Group_Tab extends BP_Group_Extension {

                function __construct() {
            
                    $args = array(
                        'slug'              => 'wx-special-posts',
                        'name'              =>  __( Wx_SPECIAL_POSTS_NAME, 'buddypress' ),
                        'nav_item_position' => 200,
                        'show_tab'          => 'anyone',
                        'screens' => array(
                            'edit' => array(
                                'name'      => __( Wx_SPECIAL_POSTS_NAME, 'buddypress' ),
                            ),
                            'create'        => array( 'position' => 10, ),
                        ),
                    );
                    parent::init( $args );
                }
            
                function display( $group_id = NULL ) {
                    $group_id = bp_get_group_id();            
                    echo do_shortcode('[wx-group-special-posts bb_group_id="'.$group_id.'"]');
                }
            
                function settings_screen( $group_id = NULL ) {
                    $setting = groups_get_groupmeta( $group_id, 'group_extension_setting'  );
                    $group_types = bp_groups_get_group_type( $group_id, false );
                    
                }
            
                function settings_screen_save( $group_id = NULL ) {
                    $setting = isset( $_POST['group_extension_setting'] ) ? '1' : '0';
                    groups_update_groupmeta( $group_id, 'group_extension_setting', $setting );
                }
            
            }
            bp_register_group_extension( 'BP_Special_Posts_Group_Tab' );
        }
    }
}

//* Init Custom Tab Here 
add_action('bp_init', 'wx_special_posts_group_extension');