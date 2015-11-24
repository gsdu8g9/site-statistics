<?php
/*
Plugin Name: Site statistics
Plugin URI:
Description: Add site statistics about users
Author: Alex
Version: 1.0.1
 */

//Hook for load language
add_action( 'plugins_loaded', 'statistics_load_lang' );

/**
 * function to load languages
 */
function statistics_load_lang() {
    load_plugin_textdomain( "site-statistics", false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

// Include widget.php
require_once ('widget.php');