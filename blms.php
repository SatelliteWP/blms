<?php
/**
 * Plugin Name: BlackLivesMatter
 * Description: Calls the Support BlackLivesMatter script to make your homepage colorless at a certain date. See our website for details about the dates.
 * Version: 1.0
 * Author: Support BlackLivesMatter
 * Author URI: https://blacklivesmatter.support/
 * Text Domain: blms
 */

function blms_enqueue_script(){
  $settings_options = array(
    'debug' => get_option( 'blms-debug-mode' ),
    'badge_location' => get_option( 'blms-badge-location' ),
    'language' => get_locale()
  );

  wp_enqueue_script( 'blms', plugins_url( 'blms.js', __file__ ), array(), '1.0', true );
  wp_localize_script( 'blms', 'settings_options', $settings_options );
}
add_action( 'wp_enqueue_scripts', 'blms_enqueue_script' );

/**
 * Register each of the options that will be part of our settings page
 */
function blms_register_settings(){
  add_settings_section( 'general_section', __( 'General Setting', 'blms' ), null, 'blms_settings' );

  add_settings_field( 'blms-debug-mode', __( 'Debug mode', 'blms' ), 'blms_render_debug_mode_field', 'blms_settings', 'general_section' );  
  add_settings_field( 'blms-badge-location', __( 'Badge location', 'blms' ), 'blms_render_badge_location_field', 'blms_settings', 'general_section' );  
  add_settings_field( 'blms-language', __( 'Support language', 'blms' ), 'blms_render_language_field', 'blms_settings', 'general_section' );  
  add_settings_field( 'blms-simulation', __( 'Simulation', 'blms' ), 'blms_render_simulation_field', 'blms_settings', 'general_section' );  
  
  register_setting( 'general_section', 'blms-debug-mode' );
  register_setting( 'general_section', 'blms-badge-location' );
  register_setting( 'general_section', 'blms-language' );
  register_setting( 'general_section', 'blms-simulation' );
}
add_action( 'admin_init', 'blms_register_settings' );

/**
 * Render the debug mode option
 */
function blms_render_debug_mode_field(){
  $debug_mode_value = get_option( 'blms-debug-mode' );
?>
  <select name="blms-debug-mode">
    <option value=0 <?php selected( $debug_mode_value, 0 ); ?>><?php _e( 'False', 'blms' )?></option>
    <option value=1 <?php selected( $debug_mode_value, 1 ); ?>><?php _e( 'True', 'blms' )?></option>
  </select>
<?php
}

/**
 * Render the badge location option
 */
function blms_render_badge_location_field(){
  $badge_location_value = get_option( 'blms-badge-location' );
?>
  <select name="blms-badge-location">
    <option value="topleft" <?php selected( $badge_location_value, 'topleft' ); ?>><?php _e( 'Top left', 'blms' )?></option>
    <option value="topright" <?php selected( $badge_location_value, 'topright' ); ?>><?php _e( 'Top right', 'blms' )?></option>
    <option value="bottomright" <?php selected( $badge_location_value, 'bottomright' ); ?>><?php _e( 'Bottom right', 'blms' )?></option>
    <option value="bottomleft" <?php selected( $badge_location_value, 'bottomleft' ); ?>><?php _e( 'Bottom left', 'blms' )?></option>
  </select>
<?php
}

/**
 * Render the website language
 */
function blms_render_language_field(){
  $language_value = get_option( 'blms-language' );
  echo get_locale();
}

/**
 * Render the simulation option
 */
function blms_render_simulation_field(){
?>
  <a href="<?= get_home_url().'?blms_simulation';?>" target="_blank"><?php _e( 'Preview your colorless homepage', 'blms' )?></a>
<?php
}

/**
 * Render the settings' page created previously
 */ 
function blms_render_settings_page(){
?>
  <div class="wrap">
      <h1><?php _e( 'Support #BlackLivesMatter Settings', 'blms' )?></h1>
      <form method="post" action="options.php">
        <?php
          settings_fields( 'general_section' );
          do_settings_sections( 'blms_settings' );
          submit_button( __( 'Save changes', 'blms' ) );
        ?>
      </form>
  </div>
<?php
}

/**
 * Add an item for settings page that will appear under the WordPress Settings menu
 */
function blms_add_menu_item(){
  add_submenu_page( 'options-general.php', 'blms_plugin', 'BLMS', 'manage_options', 'blms_settings', 'blms_render_settings_page' );
}
add_action( 'admin_menu', 'blms_add_menu_item' );

/**
 * Display a notice to informe admin that the website will turns colorless in x days...
 */
function blms_admin_notice(){
  $screen = get_current_screen();
  if( 'dashboard' === $screen->id ){ //Display the notice on the dashboard
    $now = new DateTime();
    if( $now->format('n') == 5 && ( $now->format('d') >= 15 && $now->format('d') <= 22 ) ){ ?>
      <div class="notice notice-warning is-dismissible">
          <p><?php _e( 'Reminder: Your homepage will turn colorless from May 23rd to 25th to support #BlackLivesMatter.', 'blms' ) ?></p>
      </div>
    <?php }elseif( $now->format('n') == 5 && ( $now->format('d') >= 23 && $now->format('d') <= 25 ) ){ ?>
      <div class="notice notice-info is-dismissible">
          <p><?php _e( 'Reminder: Your homepage is colorless for 3 days from May 23rd to 25th. Thank you for your support to #BlackLivesMatter.', 'blms' ) ?></p>
      </div>
    <?php }
  }
}
add_action( 'admin_notices', 'blms_admin_notice' );
