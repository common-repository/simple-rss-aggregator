<?php

add_filter( 'the_content', 'sra_the_content_filter' );
add_filter( 'the_permalink', 'sra_user_permalink', 10, 3 );

// If redirect to external links is on
if( isset($_GET['redirect']) && get_option('sra_link')=='2' ) {
	function sra_redirect() {
		if( wp_verify_nonce( $_GET['redirect'], 'sra_redirect' ) )
			wp_redirect($GLOBALS['post']->guid);
		exit;
	}
	 
	// add our function to template_redirect hook
	add_action('template_redirect', 'sra_redirect');
}

// Show posts at home
if( get_option('sra_show_posts_at_home') ) {
	add_filter( 'pre_get_posts', 'sra_show_posts_at_home' );

	function sra_show_posts_at_home( $query ) {
		
		// Merge post types
		$post_types = (!$query->get('post_type'))? array('post') : (array)$query->get('post_type');
		$post_types = $result = array_merge( $post_types, array('user_feed') );
		
		#print_r($post_types);
		#echo $query->is_main_query();
		#echo '<hr />';
		
		if ( is_home() && $query->is_main_query() )
			$query->set( 'post_type', $post_types );

		return $query;
	}
}

function sra_the_content_filter( $content ) {
  
	if ( is_single() && $GLOBALS['post']->post_type == 'user_feed') {
		
		$content = $GLOBALS['post']->post_content;
		
		// remove tags
		$content = strip_tags($content);
		
		// words limit
		$content = sra_string_limit_words($content, 80);
		
		$content = str_replace( "\n", '<br /><br />', $content );
		
		// remove duplicates
		$content = str_replace( "<br /><br /><br /><br />", '<br /><br />', $content );
		
		// add [...]
		$content .= '<span class="ellipsis">…</span>';
		
		// add paragraph
		$content = "<p>$content</p>";
		
		// add read more button
		$content .= '<a href="'.$GLOBALS['post']->guid.'" class="button" rel="external" target="_blank">'.__('Read more &#8250;').'</a>';
	}
	
    return $content;
    
}

function sra_string_limit_words($string, $word_limit) {
	$words = explode(' ', $string, ($word_limit + 1));
	if(count($words) > $word_limit)
	array_pop($words);
	return implode(' ', $words);
}


function sra_user_permalink($permalink) {
	
    if  ($GLOBALS['post']->post_type == 'user_feed') {
		
		$link_option = get_option('sra_link');
		
		if( $link_option=='1' )
			return $GLOBALS['post']->guid;
			
		elseif( $link_option=='2' )
			return $permalink.'?redirect='.wp_create_nonce( 'sra_redirect' );
			
		#elseif( $link_option=='3' )
 
    }
    return $permalink;
}
