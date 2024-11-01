<?php

add_action( 'admin_init', 'sra_register_settings' );

# ADD MENU SETTINGS ITEM
add_action('admin_menu', 'sra_settings_menu');
function sra_settings_menu() {
	add_options_page('Simple RSS Aggregator', 'RSS Aggregator', 'manage_options', 'simple-rss-aggregator', 'sra_admin_page');
}

# TEMPLATE PAGE FUNCTION
function sra_admin_page() {
	$configs = get_option('powerconfigs');
	include 'admin-template.php';
}

function sra_register_settings() {
	
	// Missing
	register_setting( 'simple-rss-aggregator-options', 'sra_sync', 'intval' );
	
	// Ok
	register_setting( 'simple-rss-aggregator-options', 'sra_filter_content', 'intval' );
	register_setting( 'simple-rss-aggregator-options', 'sra_link', 'intval' );
	register_setting( 'simple-rss-aggregator-options', 'sra_user_field', 'intval' );
	register_setting( 'simple-rss-aggregator-options', 'sra_show_posts_at_home', 'intval' );
	register_setting( 'simple-rss-aggregator-options', 'sra_time_to_update', 'intval' );
	register_setting( 'simple-rss-aggregator-options', 'sra_terms', 'intval' );
	register_setting( 'simple-rss-aggregator-options', 'sra_items', 'intval' );
	register_setting( 'simple-rss-aggregator-options', 'sra_thumbnail', 'intval' );
}
