<?php

   function wp_zillow_bp_admin_callback(){
    if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }
    if ( ! isset( $_REQUEST['wpzillow_zwsid'] ) ){
        $_REQUEST['wpzillow_zwsid'] = ''; 
        $updated = false;
    }
    else{
        
        if( !check_admin_referer( 'update_zwsid' ) ){
            wp_die( __( 'Something went wrong. Please try again.' ) );
        }
        
        $updated = update_option('wpzillow_zwsid', sanitize_text_field($_REQUEST['wpzillow_zwsid']));
        
    }
     ?>
     <div class="wrap">
 
          <?php if ( false !== $updated ) : ?>
               <div class="updated fade"><p><strong>Option Saved</strong></p></div>
          <?php endif; ?>
           
          <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
           
          <div id="poststuff">
               <div id="post-body">
                    <div id="post-body-content">
                         <form method="post" action="">
                              
                              <?php $current_zwsid = get_option('wpzillow_zwsid') ?>
                              <table class="form-table">
                                   <tr valign="top"><th scope="row"><?php echo('Enter you Zillow Web Service ID') ?></th>
                                        <td>
                                            <?php echo(wp_nonce_field( 'update_zwsid' )) ?>
                                            <input id="wpzillow_zwsid" name="wpzillow_zwsid" value="<?php echo($current_zwsid) ?>" /> 
                                            <input type="submit" name="submit" value="Submit" />
                                        </td>
                                   </tr>
                              </table>
                         </form>
                    </div> <!-- end post-body-content -->
               </div> <!-- end post-body -->
          </div> <!-- end poststuff -->
     </div>
<?php
}

function wp_zillowbp_adminmenu(){

    add_options_page( 
        'WP Zillow Options', 
        'WP Zillow', 
        'manage_options', 
        'wp-zillow-bp-admin', 
        'wp_zillow_bp_admin_callback' );

}

add_action( 'admin_menu', 'wp_zillowbp_adminmenu' );

function wpzillowbp_input(){

    echo('<input id="wpzillow_zwsid" name="wpzillow_zwsid" value="" />');

}

function wp_zillow_bp_sectioncontent(){

    echo('<h1>Settings - Zillow</h1>');
    
}

function wpzillowbp_cleanzwsid($zwsid){
    
    return sanitize_text_field($zwsid);
    
}

function wpzillow_register_setting(){
    
    add_settings_section(
        'wp_zillow_bp_section',
        'WP Zillow Settings',
        'wp_zillow_bp_sectioncontent',
        'wp-zillow-bp-admin'
    );
    
    //add_settings_field( $id, $title, $callback, $page, $section = 'default', $args = array() )

    add_settings_field( 
        'wpzillow_zwsid',//$id, 
        'ZWSID',//$title, 
        'wpzillowbp_input',//$callback, 
        'wp-zillow-bp-admin',//page 
        'default', 
        array() );

    register_setting( 'wp-zillow-bp-admin', 'wpzillow_zwsid', 'wpzillowbp_cleanzwsid' );
}

add_action( 'admin_init', 'wpzillow_register_setting' );

?>