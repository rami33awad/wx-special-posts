<div class="wrap">
   <h2><?php _e( Wx_SPECIAL_POSTS_NAME.' Settings', 'wx-special-posts' ); ?></h2>
   <?php settings_errors(); ?>

   <?php 
      $active_tab = '';
      $query_tab = $_GET['tab'] ?? 'wx_special_posts_form_settings_options';
      $active_tab = $query_tab;

      $tab_links = [
         [
            'title'     => 'Form',
            'page_slug' => 'wx_special_posts_form_settings_options'
         ]
      ];

    ?>

   <h2 class="nav-tab-wrapper">
      
      <?php 
         foreach($tab_links as $tab_link): 
         $page_title = $tab_link['title'] ?? '';
         $page_slug  = $tab_link['page_slug'] ?? '';
      ?>
         <a href="?page=wx_special_posts_settings&tab=<?php echo $page_slug; ?>" 
         class="nav-tab <?php echo $active_tab == $page_slug ? 'nav-tab-active' : ''; ?>">
            <?php _e( $page_title, 'wx-special-posts' ); ?>  
         </a>
      <?php endforeach; ?>  
      
   </h2>
   <form method="post" action="options.php">
        <?php
            foreach($tab_links as $tab_link){
               $page_slug  = $tab_link['page_slug'] ?? '';

               if( $active_tab == $page_slug){
                  settings_fields( $page_slug );
                  do_settings_sections( $page_slug );
               }

            }
            submit_button();
        ?>
   </form>
</div>