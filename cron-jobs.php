<?php

register_activation_hook(__FILE__, 'sra_update_all_cron');
register_deactivation_hook(__FILE__, 'sra_deactivation');

add_action( 'wp', 'sra_update_all_cron' );
add_action( 'sra_update_all_hook', 'sra_update_all' );    
add_filter( 'cron_schedules', 'sra_cron_schedules' ); 

function sra_deactivation() {
	wp_clear_scheduled_hook('sra_update_all_hook');
}

function sra_cron_schedules( $schedules ) {
	// add a custom schedule to the existing set
	$schedules['sra_interval'] = array(
		'interval' => 60 * 60 * intval(get_option('sra_time_to_update', '2')),
		'display' => __('Simple RSS Aggregator interval')
	);
	return $schedules;
}

function sra_update_all_cron() {
	
	// verify event has not been scheduled 
	if ( ! wp_next_scheduled( 'sra_update_all_hook' ) ) {            
		// Schedule to run hourly 
		wp_schedule_event( time(), 'sra_interval', 'sra_update_all_hook' );
		#echo wp_next_scheduled( 'sra_update_all_hook' );
		#echo 1;
	}

}
	 
function sra_update_all() {
	global $wpdb;
	
	$feeds = $wpdb->get_results("SELECT meta_value, user_id FROM $wpdb->usermeta WHERE meta_key = 'user_feed'");
	
	if( $feeds )
		foreach( $feeds as $feed )
			if( $feed->meta_value ) // Check if is a valid url
				sra_update( $feed->meta_value, $feed->user_id );
}
