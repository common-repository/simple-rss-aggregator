<?php

// for developers
function if_is_user_feed( $var1, $var2=null ) {
	if ($GLOBALS['post']->post_type == 'user_feed')
		return $var1;
	else
		return $var2;
}

function add_user_feed_post_type() {
    register_post_type( 'user_feed',
		array(
			'public' => true,
			'taxonomies'=>array('post_tag'),
			'label' => 'User Feeds',
			'supports' => array('title', 'thumbnail', 'excerpt'),
			'rewrite' => array( 'slug' => 'user_feed', 'with_front' => false ),
		 ) );
}

function sra_change_feed_cache( $seconds ) {
  return 3600;	// change the default feed cache recreation period to 1 hours
}

function sra_get_existing_post( $guid ) { 
	global $wpdb;

	return $wpdb->get_var(
		"SELECT guid
		FROM $wpdb->posts
		WHERE guid = '$guid'"
	);
}

function sra_items_insert_post( $items, $user_id ) {
	
	if( !isset($user_id) || !isset($items) )
		return;
	
	// It is to add posts in right order
	$items = array_reverse( $items );

	foreach ( $items as $item ) {                        
		
		#echo $item->get_permalink().'<br />';
		
		// Check if newly fetched item already present in existing feed items, 
		// if not insert it into wp_posts and insert post meta.
		if ( ! sra_get_existing_post( $item->get_permalink() ) ) { 
			
			if( (array)$item->get_categories() ) {
				foreach( $item->get_categories() as $category)
					$terms[]=$category->term;
			}
			
			$feed_item = apply_filters(
				'sra_populate_post_data',
				array(
					'post_author'	=> $user_id,
					'post_title'	=> $item->get_title(),
					'post_content'	=> $item->get_content(),
					'post_status'	=> 'publish',
					'post_type'		=> 'user_feed',
					'guid'			=> $item->get_permalink(),
				),
				$item
			);
			
			// Create and insert post object into the DB                              
			$inserted_ID = wp_insert_post( $feed_item );
			
			// categories
			if( intval(get_option('sra_terms'))==1 ) 
				wp_set_post_terms( $inserted_ID , $terms, 'category' );
			
			// Get thumbnail from image in text and set the post thumbnail
			if( intval(get_option('sra_thumbnail'))==1 ) 
				$thumbnail_id = sra_insert_post_thumbnail( $inserted_ID, $item->get_content() );
			
		} 
	}
	//stop();
} 


function sra_get_feed_items( $feed_url ) {
	
	// Change feed cache lifetime
	add_filter( 'wp_feed_cache_transient_lifetime' , 'sra_change_feed_cache' );
	
	/* Fetch the feed from the soure URL specified */
	$feed = fetch_feed( $feed_url );            
	
	// Remove feed cache functions
	remove_filter( 'wp_feed_cache_transient_lifetime' , 'sra_change_feed_cache' );
	
	if ( !is_wp_error( $feed ) ) {
		
		// Get limit number of config
		$maxitems = get_option('sra_items', '3');
		
		// Figure out how many total items there are, but limit it to 5. 
		$maxitems = $feed->get_item_quantity( $maxitems ); 

		if ( $maxitems == 0 ) { return; }

		// Build an array of all the items, starting with element 0 (first element).
		$items = $feed->get_items( 0, $maxitems );   
		
		#echo 'updated';
		
		return $items;
	}

	else { return; }        
}

function sra_update( $feed_url, $user_id ) {            

	require_once dirname(__FILE__).'/functions-upload_from_url.php';
	
	// URL validation
	if( !is_url($feed_url) )
		return;
	
	// Use the URL field to fetch the feed items for this source
	if( !empty( $feed_url ) )                                
		$items = sra_get_feed_items( $feed_url );                        
	
	// Insert feed items into database
	if ( ! empty( $items ) )
		sra_items_insert_post( $items, $user_id );
	
	wp_reset_postdata(); // Restore the $post global to the current post in the main query      

}       

function sra_insert_post_thumbnail( $post_id, $post_content ) {
	
	# identifica imagens no conteúdo
	preg_match_all('/<img[^>]+>/i', $post_content, $images);
	
	# se ouver imagens
	if( isset($images[0][0]) ) {
		
		#identifica links das imagens
		preg_match_all('!http://[a-z0-9\-\.\/_\%]+\.(?:jpe?g|png|gif)!Ui', $post_content , $links);
		
		#checa se a imagem pertence ao site
		$pos = strpos($links[0][0], home_url());
		
		# Eh uma imagem do prório site
		if($pos !== false && $pos==0) {
			
			# pega ID da imagem
			ereg('wp-image-[0-9]*', $post_content, $array);
			$image_id = (int)str_replace('wp-image-', '', $array[0]);
			
			# procura imagem no banco de dados pelo ID (talvez ocorra alguns bugs aqui)
			if($image = wp_get_attachment_image_src($image_id, $size, false)) {
				$class .= " wp-found";
				
				# Se não estiver vinculado a algum post, o vincula com este
				if( !$image->post_parent )
					wp_update_post(array(
						'ID' => $image_id,
						'post_parent' => $post_id,
					));
			} else {
				# oq fazer ???
			}
			
		# achou uma magem externa
		} else {
			
			#Upa imagem
			
			/* IMPORTANTE: Atualizar aqui, remover @, checar sidebar do single */
			
			require_once dirname(__FILE__).'/functions-upload_from_url.php';
			#echo 'upload aqui!';
			$image_id = @upload_from_url($links[0][0]);
			
			if( $image_id ) {	
				
				# vincula imagem com o post
				wp_update_post(array(
					'ID' => $image_id,
					//'post_excerpt' => $text,
					'post_parent'  => $post_id,
				));
				
				$image = wp_get_attachment_image_src($image_id, $size, false);
				
			} else # se chegou aqui, aconteceu um erro muito bizarro no upload_from_url()
				return 0;
			
		}
		
	} else { # não encontrou imagem no texto
		return false;
	}
	
	# redimensiona thumbnail
	if( $image_id ) {
		
		set_post_thumbnail( $post_id, $image_id );
		
		# checa se foi gerado thumbnail (melhorar forma de checar thumbnails da imagem)
		if( get_post_meta( $image_id, 'resized', true ) != 1 ) {
			
			# Thumb resize functions
			require_once(ABSPATH . 'wp-admin/includes/image.php');
			
			# Resize image thumbs
			$metadata = wp_generate_attachment_metadata( $image_id, get_attached_file( $image_id ) );
			wp_update_attachment_metadata( $image_id, $metadata );
			
			# Register event
			update_post_meta( $image_id, 'resized', 1 );
		}
		
		return $image_id;
	}	
}
