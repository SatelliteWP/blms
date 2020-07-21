<?php
/**
 * Plugin Name: BlackLivesMatter
 * Description: Calls the Support BlackLivesMatter script to make your homepage colorless at a certain date. See our website for details about the dates.
 * Version: 1.0
 * Author: Support BlackLivesMatter
 * Author URI: https://blacklivesmatter.support/
*/

add_action( 'wp_enqueue_scripts', 'blms_enqueue_script' );

function blms_enqueue_script(){
  wp_enqueue_script( 'blms', 'https://blacklivesmatter.support/js/blms.js', array(), '1.0', true );
}
