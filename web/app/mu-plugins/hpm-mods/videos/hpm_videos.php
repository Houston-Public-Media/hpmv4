<?php

if ( ! defined( 'ABSPATH' ) ) exit;
class HPM_Videos {
	protected array $options;
	public function __construct() {
		add_action( 'rest_api_init', [ $this, 'init' ] );
		add_action( 'admin_menu', [ $this, 'create_menu' ] );
		$this->options = get_option( 'hpm_videos', [
			'account_id' => '',
			'playlist_id' => '',
			'policy_key' => '',
			'player_id' => '',
			'paging_limit' => 8
		] );
	}
	public function init(): void {
		// Register WP-REST API endpoints
		register_rest_route( 'hpm-video/v1', '/list', [
			'methods'  => 'GET',
			'callback' => [ $this, 'get_api' ],
			'args'     => [
				'playlist' => [
					'default' => false,
					'sanitize_callback' => 'sanitize_text_field'
				],
				'limit' => [
					'default' => $this->options['paging_limit'],
					'sanitize_callback' => 'absint'
				],
				'offset' => [
					'default' => 0,
					'sanitize_callback' => 'absint'
				]
			],
			'permission_callback' => function() {
				return true;
			}
		] );
	}
	// Get Brightcove playlist to show on home page after local shows block starts here
	public static function get( bool $playlist = false, int $limit = 10, int $offset = 0 ): array {
		$options = get_option( 'hpm_videos', [
			'account_id' => '',
			'playlist_id' => '',
			'policy_key' => '',
			'player_id' => '',
			'paging_limit' => 8
		] );
		if ( empty( $options['account_id'] ) ) {
			return [
				'videos' => [],
				'count' => 0,
				'total' => 0
			];
		}
		$url = "https://edge.api.brightcove.com/playback/v1/accounts/" . $options['account_id'];
		if ( $playlist === false ) {
			$url .= "/videos";
			$transient_key = 'hpm_bc_videos_' . $limit . '_' . $offset;
		} else {
			$url .= "/playlists/" . $options['playlist_id'];
			$transient_key = 'hpm_bc_playlist_' . $limit . '_' . $offset;
		}
		$url .= "?limit={$limit}&offset={$offset}&sort=-created_at";
		$videos = get_transient( $transient_key );
		if ( !empty( $videos ) ) {
			return $videos;
		} else {
			$videos = [
				'videos' => [],
				'count' => 0,
				'total' => 0
			];
		}
		$response = wp_remote_get( $url,
			[
				'headers' => [
					'Accept'       => "application/json;pk=" . $options['policy_key'],
					'User-Agent'   => 'WordPress/' . get_bloginfo('version'),
				],
				'timeout' => 15,
			]
		);
		if ( is_wp_error( $response ) ) {
			print_r( $response->get_error_message() );
			return $videos;
		}
		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );
		if ( !empty( $data['videos'] ) ) {
			foreach ( $data['videos'] as $video ) {
				$temp = [
					'id' => (int)$video['id'],
					'poster' => $video['poster'],
					'thumbnail' => $video['thumbnail'],
					'name' => $video['name'],
					'description' => ( !empty( $video['description'] ) ? $video['description'] : '' ),
					'duration' => $video['duration'],
					'published' => $video['published_at'],
					'source' => '',
					'type' => '',
					'playerUrl' => 'https://players.brightcove.net/' . $options['account_id'] . '/' . $options['player_id'] . '_default/index.html?videoId=' . $video['id']
				];
				$ext_ver = 0;
				$hls = $mp4 = '';
				foreach ( $video['sources'] as $source ) {
					if ( preg_match( '/^https:\/\//', $source['src'] ) ) {
						if ( !empty( $source['type'] ) && $source['type'] == 'application/x-mpegURL' ) {
							if ( $ext_ver < $source['ext_x_version'] ) {
								$ext_ver = $source['ext_x_version'];
								$hls = $source['src'];
							}
						} elseif ( !empty( $source['container'] ) && $source['container'] == 'MP4' ) {
							$mp4 = $source['src'];
						}
					}
				}
				if ( !empty( $hls ) ) {
					$temp['source'] = $hls;
					$temp['type'] = 'hls';
				} elseif ( !empty( $mp4 ) ) {
					$temp['source'] = $mp4;
					$temp['type'] = 'mp4';
				} else {
					continue;
				}
				$videos['videos'][] = $temp;
			}
			$videos['count'] = count( $videos['videos'] );
			if ( empty( $data['count'] ) ) {
				$videos['total'] = count( $videos['videos'] );
			} else {
				$videos['total'] = $data['count'];
			}
		}
		set_transient( $transient_key, $videos, 300 );
		return $videos;
	}

	public function get_api( WP_REST_Request $request ): WP_REST_Response {
		if ( !empty( $request['playlist'] ) && strtolower( $request['playlist'] ) === 'true' ) {
			$message = 'HPM Videos Playlist Output';
			$playlist = true;
		} else {
			$message = 'HPM Videos Output';
			$playlist = false;
		}
		$videos = $this->get( $playlist, $request['limit'], $request['offset'] );
		$videos['status'] = 200;
		return rest_ensure_response( [ 'code' => 'rest_api_success', 'message' => esc_html__( $message, 'hpm-videos' ), 'data' => $videos ] );
	}

	function create_menu(): void {
		add_menu_page(
			esc_html__( 'HPM Brightcove Video Settings', 'hpmv4' ),
			esc_html__( 'BC Videos', 'hpmv4' ),
			'manage_options',
			'hpm-bc-videos',
			[ $this, 'settings_page' ],
			'dashicons-format-video',
			50.887
		);
		add_action( 'admin_init', [ $this, 'register_settings' ] );
	}

	/**
	 * Registers the settings group for HPM Priority
	 */
	public function register_settings(): void {
		register_setting( 'hpm-videos-settings-group', 'hpm_videos' );
	}

	public function settings_page(): void {
		$videos = get_option( 'hpm_videos' );
		if ( empty( $videos ) ) {
			$videos = [
				'account_id' => '',
				'playlist_id' => '',
				'policy_key' => '',
				'player_id' => '',
				'paging_limit' => 8
			];
		} ?>
		<div class="wrap">
			<?php settings_errors(); ?>
			<h1><?php _e('Brightcove Video Settings', 'hpmv4' ); ?></h1>
			<p><?php _e('Use this page to manage your Brightcove Video Settings.', 'hpmv4' ); ?></p>
			<form method="post" action="options.php">
				<?php settings_fields( 'hpm-videos-settings-group' ); ?>
				<?php do_settings_sections( 'hpm-videos-settings-group' ); ?>
				<div id="poststuff">
					<div id="post-body" class="metabox-holder columns-1">
						<div id="post-body-content">
							<div class="meta-box-sortables ui-sortable">
								<div class="postbox">
									<div class="postbox-header"><h2 class="hndle ui-sortable-handle"><?php _e('Options', 'hpmv4' ); ?></h2></div>
									<div class="inside">
										<table class="form-table">
											<tr>
												<th scope="row"><label for="hpm_videos[account_id]"><?php _e('Account ID', 'hpmv4' ); ?></label></th>
												<td><input type="text" name="hpm_videos[account_id]" id="hpm_videos[account_id]" value="<?php echo $videos['account_id']; ?>" class="regular-text" /></td>
											</tr>
											<tr>
												<th scope="row"><label for="hpm_videos[playlist_id]"><?php _e('Playlist ID', 'hpmv4' ); ?></label></th>
												<td><input type="text" name="hpm_videos[playlist_id]" id="hpm_videos[playlist_id]" value="<?php echo $videos['playlist_id']; ?>" class="regular-text" /></td>
											</tr>
											<tr>
												<th scope="row"><label for="hpm_videos[policy_key]"><?php _e('Policy Key', 'hpmv4' ); ?></label></th>
												<td><input type="text" name="hpm_videos[policy_key]" id="hpm_videos[policy_key]" value="<?php echo $videos['policy_key']; ?>" class="regular-text" /></td>
											</tr>
											<tr>
												<th scope="row"><label for="hpm_videos[player_id]"><?php _e('Player ID', 'hpmv4' ); ?></label></th>
												<td><input type="text" name="hpm_videos[player_id]" id="hpm_videos[player_id]" value="<?php echo $videos['player_id']; ?>" class="regular-text" /></td>
											</tr>
											<tr>
												<th scope="row"><label for="hpm_videos[paging_limit]"><?php _e('Default Paging Limit', 'hpmv4' ); ?></label></th>
												<td><input type="number" name="hpm_videos[paging_limit]" id="hpm_videos[paging_limit]" value="<?php echo $videos['paging_limit']; ?>" class="regular-text" /></td>
											</tr>
										</table>
									</div>
								</div>
							</div>
							<?php submit_button(); ?>
							<br class="clear" />
						</div>
					</div>
				</div>
			</form>
		</div>
		<?php
	}
}
new HPM_Videos();