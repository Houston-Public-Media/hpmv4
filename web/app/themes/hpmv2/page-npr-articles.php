<?php
/*
Template Name: NPR Content
*/
	if ( isset( $wp_query->query_vars['npr_id'] ) ) :
		$npr_id = urldecode($wp_query->query_vars['npr_id']);
	endif;
	function search($array, $key, $value)
	{
		$results = array();

		if (is_array($array)) :
			if (isset($array[$key]) && $array[$key] == $value)
				$results[] = $array;

			foreach ($array as $subarray)
				$results = array_merge($results, search($subarray, $key, $value));
		endif;
		return $results;
	}
	$html = file_get_contents("https://api.npr.org/query?id=".$npr_id."&fields=all&output=JSON&apiKey=MDAyMTgwNzc5MDEyMjQ4ODE4MjMyYTExMA001");
	$json = json_decode($html,true);
	$node = $json['list']['story'][0];
	$related_links = $people = $paragraphs = $orgs = $media = $crops = $sound = $keywords = $collection = $members = $multimedia = $keywords_text = array();
	$program = '';
	$npr_link = $title = $node['title']['$text'];
	$id = $node['id'];
	$desc = $node['teaser']['$text'];
	$date = $node['pubDate']['$text'];
	if ( !empty( $node['byline'] ) ) :
		$link_text = ( !empty( $node['byline'][0]['link'][0]['$text'] ) ? $node['byline'][0]['link'][0]['$text'] : '' );
		$api = ( !empty( $node['byline'][0]['link'][1]['$text'] ) ? $node['byline'][0]['link'][1]['$text'] : '' );
		$people = array(
			'name' => ( !empty( $node['byline'][0]['name']['$text'] ) ? $node['byline'][0]['name']['$text'] : 'NPR Staff' ),
			'link' => $link_text, 'api' => $api
		);
	endif;

	if ( !empty( $node['organization'][0] ) ) :
		$orgs = array(
			'name' => $node['organization'][0]['name']['$text'],
			'website' => ( !empty( $node['organization'][0]['website']['$text'] ) ? $node['organization'][0]['website']['$text'] : '' )
		);
	endif;
	if ( !empty( $node['show'][0] ) ) :
		$program = $node['show'][0]['program']['$text'];
	endif;
	if ( !empty( $node['relatedLink'] ) ) :
		foreach ( $node['relatedLink'] as $related ) :
			$related_links[] = array(
				'caption' => $related['caption']['$text'],
				'link' => ( !empty( $related['link'][0]['$text'] ) ? $related['link'][0]['$text'] : '#' )
			);
		endforeach;
	endif;
	foreach ($node['parent'] as $parent) :
		$keywords[] = '<a href="'.$parent['link'][0]['$text'].'" rel="tag">'.$parent['title']['$text'].'</a>';
		$keywords_text[] = $parent['title']['$text'];
	endforeach;
	$npr_tags = implode( ' ', $keywords );
	foreach ($node['textWithHtml']['paragraph'] as $p) :
		$paragraphs[] = $p['$text'];
	endforeach;
	if (!empty($node['audio'])) :
		foreach ($node['audio'] as $audio) :
			if ($audio['type'] == "primary") :
				if (!empty($audio['format']['mp3'])) :
					$mp3 = $audio['format']['mp3'][0]['$text'];
				else :
					$mp3 = '';
				endif;
				if (!empty($audio['format']['mediastream'])) :
					$mediastream = $audio['format']['mediastream']['$text'];
				else :
					$mediastream = '';
				endif;
			endif;
		endforeach;
		$sound = array('mp3' => $mp3,'stream' => $mediastream);
	endif;
	if ( !empty( $node['image'] ) ) :
		foreach ($node['image'] as $img) :
			$photo_id = $img['id'];
			$photo_type = $img['type'];
			$photo_title = $img['title']['$text'];
			$photo_producer = ( !empty( $img['producer']['$text'] ) ? $img['producer']['$text'] : '' );
			$photo_provider = ( !empty( $img['provider']['$text'] ) ? $img['provider']['$text'] : '' );
			$photo_src = $img['src'];
			foreach($img['crop'] as $crop) :
				$crops[$crop['type']] = array(
					'src' => $crop['src'],
					'height' => $crop['height'],
					'width' => $crop['width'],
					'type' => $crop['type']
				);
			endforeach;
			$media[] = array(
				'id' => $photo_id,
				'image_type' => $photo_type,
				'title' => $photo_title,
				'producer' => $photo_producer,
				'provider' => $photo_provider,
				'src' => $photo_src,
				'crops' => $crops
			);
		endforeach;
	endif;
	if (!empty($node['collection'])) :
		$collection = array(
			'type' => $node['collection'][0]['type'],
			'displayType' => ( !empty( $node['collection'][0]['displayType'] ) ? $node['collection'][0]['displayType'] : '' ),
			'title' => ( !empty( $node['collection'][0]['title']['$text'] ) ? $node['collection'][0]['title']['$text'] : '' ),
			'introText' => ( !empty( $node['collection'][0]['introtext']['$text'] ) ? $node['collection'][0]['introtext']['$text'] : '' ),
			'members' => array()
		);
	endif;
	if (!empty($node['multimedia'])) :
		$multimedia = array(
			'title' => $node['multimedia'][0]['title']['$text'],
			'credit' => $node['multimedia'][0]['credit']['$text'],
			'altImageUrl' => $node['multimedia'][0]['altImageUrl']['$text'],
			'format' => array(
				'mp4' => $node['multimedia'][0]['format']['mp4']['$text'],
				'm3u8' => $node['multimedia'][0]['format']['m3u8']['$text'],
				'smil' => $node['multimedia'][0]['format']['smil']['$text']
			)
		);
	endif;
	if ( !empty( $node['member'] ) ) :
		foreach($node['member'] as $member) :
			if (!empty($member['externalAsset'])) :
				$obj = search($node['externalAsset'],'id',$member['externalAsset']['refId']);
				$collection['members'][] = array(
					'title' => $member['title']['$text'],
					'intro_text' => $member['introText']['$text'],
					'url' => $obj[0]['url']['$text']
				);
			elseif (!empty($member['image'])) :
				$collection['members'][] = array(
					'title' => $member['title']['$text'],
					'image_ref' => $member['image']['refId'],
					'image_type' => $member['image']['crop']
				);
			elseif (!empty($member['audio'])) :
				$obj = search($node['audio'],'id',$member['audio']['refId']);
				$obj2 = search($node['product'],'id',$member['ecommerce']['refId']);
				$amazon = ''; $itunes = '';
				foreach ($obj2[0]['purchaseLink'] as $purchase) :
					if (!empty($purchase['$text'])) :
						if ($purchase['vendor'] == 'iTunes') :
							$itunes = $purchase['$text'];
						elseif ($purchase['vendor'] == "Amazon") :
							$amazon = $purchase['$text'];
						endif;
					endif;
				endforeach;
				$collection['members'][] = array(
					'title' => $member['title']['$text'],
					'amazon' => $amazon,
					'itunes' => $itunes,
					'mediastream' => $obj[0]['format']['mediastream']['$text']
				);
			endif;
		endforeach;
	endif;
	$item_array = array(
		'title' => $npr_link,
		'npr_link' => $npr_link,
		'desc' => $desc,
		'id' => $id,
		'date' => $date,
		'byline' => $people,
		'show' => $program,
		'related_links' => $related_links,
		'text' => $paragraphs,
		'organization' => $orgs,
		'media' => $media,
		'audio' => $sound,
		'collection' => $collection,
		'multimedia' => $multimedia
	);

	$primary_image = $other_media = $other_image = $slidepic = $storylist = $audiolist = $videos = $inserts = array();
	$oi_count = 0;
	$interval = '';

	if (!empty($item_array['media'])) :
		$media_count = count($item_array['media']);
		$primary = search($item_array['media'],'image_type','primary');
		if (isset($primary[0]['crops']['standard'])) :
			$primary_image = array(
				'src' => $primary[0]['crops']['standard']['src'],
				'caption' => $primary[0]['title'],
				'provider' => $primary[0]['provider'],
				'producer' => $primary[0]['producer'],
				'thumb' => $primary[0]['src'],
				'width' => $primary[0]['crops']['standard']['width'],
				'height' => $primary[0]['crops']['standard']['height']
			);
		elseif (isset($primary[0]['crops']['custom'])) :
			$primary_image = array(
				'src' => $primary[0]['crops']['custom']['src'],
				'caption' => $primary[0]['title'],
				'provider' => $primary[0]['provider'],
				'producer' => $primary[0]['producer'],
				'thumb' => $primary[0]['src'],
				'width' => $primary[0]['crops']['custom']['width'],
				'height' => $primary[0]['crops']['custom']['height']
			);
		endif;
		if (!empty($item_array['collection']) && $item_array['collection']['displayType'] == 'Slideshow') :
			foreach($item_array['collection']['members'] as $mem) :
				$slidenew = search($item_array['media'],'id',$mem['image_ref']);
				$slidepic[] = array(
					'title' => $mem['title'],
					'producer' => $slidenew[0]['producer'],
					'provider' => $slidenew[0]['provider'],
					'src' => $slidenew[0]['crops'][$mem['image_type']]['src']
				);
			endforeach;
		elseif (!empty($item_array['collection']) && $item_array['collection']['displayType'] == 'Simple Story') :
			foreach($item_array['collection']['members'] as $mem) :
				$storylist[] = array(
					'title' => $mem['title'],
					'intro_text' => $mem['intro_text'],
					'url' => $mem['url']
				);
			endforeach;
		elseif (!empty($item_array['collection']) && $item_array['collection']['displayType'] == 'Music Classic Playlist') :
			foreach($item_array['collection']['members'] as $mem) :
				$audiolist[] = array(
					'title' => $mem['title'],
					'amazon' => $mem['amazon'],
					'itunes' => $mem['itunes'],
					'mediastream' => $mem['mediastream']
				);
			endforeach;
		endif;
	endif;

	$para_count = count($item_array['text']);

	if (!empty($slidepic) || !empty($storylist) || !empty($audiolist) || !empty($item_array['multimedia'])) :
		if ($para_count < 2) :
			$inserts[] = 0;
		else :
			$inserts[] = round($para_count/2,0,PHP_ROUND_HALF_UP);
		endif;
	elseif (!empty($other_image) && empty($slidepic)) :
		$image_count = count($other_image);
		$interval = round($para_count/($image_count+1),0,PHP_ROUND_HALF_UP);
		$l = 0;
		for($c=0;$c<$image_count;$c++) :
			$l = $l+$interval;
			$inserts[] = $l;
		endfor;
	endif;

    $author_info = array('name' => '', 'link' => '');
	if ( !empty( $item_array['byline']['name'] ) ) :
        $author_info['name'] = $item_array['byline']['name'];
    elseif ( !empty( $item_array['organization']['name'] ) ) :
	    $author_info['name'] = $item_array['organization']['name'];
	elseif ( !empty( $item_array['show'] ) ) :
		$author_info['name'] = $item_array['show'];
    else :
        $author_info['name'] = 'NPR';
    endif;

    if ( !empty( $item_array['byline']['link'] ) ) :
        $author_info['link'] = $item_array['byline']['link'];
    elseif ( !empty( $item_array['organization']['website'] ) ) :
        $author_info['link'] = $item_array['organization']['website'];
    else :
        $author_info['link'] = 'http://npr.org';
    endif;

    $author = '<address class="vcard author"><a href="'.$author_info['link'].'" title="Posts by '.$author_info['name'].'" class="author url fn" rel="author">'.$author_info['name'].'</a></address>';

	if (isset($item_array['audio']['mp3'])) :
		$audio = $item_array['audio']['mp3'];
	else :
		$audio = '';
	endif;

	if (!empty($primary_image)) :
		if (!empty($primary_image['provider']) && !empty($primary_image['producer'])) :
			$credit = " // ".$primary_image['provider'].", ".$primary_image['producer'];
		elseif(empty($primary_image['provider']) && !empty($primary_image['produer'])) :
			$credit = " // ".$primary_image['producer'];
		elseif(!empty($primary_image['provider']) && empty($primary_image['producer'])) :
			$credit = " // ".$primary_image['provider'];
		else :
			$credit = '';
		endif;
		$image_caption = $primary_image['caption'].$credit;
	else :
		$image_caption = '';
	endif;


	$headline = $item_array['title'];
	$date = strtotime($item_array['date']);
	$offset = get_option('gmt_offset')*3600;
	$date_offset = $date + $offset;
	if (!empty($primary_image['thumb'])) :
		$thumbnail = $primary_image['thumb'];
	else :
		$thumbnail = '';
	endif;
	if ( !empty( $primary_image ) ) :
		$image_type = pathinfo( $primary_image['src'] );
		$image = array(
			'src' => $primary_image['src'],
			'width' => $primary_image['width'],
			'height' => $primary_image['height'],
			'mime-type' => 'image/'.$image_type['extension']
		);
	else :
		$image = array(
			'src' => 'https://cdn.hpm.io/assets/images/NPR-NEWS.gif',
			'width' => 600,
			'height' => 293,
			'mime-type' => 'image/gif'
		);
	endif;
	$subheading = $item_array['desc'];
	$body_text = '';
	$id = $item_array['id'];

	for ($i=0;$i<count($item_array['text']);$i++) :
		$body_text .= "<p>".$item_array['text'][$i]."</p>";

		if (!empty($inserts) && in_array($i,$inserts)) :
			if (!empty($slidepic)) :
				$body_text .= "<div id=\"amw_galleria_slideshow_1\">";

				foreach($slidepic as $slide) :
					if (!empty($slide['provider']) && !empty($slide['producer'])) :
						$slidecred = $slide['provider'].", ".$slide['producer'];
					elseif (empty($slide['provider']) && !empty($slide['produer'])) :
						$slidecred = $slide['producer'];
					elseif (!empty($slide['provider']) && empty($slide['producer'])) :
						$slidecred = " // ".$slide['provider'];
					else :
						$slidecred = '';
					endif;
					$body_text .= "<a href=\"".$slide['src']."\"><img src=\"".$slide['src']."\" data-title=\"".$slide['title'].$slidecred."\" data-description=\"".$collection['title']."\" /></a>";
				endforeach;
				$body_text .= '</div><script>jQuery(document).ready(function(){ jQuery("#amw_galleria_slideshow_1").galleria({"width":"auto","height":0.76,"autoplay":false,"transition":"slide","initialTransition":"fade","transitionSpeed":0,"_delayTime":4000,"_hideControls":false,"_thumbnailMode":"grid","_captionMode":"on_expand"}); });</script>';
			elseif(!empty($other_image)) :
				if (!empty($other_image[$oi_count]['caption'])) :
					$credit = $other_image[$oi_count]['caption'];
				else :
					$credit = '';
				endif;

				if (!empty($other_image[$oi_count]['provider']) && !empty($other_image[$oi_count]['producer'])) :
					$credit .= " // ".$other_image[$oi_count]['provider'].", ".$other_image[$oi_count]['producer'];
				elseif(empty($other_image[$oi_count]['provider']) && !empty($other_image[$oi_count]['produer'])) :
					$credit .= " // ".$other_image[$oi_count]['producer'];
				elseif(!empty($other_image[$oi_count]['provider']) && empty($other_image[$oi_count]['producer'])) :
					$credit .= " // ".$other_image[$oi_count]['provider'];
				endif;

				$body_text .= "<p class=\"center caption\"><img src=\"".$other_image[$oi_count]['src']."\" alt=\"".str_replace('"','\'',$credit)."\" /><br />".$credit."</p>";

				$oi_count++;
			elseif(!empty($storylist)) :
				$body_text .= '<ul>';
				foreach ($storylist as $sl) :
					$body_text .= "<li><a href=\"".$sl['url']."\" target=\"_blank\">".$sl['title']."</a>".$sl['intro_text']."</li>";
				endforeach;
				$body_text .= '</ul>';
			elseif(!empty($audiolist)) :
				$al_counter = 0;
				if ($para_count < 2) :
					$body_text .= "<p class=\"rclear\"></p>";
				endif;
				foreach ($audiolist as $al) :
					$body_text .= '<div id="jquery_jplayer_'.$al_counter.'" class="jp-jplayer"></div><div id="jp_container_'.$al_counter.'" class="jp-audio" role="application" aria-label="media player"><div class="jp-type-single"><div class="jp-gui jp-interface"> <div class="jp-controls"> <button class="jp-play" role="button" tabindex="0"><span class="fa fa-play" aria-hidden="true"></span></button> <button class="jp-pause" role="button" tabindex="0"><span class="fa fa-pause" aria-hidden="true"></span></button> </div> <div class="jp-progress-wrapper"> <div class="jp-progress"> <div class="jp-seek-bar"> <div class="jp-play-bar"></div> </div> </div> <div class="jp-details"> <div class="jp-title" aria-label="title">&nbsp;</div> </div> <div class="jp-time-holder"> <span class="jp-current-time" role="timer" aria-label="time"></span> / <span class="jp-duration" role="timer" aria-label="duration"></span> </div> <div class="jp-download-link"> <a href="<?php echo $attachment_url; ?>">Download</a> </div> </div> </div> <div class="jp-no-solution"> <span>Update Required</span> To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>. </div> </div> </div><script type="text/javascript"> jQuery(document).ready(function($){$("#jquery_jplayer_'.$al_counter.'").jPlayer({ready: function () {$(this).jPlayer("setMedia", {title: "'.$al['title'].'", rtmpa: "'.$al['mediastream'].'"}); }, swfPath: "https://cdn.hpm.io/assets/js/jplayer", supplied: "mp3", preload: "none", cssSelectorAncestor: "#jp_container_'.$al_counter.'", wmode: "window", useStateClassSkin: true, autoBlur: false, smoothPlayBar: true, keyEnabled: true, remainingDuration: false, toggleDuration: true }); }); </script>';
					$al_counter++;
				endforeach;
			elseif(!empty($item_array['multimedia'])) :
				$body_text .= "<figure class=\"wp-caption alignnone\"><div id=\"npr_multimedia\" class=\"player\"></div><div class=\"media-credit-container alignnone\"><span class=\"media-credit\">".$multimedia['credit']."</span></div></figure><script>flowplayer.conf = { ratio: 9/16, splash: true, analytics: \"UA-3106036-9\", embed: false, wmode: 'transparent' }; flowplayer(\"#npr_multimedia\", { clip: { sources: [ { type: \"application/x-mpegURL\", src: \"".$multimedia['format']['m3u8']."\" } ] }, splash: '".$multimedia['altImageUrl']."' }); </script>";
			endif;
		endif;
	endfor;
	$nprdata = array(
		'title' => $headline,
		'permalink' => 'http://www.houstonpublicmedia.org/npr/'.date( 'Y/m/d/',$date_offset).$id.'/'.sanitize_title( $headline ).'/',
		'excerpt' => $subheading,
		'image' => $image,
		'keywords' => $keywords_text,
		'date' => date( 'c', $date_offset ),
		'author' => $people
	);
