<?php
/*
Plugin Name: Simple RSS Aggregator 
Plugin URI: http://wordpress.org/extend/plugins/simple-rss-aggregator/
Author URI: http://araujo.cc/
Description: Imports and aggregates RSS Feeds using each user as feed provider.
Author: Arthur Araújo
Version: 1.0.1
*/

//sra_update('http://cybermundo.com.br/feed/');

include dirname(__FILE__).'/add-userfields.php';
include dirname(__FILE__).'/cron-jobs.php';
include dirname(__FILE__).'/filters.php';
include dirname(__FILE__).'/functions.php';

add_action( 'init', 'add_user_feed_post_type' );

if( is_admin() )
	include dirname(__FILE__).'/admin.php';

// CORRIGIR Fatal error: Call to undefined function wp_get_current_user() in /var/www/cybermundo/wp-includes/capabilities.php on line 1281

// Force feed update
if( !empty($_GET['force_feed_update']) && current_user_can('manage_options') )
	sra_update_all();

// Show next feed update
if( !empty($_GET['next_feed_update']) && current_user_can('manage_options') ) {
	echo wp_next_scheduled( 'sra_update_all_hook' );
	exit;
}

?>
