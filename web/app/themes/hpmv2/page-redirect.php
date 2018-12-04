<?php
/*
Template Name: Redirect
*/
	$old_id = get_query_var('hpm_slug', '');
	// $media = get_query_var('hpm_slug_extra', '');
	$epno = get_query_var('hpm_epno', '');
	$hm_old_id = get_query_var('hm_old_id', '');
	if ( !empty( $_GET ) ) :
		if ( !empty( $_GET['q'] ) ) :
			$q = $_GET['q'];
			if ( preg_match( '/^source/', $q ) || preg_match( '/^by/', $q ) ) :
				$q_a = str_replace( array('source:','by','+'),array('','',' '),$q );
				$q_a = sanitize_title($q_a);
				header("HTTP/1.1 301 Moved Permanently");
				header('Location: http://www.houstonpublicmedia.org/articles/author/'.$q_a);
				exit;
			elseif ( preg_match( '/^tag/', $q ) ) :
				$tag = str_replace( array('tag:',' '),array('','-'),$q );
				$tag = sanitize_title($tag);
				header("HTTP/1.1 301 Moved Permanently");
				header('Location: http://www.houstonpublicmedia.org/tag/'.$tag);
				exit;
			elseif ( preg_match( '/^category/', $q ) ) :
				$tag = str_replace( array('category:',' '),array('','-'),$q );
				$tag = sanitize_title($tag);
				header("HTTP/1.1 301 Moved Permanently");
				header('Location: http://www.houstonpublicmedia.org/tag/'.$tag);
				exit;
			else :
				header("HTTP/1.0 404 Not Found - Archive Empty");
				require TEMPLATEPATH.'/404.php';
				exit;
			endif;
		endif;
	elseif ( !empty( $old_id ) ) :
		if ( strlen($old_id) < 5 && is_numeric( $old_id ) ) :
			header("HTTP/1.1 301 Moved Permanently");
			header('Location: http://www.houstonpublicmedia.org/topics/news/page/'.$old_id);
			exit;
		endif;
		if ( $old_id == 1360860053 ) :
			header("HTTP/1.1 301 Moved Permanently");
			header('Location: http://www.houstonpublicmedia.org/preview/');
			exit;
		endif;
		$tendenci = $wpdb->get_results("SELECT `post_id` FROM `wp_postmeta` WHERE `meta_key` = 'old_id' && `meta_value` = '$old_id'",OBJECT);
		if ( !empty( $tendenci ) ) :
			$new_id = get_permalink( $tendenci[0]->post_id );
			header("HTTP/1.1 301 Moved Permanently");
			header('Location: '.$new_id);
			exit;
		else :
			header("HTTP/1.0 404 Not Found - Archive Empty");
			require TEMPLATEPATH.'/404.php';
			exit;
		endif;
	elseif ( !empty( $epno ) ) :
		$engines = $wpdb->get_results("SELECT `post_id` FROM `wp_postmeta` WHERE `meta_key` = 'epno' && `meta_value` = '$epno'",OBJECT);
		if ( !empty( $engines ) ) :
			$new_id = get_permalink( $engines[0]->post_id );
			header("HTTP/1.1 301 Moved Permanently");
			header('Location: '.$new_id);
			exit;
		else :
			header("HTTP/1.0 404 Not Found - Archive Empty");
			require TEMPLATEPATH.'/404.php';
			exit;
		endif;
	elseif ( !empty( $hm_old_id ) ) :
		$hm = $wpdb->get_results("SELECT `post_id` FROM `wp_postmeta` WHERE `meta_key` = 'hm_old_id' && `meta_value` = '$hm_old_id' LIMIT 1",OBJECT);
		if ( !empty( $hm ) ) :
			$new_id = get_permalink( $hm[0]->post_id );
			header("HTTP/1.1 301 Moved Permanently");
			header('Location: '.$new_id);
			exit;
		else :
			header("HTTP/1.0 404 Not Found - Archive Empty");
			require TEMPLATEPATH.'/404.php';
			exit;
		endif;
	else :
		header("HTTP/1.0 404 Not Found - Archive Empty");
		require TEMPLATEPATH.'/404.php';
		exit;
	endif;
?>