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
  register_setting( 'general_section', 'debug-options' );
}

/**
 * Function that will render the debug mode option
*/
function blms_render_debug_mode_field(){
?>
  <select name='debug-options'>
    <option value='0' <?php selected( get_option( 'debug-options' ), '0' ); ?>>False</option>
    <option value='1' <?php selected( get_option( 'debug-options' ), '1' ); ?>>True</option>
  </select>
<?php
}
add_action( 'admin_init', 'blms_register_settings' );

/**
 * Function that will be in charge of rendering settings page created previously
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
  add_submenu_page( 'options-general.php', 'blms_plugin', 'BLMS Settings', 'manage_options', 'blms_settings', 'blms_render_settings_page' );
}
add_action( 'admin_menu', 'add_menu_item' );
