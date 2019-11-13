<?php
/**
 * Copyright (C) 2014-2019 ServMask Inc.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * ███████╗███████╗██████╗ ██╗   ██╗███╗   ███╗ █████╗ ███████╗██╗  ██╗
 * ██╔════╝██╔════╝██╔══██╗██║   ██║████╗ ████║██╔══██╗██╔════╝██║ ██╔╝
 * ███████╗█████╗  ██████╔╝██║   ██║██╔████╔██║███████║███████╗█████╔╝
 * ╚════██║██╔══╝  ██╔══██╗╚██╗ ██╔╝██║╚██╔╝██║██╔══██║╚════██║██╔═██╗
 * ███████║███████╗██║  ██║ ╚████╔╝ ██║ ╚═╝ ██║██║  ██║███████║██║  ██╗
 * ╚══════╝╚══════╝╚═╝  ╚═╝  ╚═══╝  ╚═╝     ╚═╝╚═╝  ╚═╝╚══════╝╚═╝  ╚═╝
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Kangaroos cannot jump here' );
}

class Ai1wm_Import_Controller {

	public static function index() {
		Ai1wm_Template::render( 'import/index' );
	}

	public static function import( $params = array() ) {
		ai1wm_setup_environment();

		// Set params
		if ( empty( $params ) ) {
			$params = stripslashes_deep( array_merge( $_GET, $_POST ) );
		}

		// Set priority
		if ( ! isset( $params['priority'] ) ) {
			$params['priority'] = 10;
		}

		// Set secret key
		$secret_key = null;
		if ( isset( $params['secret_key'] ) ) {
			$secret_key = trim( $params['secret_key'] );
		}

		try {
			// Ensure that unauthorized people cannot access import action
			ai1wm_verify_secret_key( $secret_key );
		} catch ( Ai1wm_Not_Valid_Secret_Key_Exception $e ) {
			exit;
		}

		// Loop over filters
		if ( ( $filters = ai1wm_get_filters( 'ai1wm_import' ) ) ) {
			while ( $hooks = current( $filters ) ) {
				if ( intval( $params['priority'] ) === key( $filters ) ) {
					foreach ( $hooks as $hook ) {
						try {

							// Run function hook
							$params = call_user_func_array( $hook['function'], array( $params ) );

							// Log request
							Ai1wm_Log::import( $params );

						} catch ( Ai1wm_Import_Retry_Exception $e ) {
							if ( defined( 'WP_CLI' ) ) {
								WP_CLI::error( sprintf( __( 'Unable to import. Error code: %s. %s', AI1WM_PLUGIN_NAME ), $e->getCode(), $e->getMessage() ) );
							} else {
								status_header( $e->getCode() );
								echo json_encode( array( 'errors' => array( array( 'code' => $e->getCode(), 'message' => $e->getMessage() ) ) ) );
							}
							exit;
						} catch ( Exception $e ) {
							if ( defined( 'WP_CLI' ) ) {
								WP_CLI::error( sprintf( __( 'Unable to import: %s', AI1WM_PLUGIN_NAME ), $e->getMessage() ) );
							} else {
								Ai1wm_Status::error( __( 'Unable to import', AI1WM_PLUGIN_NAME ), $e->getMessage() );
								Ai1wm_Notification::error( __( 'Unable to import', AI1WM_PLUGIN_NAME ), $e->getMessage() );
							}
							Ai1wm_Directory::delete( ai1wm_storage_path( $params ) );
							exit;
						}
					}

					// Set completed
					$completed = true;
					if ( isset( $params['completed'] ) ) {
						$completed = (bool) $params['completed'];
					}

					// Do request
					if ( $completed === false || ( $next = next( $filters ) ) && ( $params['priority'] = key( $filters ) ) ) {
						if ( defined( 'WP_CLI' ) ) {
							if ( ! defined( 'DOING_CRON' ) ) {
								continue;
							}
						}

						if ( isset( $params['ai1wm_manual_import'] ) || isset( $params['ai1wm_manual_restore'] ) ) {
							echo json_encode( $params );
							exit;
						}

						wp_remote_post( apply_filters( 'ai1wm_http_import_url', admin_url( 'admin-ajax.php?action=ai1wm_import' ) ), array(
							'timeout'   => apply_filters( 'ai1wm_http_import_timeout', 10 ),
							'blocking'  => apply_filters( 'ai1wm_http_import_blocking', false ),
							'sslverify' => apply_filters( 'ai1wm_http_import_sslverify', false ),
							'headers'   => apply_filters( 'ai1wm_http_import_headers', array() ),
							'body'      => apply_filters( 'ai1wm_http_import_body', $params ),
						) );
						exit;
					}
				}

				next( $filters );
			}
		}

		return $params;
	}

	public static function buttons() {
		return array(
			apply_filters( 'ai1wm_import_file', Ai1wm_Template::get_content( 'import/button-file' ) ),
			apply_filters( 'ai1wm_import_url', Ai1wm_Template::get_content( 'import/button-url' ) ),
			apply_filters( 'ai1wm_import_ftp', Ai1wm_Template::get_content( 'import/button-ftp' ) ),
			apply_filters( 'ai1wm_import_dropbox', Ai1wm_Template::get_content( 'import/button-dropbox' ) ),
			apply_filters( 'ai1wm_import_gdrive', Ai1wm_Template::get_content( 'import/button-gdrive' ) ),
			apply_filters( 'ai1wm_import_s3', Ai1wm_Template::get_content( 'import/button-s3' ) ),
			apply_filters( 'ai1wm_import_b2', Ai1wm_Template::get_content( 'import/button-b2' ) ),
			apply_filters( 'ai1wm_import_onedrive', Ai1wm_Template::get_content( 'import/button-onedrive' ) ),
			apply_filters( 'ai1wm_import_box', Ai1wm_Template::get_content( 'import/button-box' ) ),
			apply_filters( 'ai1wm_import_mega', Ai1wm_Template::get_content( 'import/button-mega' ) ),
			apply_filters( 'ai1wm_import_digitalocean', Ai1wm_Template::get_content( 'import/button-digitalocean' ) ),
			apply_filters( 'ai1wm_import_gcloud_storage', Ai1wm_Template::get_content( 'import/button-gcloud-storage' ) ),
			apply_filters( 'ai1wm_import_azure_storage', Ai1wm_Template::get_content( 'import/button-azure-storage' ) ),
			apply_filters( 'ai1wm_import_glacier', Ai1wm_Template::get_content( 'import/button-glacier' ) ),
			apply_filters( 'ai1wm_import_pcloud', Ai1wm_Template::get_content( 'import/button-pcloud' ) ),
			apply_filters( 'ai1wm_import_webdav', Ai1wm_Template::get_content( 'import/button-webdav' ) ),
			apply_filters( 'ai1wm_import_s3_client', Ai1wm_Template::get_content( 'import/button-s3-client' ) ),
		);
	}

	public static function pro() {
		return Ai1wm_Template::get_content( 'import/pro' );
	}

	public static function http_import_headers( $headers = array() ) {
		if ( ( $user = get_option( AI1WM_AUTH_USER ) ) && ( $password = get_option( AI1WM_AUTH_PASSWORD ) ) ) {
			if ( ( $hash = base64_encode( sprintf( '%s:%s', $user, $password ) ) ) ) {
				$headers['Authorization'] = sprintf( 'Basic %s', $hash );
			}
		}

		return $headers;
	}

	public static function max_chunk_size() {
		return min(
			ai1wm_parse_size( ini_get( 'post_max_size' ), AI1WM_MAX_CHUNK_SIZE ),
			ai1wm_parse_size( ini_get( 'upload_max_filesize' ), AI1WM_MAX_CHUNK_SIZE ),
			ai1wm_parse_size( AI1WM_MAX_CHUNK_SIZE )
		);
	}
}
