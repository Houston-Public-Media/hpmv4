<?php
	use Noweh\TwitterApi\Client;

	add_action( 'load-post.php', 'hpm_social_post_setup' );
	add_action( 'load-post-new.php', 'hpm_social_post_setup' );
	function hpm_social_post_setup() {
		add_action( 'add_meta_boxes', 'hpm_social_post_add_meta' );
		add_action( 'save_post', 'hpm_social_post_save_meta', 10, 2 );
	}
	add_action( 'publish_post', 'hpm_social_post_send', 10, 2 );

	function hpm_social_post_add_meta() {
		$user = wp_get_current_user();
		if ( in_array( 'administrator', (array) $user->roles ) || in_array( 'editor', (array) $user->roles ) ) {
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

	function hpm_social_post_meta_box( $object, $box ) {
		wp_nonce_field( basename( __FILE__ ), 'hpm_social_post_class_nonce' );
		$social_post = get_post_meta( $object->ID, 'hpm_social_post', true );
		$social_facebook_sent = get_post_meta( $object->ID, 'hpm_social_facebook_sent', true );
		$social_twitter_sent = get_post_meta( $object->ID, 'hpm_social_twitter_sent', true );
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
		<p><label for="hpm-social-post-twitter"><strong><?php _e( "Twitter", 'hpm-podcasts' ); ?> (<span id="excerpt_counter"></span><?php _e( "/280 character remaining)", 'hpm-podcasts' ); ?></strong></label><?php echo ( $social_twitter_sent == 1 ? '  <span style="font-weight: bolder; font-style: italic; color: red;">This tweet has already been posted</span>' : '' ); ?><br />
		<textarea id="hpm-social-post-twitter" name="hpm-social-post-twitter" placeholder="What would you like to tweet?" style="width: 100%;" rows="2" maxlength="280"><?php echo $social_post['twitter']['data']; ?></textarea></p>
		<p><label for="hpm-social-post-facebook"><strong><?php _e( "Facebook:", 'hpm-podcasts' ); ?></strong></label><?php echo ( $social_facebook_sent == 1 ? '  <span style="font-weight: bolder; font-style: italic; color: red;">This has already been posted to Facebook</span>' : '' ); ?><br />
		<textarea id="hpm-social-post-facebook" name="hpm-social-post-facebook" placeholder="What would you like to post to Facebook?" style="width: 100%;" rows="2"><?php echo $social_post['facebook']['data']; ?></textarea></p>
		<script>
			jQuery(document).ready(function($){
				var desc = $("#hpm-social-post-twitter");
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
		if ( !in_array( 'administrator', (array) $user->roles ) && !in_array( 'editor', (array) $user->roles ) ) {
			return $post_id;
		}
		if ( !isset( $_POST['hpm_social_post_class_nonce'] ) || !wp_verify_nonce( $_POST['hpm_social_post_class_nonce'], basename( __FILE__ ) ) ) {
			return $post_id;
		}

		$post_type = get_post_type_object( $post->post_type );

		if ( !current_user_can( $post_type->cap->edit_post, $post_id ) ) {
			return $post_id;
		}
		if ( empty( $social_post_current ) ) {
			$social_post_current = [
				'twitter' => [
					'data' => ''
				],
				'facebook' => [
					'data' => ''
				]
			];
		}
		if ( empty( $_POST['hpm-social-post-facebook'] ) && empty( $_POST['hpm-social-post-twitter'] ) ) {
			delete_post_meta( $post_id, 'hpm_social_post' );
			delete_post_meta( $post_id, 'hpm_social_facebook_sent' );
			delete_post_meta( $post_id, 'hpm_social_twitter_sent' );
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
	}

	function hpm_social_post_send( $post_id, $post ) {
		$social_post = get_post_meta( $post_id, 'hpm_social_post', true );
		$social_facebook_sent = get_post_meta( $post_id, 'hpm_social_facebook_sent', true );
		$social_twitter_sent = get_post_meta( $post_id, 'hpm_social_twitter_sent', true );
		if ( empty( $social_post ) ) {
			return $post_id;
		}
		if ( empty( $social_twitter_sent ) ) {
			if ( !empty( $social_post['twitter']['data'] ) ) {
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
					$return = $client->tweet()->performRequest( 'POST', [ 'text' => $social_post['twitter']['data'] . ' ' . get_the_permalink( $post_id ) ] );
					update_post_meta( $post_id, 'hpm_social_twitter_sent', 1 );
					log_it( $return );
				} catch (Exception $e) {
					log_it( $e );
				}
			}
		}

		if ( empty( $social_facebook_sent ) ) {
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