<?php
/**
 * Plugin Name: BlackLivesMatter
 * Description: Calls the Support BlackLivesMatter script to make your homepage colorless at a certain date. See our website for details about the dates.
 * Version: 1.1
 * Tested up to: 5.7.1
 * Author: Support BlackLivesMatter
 * Author URI: https://blacklivesmatter.support/
 * Text Domain: blms
*/

function blms_enqueue_script(){
  $default_page_language = array( 'en'  => '/', 'fr' => '/fr' );
  $defined_page_language = array();

  //Check english pages is defined by the admin
  $en_homepage = get_option( 'blms-en-page', get_home_url() );
  $blms_en_page = ($en_homepage !== '') ? str_replace( get_home_url(), '/', $en_homepage ) : '/';
  $defined_page_language['en'] = $blms_en_page;

  //Check french pages is defined by the admin
  $fr_homepage = get_option( 'blms-fr-page', get_home_url() );
  $blms_fr_page = ($fr_homepage !== '') ? str_replace( get_home_url(), '/', $fr_homepage ) : '/fr';
  $defined_page_language['fr'] = $blms_fr_page;

  $blms_pages = ( count( $defined_page_language ) > 0 ) ? $defined_page_language : $default_page_language;
  $blms_pages = json_encode( $blms_pages );

  $blms_settings_options = 'var blms_debug = '.get_option( 'blms-debug-mode', 'false' ).'; ';
  $blms_settings_options .= 'var blms_badge_location = "'.get_option( 'blms-badge-location', 'bottomright' ).'"; ';
  $blms_settings_options .= 'var blms_pages = '.$blms_pages.'; ';

  wp_enqueue_script( 'blms', plugins_url( 'blms.js', __file__ ), array(), '1.0', true );
  wp_add_inline_script( 'blms', $blms_settings_options, 'before' );
}
add_action( 'wp_enqueue_scripts', 'blms_enqueue_script' );

/**
 * Add an item for settings page that will appear under the WordPress Settings menu
 */
function blms_add_menu_item(){
  add_submenu_page( 'options-general.php', 'blms_plugin', 'BLMS', 'manage_options', 'blms_settings', 'blms_render_settings_page' );
}
add_action( 'admin_menu', 'blms_add_menu_item' );

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
 * Register each of the options that will be part of our settings page
 */
function blms_register_settings(){
  add_settings_section( 'general_section', '', null, 'blms_settings' );

  add_settings_field( 'blms-debug-mode', __( 'Debug mode', 'blms' ), 'blms_render_debug_mode_field', 'blms_settings', 'general_section' );  
  add_settings_field( 'blms-badge-location', __( 'Badge location', 'blms' ), 'blms_render_badge_location_field', 'blms_settings', 'general_section' );  
  add_settings_field( 'blms-en-page', __( 'English homepage URL', 'blms' ), 'blms_render_en_homepage_field', 'blms_settings', 'general_section' );  
  add_settings_field( 'blms-fr-page', __( 'French homepage URL', 'blms' ), 'blms_render_fr_homepage_field', 'blms_settings', 'general_section' );  
  add_settings_field( 'blms-simulation', __( 'Simulation', 'blms' ), 'blms_render_simulation_field', 'blms_settings', 'general_section' );  
  
  register_setting( 'general_section', 'blms-debug-mode' );
  register_setting( 'general_section', 'blms-badge-location' );
  register_setting( 'general_section', 'blms-en-page' );
  register_setting( 'general_section', 'blms-fr-page' );
  register_setting( 'general_section', 'blms-simulation' );

}
add_action( 'admin_init', 'blms_register_settings' );

/**
 * Render the debug mode option
 */
function blms_render_debug_mode_field(){
  $debug_mode = get_option( 'blms-debug-mode', false );
?>
  <select name="blms-debug-mode">
    <option value='false' <?php selected( $debug_mode, 'false' ); ?>><?php _e( 'False', 'blms' )?></option>
    <option value='true' <?php selected( $debug_mode, 'true' ); ?>><?php _e( 'True', 'blms' )?></option>
  </select>
<?php
}

/**
 * Render the badge location option
 */
function blms_render_badge_location_field(){
  $badge_location = get_option( 'blms-badge-location', 'bottomright' );
?>
  <select name="blms-badge-location">
    <option value="topleft" <?php selected( $badge_location, 'topleft' ); ?>><?php _e( 'Top left', 'blms' )?></option>
    <option value="topright" <?php selected( $badge_location, 'topright' ); ?>><?php _e( 'Top right', 'blms' )?></option>
    <option value="bottomright" <?php selected( $badge_location, 'bottomright' ); ?>><?php _e( 'Bottom right', 'blms' )?></option>
    <option value="bottomleft" <?php selected( $badge_location, 'bottomleft' ); ?>><?php _e( 'Bottom left', 'blms' )?></option>
  </select>
<?php
}

/**
 * Render the english home page URL
 */
function blms_render_en_homepage_field(){
  $en_homepage = get_option( 'blms-en-page', '' );
?>
  <input type="text" name="blms-en-page" class="regular-text" placeholder="<?php _e( 'English homepage URL', 'blms' )?>" value="<?= esc_attr( $en_homepage ); ?>">
<?php
}

/**
 * Render the french home page URL
 */
function blms_render_fr_homepage_field(){
  $fr_homepage = get_option( 'blms-fr-page', '' );
?>
  <input type="text" name="blms-fr-page" class="regular-text" placeholder="<?php _e( 'French homepage URL', 'blms' )?>" value="<?= esc_attr( $fr_homepage ); ?>">
<?php
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
 * Display a notice to informe admin the website will turns colorless
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

/**
 * Adding the "Settings" link in the plugin's page.
 */
function blms_settings_link( $links ){ 
  $links[] = '<a href="'.admin_url('options-general.php?page=blms_settings').'">'.__( 'Settings', 'blms' ).'</a>'; 
	return $links;
}
$plugin = plugin_basename( __FILE__ );
add_filter( 'plugin_action_links_'.$plugin, 'blms_settings_link' );
