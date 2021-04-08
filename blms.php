<?php
/**
 * Plugin Name: BlackLivesMatter
 * Description: Calls the Support BlackLivesMatter script to make your homepage colorless at a certain date. See our website for details about the dates.
 * Version: 1.0
 * Author: Support BlackLivesMatter
 * Author URI: https://blacklivesmatter.support/
*/

function blms_init() {
  $labels = array(
      'name'      => __( 'Parameters', 'blms' ),
      'menu_name' => __( 'Black Lives Matter', 'blms' )
  );
  $args = array(
      'labels'        => $labels,
      'public'        => true,
      'menu_position' => 80,
      'capabilities'  => array(
        'create_posts' => 'do_not_allow'
      )
  );
  register_post_type( 'blms', $args );
}
add_action( 'init', 'blms_init' );

function blms_register_submenu_page() {
  add_submenu_page( 'edit.php?post_type=blms', '', 'Parameters', 'manage_options', 'parameters', 'blms_submenu_page_callback' ); 
}

function blms_submenu_page_callback() {
    echo '<div class="wrap">
            <h2>Parameters</h2>
            <div class="blms-param-content">
              <form method="POST" action="">
                <ul>
                  <li>
                    Debug mode :
                    <select>
                      <option>True</option>
                      <option selected>False</option>
                    </select>
                  </li>
                  <li>
                    Badge location : 
                    <select>
                      <option>Top left</option>
                      <option>Top right</option>
                      <option selected>Bottom right</option>
                      <option>Bottom left</option>
                    </select>
                  </li>
                  <li>
                    Page language : 
                    <textarea></textarea>
                  </li>
                  <li>
                    Simulation : 
                    <a href="#" target="_blank">Click here !</a>
                  </li>
                </ul>
                <button type="submit">Save</button>
              </form>
            </div>
          </div>';
}
add_action( 'admin_menu', 'blms_register_submenu_page' );

function blms_enqueue_script(){
  wp_enqueue_script( 'blms', 'https://blacklivesmatter.support/js/blms.js', array(), '1.0', true );
}
add_action( 'wp_enqueue_scripts', 'blms_enqueue_script' );

