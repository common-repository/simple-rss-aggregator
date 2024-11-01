<?php

if( !function_exists('is_url') ):
function is_url( $url ) {
	#$pattern = '/^(([\w]+:)?\/\/)?(([\d\w]|%[a-fA-f\d]{2,2})+(:([\d\w]|%[a-fA-f\d]{2,2})+)?@)?([\d\w][-\d\w]{0,253}[\d\w]\.)+[\w]{2,4}(:[\d]+)?(\/([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)*(\?(&amp;?([-+_~.\d\w]|%[a-fA-f\d]{2,2})=?)*)?(#([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)?$/';
	$pattern = '/^(([\w]+:)?\/\/)?(([\d\w]|%[a-fA-f\d]{2,2})+(:([\d\w]|%[a-fA-f\d]{2,2})+)?@)?([\d\w][-\d\w]{0,253}[\d\w]?\.)+[\w]{2,4}(:[\d]+)?(\/([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)*(\?(&amp;?([-+_~.\d\w]|%[a-fA-f\d]{2,2})=?)*)?(#([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)?$/';
	return preg_match($pattern, $url);
}
endif;

if( !function_exists('upload_from_url') ):
function upload_from_url( $url ) {
	#$url = 'http://img.catho.com.br/site/site2009/btnLoginOk.gif';
	if(ereg('gif|jpg|png', pathinfo($url, PATHINFO_EXTENSION) ))
		if(is_url($url)) {
			
			# UOLHost trick
			ini_set('allow_url_fopen', '1');
			
			if($data=file_get_contents($url)) {
				# Create image from data
				$upload = wp_upload_bits(basename($url), null, $data);
				
				# Get mimetype, replacement for @mime_content_type($upload['file'])
				$mime = wp_check_filetype($upload['file']);
				
				$url_info = parse_url($url);
				
				$attachment = array(
					'post_title'    => basename($url),
					'post_excerpt'  => 'Fonte: '.$url_info['scheme'].'://'.$url_info['host'].'/', # entrar em legenda
					'post_content'  => $url,
					'post_author'   => $user_ID,
					'post_mime_type'=> $mime['type'],
					'guid'          => $upload['url']
				);
				
				$id = wp_insert_attachment( $attachment, $upload['file'] );
				
				if ( !is_wp_error($id) ) {
					require_once(ABSPATH . 'wp-admin/includes/image.php');
					wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $upload['file'] ) ); # gera os thumbs
				}
					
				return $id;

			} else {
				return $error = 'Erro ao acessar arquivo!';
			}

		} else 
			return $error = 'Endereço inválido!';
	else
		return $error = 'Tipo de arquivo inválido!';
}
endif;

?>
