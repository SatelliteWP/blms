<?php
/**
 * Plugin Name: BlackLivesMatter
 * Description: Calls the Support BlackLivesMatter script to make your homepage colorless at a certain date. See our website for details about the dates.
 * Version: 1.0
 * Author: Support BlackLivesMatter
 * Author URI: https://blacklivesmatter.support/
*/

function blms_enqueue_script(){
  wp_enqueue_script( 'blms', 'https://blacklivesmatter.support/js/blms.js', array(), '1.0', true );
}
add_action( 'wp_enqueue_scripts', 'blms_enqueue_script' );

/**
 * Register each of the options that will be part of our settings page
*/
function blms_register_settings(){
  add_settings_section( 'general_section', 'General Setting', null, 'blms_settings' );

  add_settings_field( 'debug-options', 'Debug mode', 'blms_render_debug_mode_field', 'blms_settings', 'general_section' );  
  add_settings_field( 'badge-location-options', 'Badge location', 'blms_render_badge_location_field', 'blms_settings', 'general_section' );  
  add_settings_field( 'simulation-options', 'Simulation', 'blms_render_simulation_field', 'blms_settings', 'general_section' );  
  
  register_setting( 'general_section', 'debug-options' );
  register_setting( 'general_section', 'badge-location-options' );
  register_setting( 'general_section', 'simulation-options' );
}
add_action( 'admin_init', 'blms_register_settings' );

/**
 * Render the debug mode option
*/
function blms_render_debug_mode_field(){
?>
  <select name='debug-options'>
    <option value='0' <?php selected( get_option( 'debug-options' ), '0' ); ?>>False</option>
    <option value='1' <?php selected( get_option( 'debug-options' ), '1' ); ?>>True</option>
  </select>
<?php
}

/**
 * Render the badge location option
*/
function blms_render_badge_location_field(){
?>
  <select name='badge-location-options'>
    <option value='0' <?php selected( get_option( 'badge-location-options' ), '0' ); ?>>Top left</option>
    <option value='1' <?php selected( get_option( 'badge-location-options' ), '1' ); ?>>Top right</option>
    <option value='2' <?php selected( get_option( 'badge-location-options' ), '2' ); ?>>Bottom right</option>
    <option value='3' <?php selected( get_option( 'badge-location-options' ), '3' ); ?>>Bottom left</option>
  </select>
<?php
}

/**
 * Render the simulation option
*/
function blms_render_simulation_field(){
?>
  <a href='#' target='_blank'>Cliquez ici!</a>
<?php
}

/**
 * Render the settings' page created previously
*/ 
function blms_render_settings_page(){
?>
  <div class='wrap'>
      <h1>Black Lives Matter Plugin Settings</h1>
      <form method='post' action='options.php'>
        <?php
          settings_fields( 'general_section' );
          do_settings_sections( 'blms_settings' );
          submit_button();
        ?>
      </form>
  </div>
<?php
}

/**
 * Add an item for settings page that will appear under the WordPress Settings menu
*/
function add_menu_item(){
  add_submenu_page( 'options-general.php', 'blms_plugin', 'BLMS', 'manage_options', 'blms_settings', 'blms_render_settings_page' );
}
add_action( 'admin_menu', 'add_menu_item' );