get_header(); ?>
	<style>
		article .entry-content .fullattribution img { max-width: 1px; max-height: 1px; }
	</style>
<?php
	if ( !empty( $item_array['multimedia'] ) ) : ?>
	<link rel="stylesheet" href="https://cdn.hpm.io/static/js/flowplayer/skin/functional.css">
	<script src="https://cdn.hpm.io/static/js/flowplayer/flowplayer.min.js"></script>
<?php
	endif; ?>
    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header">
					<h3>NPR</h3>
					<h1 class="entry-title"><?php echo $headline; ?></h1>
					<p><?php echo $subheading; ?></p>
					<div class="byline-date">
					<?PHP
						echo $author." | ";
						$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

						$time_string = sprintf( $time_string,
							esc_attr( date( 'c', $date_offset ) ),
							date('F j, Y, g:i A', $date_offset )
						);

						printf( '<span class="posted-on"><span class="screen-reader-text">%1$s </span>%2$s</span>',
							_x( 'Posted on', 'Used before publish date.', 'hpmv2' ),
							$time_string
						);
					?>
					</div>
				</header><!-- .entry-header -->
				<div class="entry-content">
					<?php
						if (!empty( $audio )) :
							echo do_shortcode( '[audio mp3="'.$audio.'"][/audio]' );
						endif;
						if ( !empty( $image ) ) :
					?>
					<div class="post-thumbnail">
						<img src="<?php echo $image['src']; ?>" class="attachment-large wp-post-image" alt="<?php $image_caption; ?>">
						<?php
							if ( !empty( $image_caption ) ) :
								echo "<p>".$image_caption."</p>";
							endif;
						?>
					</div><!-- .post-thumbnail -->
					<?PHP
						endif;
						echo $body_text;
					?>
					<div id="article-share">
					<?php
						$uri_title = rawurlencode( get_the_title() );
						$facebook_link = rawurlencode( get_the_permalink().'?utm_source=facebook-share-npr&utm_medium=button&utm_campaign=hpm-share-link' );
						$twitter_link = rawurlencode( get_the_permalink().'?utm_source=twitter-share-npr&utm_medium=button&utm_campaign=hpm-share-link' );
						$linkedin_link = rawurlencode( get_the_permalink().'?utm_source=linked-share-npr&utm_medium=button&utm_campaign=hpm-share-link' );
						$uri_excerpt = rawurlencode( get_the_excerpt() ); ?>
						<h4>Share</h4>
						<div class="article-share-icon">
							<a href="https://www.facebook.com/sharer.php?u=<?php echo $facebook_link; ?>" target="_blank" data-dialog="400:368">
								<span class="fa fa-facebook" aria-hidden="true"></span>
							</a>
						</div>
						<div class="article-share-icon">
							 <a href="https://twitter.com/share?text=<?PHP echo $uri_title; ?>&amp;url=<?PHP echo $twitter_link; ?>" target="_blank" data-dialog="364:250">
								<span class="fa fa-twitter" aria-hidden="true"></span>
							</a>
						</div>
						<div class="article-share-icon">
							<a href="mailto:?subject=Someone%20Shared%20an%20Article%20From%20Houston%20Public%20Media%21&body=I%20would%20like%20to%20share%20an%20article%20I%20found%20on%20Houston%20Public%20Media!%0A%0A<?php the_title(); ?>%0A%0A<?php the_permalink(); ?>">
								<span class="fa fa-envelope" aria-hidden="true"></span>
							</a>
						</div>
						<div class="article-share-icon">
							<a href="http://www.linkedin.com/shareArticle?mini=true&source=Houston+Public+Media&summary=<?PHP echo $uri_excerpt; ?>&title=<?PHP echo $uri_title; ?>&url=<?PHP echo $linkedin_link; ?>" target="_blank" data-dialog="600:471">
								<span class="fa fa-linkedin" aria-hidden="true"></span>
							</a>
						</div>
					</div>
				</div><!-- .entry-content -->

				<footer class="entry-footer">
					<p class="screen-reader-text"><span class="tags-links">
						<span class="screen-reader-text">Tags </span>
						<?php echo $npr_tags; ?>
					</p>
				</footer><!-- .entry-footer -->
			</article><!-- #post-## -->
			<aside class="column-right">
			<?php
				if ( !empty( $item_array['related_links'] ) ) : ?>
				<div id="related-posts">
					<h4>Related</h4>
					<ul>
				<?php
					foreach ( $item_array['related_links'] as $related ) : ?>
						<li><h2 class="entry-title"><a href="<?php echo $related['link']; ?>" rel="bookmark" target="_blank"><?PHP echo $related['caption']; ?></a></h2></li>
				<?php
					endforeach; ?>
					</ul>
				</div>
			<?php
				endif;
				get_template_part( 'sidebar', 'none' ); ?>
			</aside>
		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>