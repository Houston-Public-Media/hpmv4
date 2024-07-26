<?php
	use Noweh\TwitterApi\Client;

	add_action( 'load-post.php', 'hpm_social_post_setup' );
	add_action( 'load-post-new.php', 'hpm_social_post_setup' );
	function hpm_social_post_setup(): void {
		add_action( 'add_meta_boxes', 'hpm_social_post_add_meta' );
		add_action( 'save_post', 'hpm_social_post_save_meta', 10, 2 );
	}
	add_action( 'publish_post', 'hpm_social_post_send', 10, 2 );

	function hpm_social_post_add_meta():void {
		$user = wp_get_current_user();
		if ( in_array( 'administrator', $user->roles ) || in_array( 'editor', $user->roles ) ) {
			add_meta_box(
				'hpm-social-post-meta-class',
				esc_html__( 'Social Posting', 'example' ),
				'hpm_social_post_meta_box',
				[ 'post' ],
				'normal',
				'high'
			);
		}
	}

	function hpm_social_post_meta_box( $object, $box ): void {
		wp_nonce_field( basename( __FILE__ ), 'hpm_social_post_class_nonce' );
		$social_post = get_post_meta( $object->ID, 'hpm_social_post', true );
		$social_facebook_sent = get_post_meta( $object->ID, 'hpm_social_facebook_sent', true );
		$social_twitter_sent = get_post_meta( $object->ID, 'hpm_social_twitter_sent', true );
		$social_mastodon_sent = get_post_meta( $object->ID, 'hpm_social_mastodon_sent', true );
		$social_bluesky_sent = get_post_meta( $object->ID, 'hpm_social_bluesky_sent', true );
		$twitter_sent = [];
		if ( $social_twitter_sent == 1 ) {
			$twitter_sent[] = 'Twitter/X';
		}
		if ( $social_mastodon_sent == 1 ) {
			$twitter_sent[] = 'Mastodon';
		}
		if ( $social_bluesky_sent == 1 ) {
			$twitter_sent[] = 'Bluesky';
		}
		if ( empty( $social_post ) ) {
			$social_post = [
				'twitter' => [
					'data' => ''
				],
				'facebook' => [
					'data' => ''
				]
			];
		} ?>
		<p><?php _e( "Compose your social posts below. A link to the current article will be appended automatically.", 'hpm-podcasts' ); ?></p>
		<p><label for="hpm-social-post-twitter"><strong><?php _e( "Twitter/Mastodon/Bluesky", 'hpm-podcasts' ); ?> (<span id="excerpt_counter"></span><?php _e( "/280 character remaining)", 'hpm-podcasts' ); ?></strong></label><?php echo ( !empty( $twitter_sent ) ? '  <span style="font-weight: bolder; font-style: italic; color: red;">This tweet has already been posted to: ' . implode( ', ', $twitter_sent ) . '</span>' : '' ); ?><br />
		<textarea id="hpm-social-post-twitter" name="hpm-social-post-twitter" placeholder="What would you like to tweet/toot/skeet?" style="width: 100%;" rows="2" maxlength="280"><?php echo $social_post['twitter']['data']; ?></textarea></p>
		<p><label for="hpm-social-post-facebook"><strong><?php _e( "Facebook:", 'hpm-podcasts' ); ?></strong></label><?php echo ( $social_facebook_sent == 1 ? '  <span style="font-weight: bolder; font-style: italic; color: red;">This has already been posted to Facebook</span>' : '' ); ?><br />
		<textarea id="hpm-social-post-facebook" name="hpm-social-post-facebook" placeholder="What would you like to post to Facebook?" style="width: 100%;" rows="2"><?php echo $social_post['facebook']['data']; ?></textarea></p>
		<script>
			jQuery(document).ready(function($){
				let desc = $("#hpm-social-post-twitter");
				$("span#excerpt_counter").text(desc.val().length);
				desc.keyup( function() {
					if($(this).val().length > 280){
						$(this).val($(this).val().substr(0, 280));
					}
					$("span#excerpt_counter").text($("#hpm-social-post-twitter").val().length);
				});
			});
		</script><?php
	}

	function hpm_social_post_save_meta( $post_id, $post ) {
		$user = wp_get_current_user();
		if ( !in_array( 'administrator', $user->roles ) && !in_array( 'editor', $user->roles ) ) {
			return $post_id;
		}
		if ( !isset( $_POST['hpm_social_post_class_nonce'] ) || !wp_verify_nonce( $_POST['hpm_social_post_class_nonce'], basename( __FILE__ ) ) ) {
			return $post_id;
		}

		$post_type = get_post_type_object( $post->post_type );

		if ( !current_user_can( $post_type->cap->edit_post, $post_id ) ) {
			return $post_id;
		}

		if ( empty( $_POST['hpm-social-post-facebook'] ) && empty( $_POST['hpm-social-post-twitter'] ) ) {
			delete_post_meta( $post_id, 'hpm_social_post' );
			delete_post_meta( $post_id, 'hpm_social_facebook_sent' );
			delete_post_meta( $post_id, 'hpm_social_twitter_sent' );
			delete_post_meta( $post_id, 'hpm_social_mastodon_sent' );
			delete_post_meta( $post_id, 'hpm_social_bluesky_sent' );
		} else {
			$social_post = [
				'facebook' => [
					'data' => sanitize_text_field( $_POST['hpm-social-post-facebook'] )
				],
				'twitter' => [
					'data' => sanitize_text_field( $_POST['hpm-social-post-twitter'] )
				]
			];
			update_post_meta( $post_id, 'hpm_social_post', $social_post );
			if ( $post->post_status == 'publish' ) {
				hpm_social_post_send( $post_id, $post );
			}
		}
		return $post_id;
	}

	function hpm_social_post_send( $post_id, $post ) {
		$social_post = get_post_meta( $post_id, 'hpm_social_post', true );
		$social_facebook_sent = get_post_meta( $post_id, 'hpm_social_facebook_sent', true );
		$social_twitter_sent = get_post_meta( $post_id, 'hpm_social_twitter_sent', true );
		$social_mastodon_sent = get_post_meta( $post_id, 'hpm_social_mastodon_sent', true );
		$social_bluesky_sent = get_post_meta( $post_id, 'hpm_social_bluesky_sent', true );
		if ( empty( $social_post ) ) {
			return $post_id;
		}
		if ( WP_ENV !== 'production' ) {
			return $post_id;
		}
		if ( !empty( $social_post['twitter']['data'] ) ) {
			$cats = get_the_category( $post_id );
			$tags = wp_get_post_tags( $post_id );
			$keywords = [];
			foreach( $cats as $cat ) {
				preg_match_all('/([\w\d]+)/', html_entity_decode( $cat->name ), $match );
				if ( !empty( $match[1] ) ) {
					for ( $v = 0; $v < count( $match[1] ); $v++ ) {
						$match[1][$v] = ucwords( $match[1][$v] );
					}
					$keywords[] = '#' . implode( '', $match[1] );
				}
			}
			foreach( $tags as $tag ) {
				preg_match_all('/([\w\d]+)/', html_entity_decode( $tag->name ), $match );
				if ( !empty( $match[1] ) ) {
					for ( $v = 0; $v < count( $match[1] ); $v++ ) {
						$match[1][$v] = ucwords( $match[1][$v] );
					}
					$keywords[] = '#' . implode( '', $match[1] );
				}
			}
			$keywords = array_unique( $keywords );
			$post_body = $social_post['twitter']['data'] . "\n\n" . get_the_permalink( $post_id );
			$masto_post_body = $post_body . "\n\n" . implode( ' ', $keywords );
			if ( empty( $social_twitter_sent ) && !empty( HPM_TW_BEARER_TOKEN ) ) {
				$account_id = explode( '-', HPM_TW_ACCESS_TOKEN );
				$settings = [
					'account_id' => $account_id[0],
					'consumer_key' => HPM_TW_CONSUMER_KEY,
					'consumer_secret' => HPM_TW_CONSUMER_SECRET,
					'bearer_token' => HPM_TW_BEARER_TOKEN,
					'access_token' => HPM_TW_ACCESS_TOKEN,
					'access_token_secret' => HPM_TW_ACCESS_TOKEN_SECRET
				];
				try {
					$client = new Client( $settings );
					$return = $client->tweet()->create()->performRequest( [ 'text' => $post_body ] );
					update_post_meta( $post_id, 'hpm_social_twitter_sent', 1 );
					log_it( $return );
				} catch (Exception|\GuzzleHttp\Exception\GuzzleException $e) {
					log_it( $e );
				}
			}
			if ( empty( $social_mastodon_sent ) && !empty( HPM_MASTODON_BEARER ) ) {
				$payload = [
					'body' => [
						'status' => $masto_post_body,
						'visibility' => 'public',
						'language' => 'en'
					],
					'headers' => [
						'Authorization' => 'Bearer ' . HPM_MASTODON_BEARER,
						'Idempotency-Key' => microtime()
					],
					'method' => 'POST'
				];
				$url = 'https://mastodon.social/api/v1/statuses';
				$result = wp_remote_post( $url, $payload );
				if ( !is_wp_error( $result ) ) {
					if ( $result['response']['code'] !== 200 ) {
						log_it( json_decode( wp_remote_retrieve_body( $result ) ) );
					} else {
						update_post_meta( $post_id, 'hpm_social_mastodon_sent', 1 );
					}
				}
			}
			if ( empty( $social_bluesky_sent ) && !empty( BSKY_HANDLE ) ) {
				$bsky_options = [
					'headers' => [
						"Content-Type" => "application/json"
					],
					'method' => 'POST',
					'body' => json_encode( [
						'identifier' => BSKY_HANDLE,
						'password' => BSKY_APP_PASSWORD
					] )
				];
				$bsky_urls = [
					'auth' => 'https://bsky.social/xrpc/com.atproto.server.createSession',
					'post' => 'https://bsky.social/xrpc/com.atproto.repo.createRecord',
					'image' => 'https://bsky.social/xrpc/com.atproto.repo.uploadBlob'
				];
				$bsky_embed = [
					"\$type" => 'app.bsky.embed.external',
					"external" => [
						"uri" => get_the_permalink( $post_id ),
						"title" => get_the_title( $post_id ),
						"description" => get_the_excerpt( $post_id )
					]
				];
				$bsky_auth_result = wp_remote_request( $bsky_urls['auth'], $bsky_options );
				if ( !is_wp_error( $bsky_auth_result ) ) {
					if ( $bsky_auth_result['response']['code'] === 200 ) {
						$bsky_auth_body = wp_remote_retrieve_body( $bsky_auth_result );
						if ( $bsky_auth_body ) {
							$bsky_auth = json_decode( $bsky_auth_body );
							if ( !empty( $bsky_auth->accessJwt ) ) {
								$bsky_options['headers']['Authorization'] = "Bearer " . $bsky_auth->accessJwt;
								$thumb_id = get_post_thumbnail_id( $post_id );
								if ( $thumb_id !== false ) {
									$thumb = wp_get_attachment_image_url( $thumb_id, 'medium' );
									$thumb_type = get_post_mime_type( $thumb_id );
									$image_data = file_get_contents( $thumb );
									if ( $image_data !== false ) {
										$bsky_options['body'] = $image_data;
										$bsky_options['headers']['Content-Type'] = $thumb_type;
										$bsky_image_result = wp_remote_request( $bsky_urls['image'], $bsky_options );
										if ( !is_wp_error( $bsky_image_result ) ) {
											if ( $bsky_image_result['response']['code'] === 200 ) {
												$bsky_image_result_body = wp_remote_retrieve_body( $bsky_image_result );
												if ( $bsky_image_result_body ) {
													$bsky_image_json = json_decode( $bsky_image_result_body, true );
													$bsky_embed["external"]["thumb"] = $bsky_image_json['blob'];
												} else {
													log_it( $post_id . ": The Bluesky post request was successful but the body was empty" );
												}
											}
										} else {
											log_it( $bsky_image_result->get_error_message() );
										}
									}
								}
								$bsky_options['body'] = json_encode( [ 'repo' => BSKY_HANDLE, 'collection' => 'app.bsky.feed.post', 'record' => [ 'text' => $social_post['twitter']['data'], 'createdAt' => date( 'c' ), 'embed' => $bsky_embed ] ] );
								$bsky_options['headers']['Content-Type'] = "application/json";
								$bsky_post_result = wp_remote_request( $bsky_urls['post'], $bsky_options );
								if ( !is_wp_error( $bsky_post_result ) ) {
									if ( $bsky_post_result[ 'response' ][ 'code' ] === 200 ) {
										$bsky_post_result_body = wp_remote_retrieve_body( $bsky_post_result );
										if ( $bsky_post_result_body ) {
											update_post_meta( $post_id, 'hpm_social_bluesky_sent', 1 );
										} else {
											log_it( $post_id . ": The Bluesky post request was successful but the body was empty" );
										}
									}
								} else {
									log_it( $bsky_post_result->get_error_message() );
								}
							}
						} else {
							log_it( $post_id . ": The Bluesky authorization request was successful but the body was empty" );
						}
					}
				} else {
					log_it( $bsky_auth_result->get_error_message() );
				}
			}
		}

		if ( empty( $social_facebook_sent ) && !empty( HPM_FB_ACCESS_TOKEN ) ) {
			if ( !empty( $social_post['facebook']['data'] ) ) {
				$url = add_query_arg([
					'message'  => $social_post['facebook']['data'],
					'link' => get_the_permalink( $post_id ),
					'access_token' => HPM_FB_ACCESS_TOKEN,
					'appsecret_proof' => HPM_FB_APPSECRET
				],  'https://graph.facebook.com/' . HPM_FB_PAGE_ID . '/feed' );
				$result = wp_remote_post( $url );
				if ( !is_wp_error( $result ) ) {
					if ( $result['response']['code'] == 200 ) {
						log_it( wp_remote_retrieve_body( $result ) );
						update_post_meta( $post_id, 'hpm_social_facebook_sent', 1 );
					} else {
						log_it( wp_remote_retrieve_body( $result ) );
					}
				}
			}
		}
		return $post_id;
	}