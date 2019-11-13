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

class Ai1wm_Updater {

	/**
	 * Retrieve plugin installer pages from WordPress Plugins API.
	 *
	 * @param  mixed        $result
	 * @param  string       $action
	 * @param  array|object $args
	 * @return mixed
	 */
	public static function plugins_api( $result, $action = null, $args = null ) {
		if ( empty( $args->slug ) ) {
			return $result;
		}

		// Get extensions
		$extensions = Ai1wm_Extensions::get();

		// View details page
		if ( isset( $args->slug ) && isset( $extensions[ $args->slug ] ) && $action === 'plugin_information' ) {

			// Get current updates
			$updates = get_option( AI1WM_UPDATER, array() );

			// Plugin details
			if ( isset( $updates[ $args->slug ] ) && ( $details = $updates[ $args->slug ] ) ) {
				return (object) $details;
			}
		}

		return $result;
	}

	/**
	 * Update WordPress plugin list page.
	 *
	 * @param  object $transient
	 * @return object
	 */
	public static function update_plugins( $transient ) {
		global $wp_version;

		// Get extensions
		$extensions = Ai1wm_Extensions::get();

		// Get current updates
		$updates = get_option( AI1WM_UPDATER, array() );

		// Get extension updates
		foreach ( $updates as $slug => $update ) {
			if ( isset( $extensions[ $slug ] ) && ( $extension = $extensions[ $slug ] ) ) {
				if ( ( $purchase_id = get_option( $extension['key'] ) ) ) {
					if ( version_compare( $extension['version'], $update['version'], '<' ) ) {

						// Get download URL
						if ( $update['slug'] === 'file-extension' ) {
							$download_url = add_query_arg( array( 'siteurl' => get_site_url() ), sprintf( '%s', $update['download_link'] ) );
						} else {
							$download_url = add_query_arg( array( 'siteurl' => get_site_url() ), sprintf( '%s/%s', $update['download_link'], $purchase_id ) );
						}

						// Set plugin details
						$transient->response[ $extension['basename'] ] = (object) array(
							'slug'        => $slug,
							'new_version' => $update['version'],
							'url'         => $update['homepage'],
							'plugin'      => $extension['basename'],
							'package'     => $download_url,
							'tested'      => $wp_version,
							'icons'       => $update['icons'],
						);
					}
				}
			}
		}

		return $transient;
	}

	/**
	 * Check for extension updates
	 *
	 * @return boolean
	 */
	public static function check_for_updates() {
		// Get current updates
		$updates = get_option( AI1WM_UPDATER, array() );

		// Get extension updates
		foreach ( Ai1wm_Extensions::get() as $slug => $extension ) {
			$response = wp_remote_get( $extension['about'], array(
				'timeout' => 15,
				'headers' => array( 'Accept' => 'application/json' ),
			) );

			// Add updates
			if ( ! is_wp_error( $response ) ) {
				if ( ( $response = json_decode( $response['body'], true ) ) ) {
					// Slug is mandatory
					if ( ! isset( $response['slug'] ) ) {
						continue;
					}

					// Version is mandatory
					if ( ! isset( $response['version'] ) ) {
						continue;
					}

					// Homepage is mandatory
					if ( ! isset( $response['homepage'] ) ) {
						continue;
					}

					// Download link is mandatory
					if ( ! isset( $response['download_link'] ) ) {
						continue;
					}

					$updates[ $slug ] = $response;
				}
			}
		}

		return update_option( AI1WM_UPDATER, $updates );
	}

	/**
	 * Add "Check for updates" link
	 *
	 * @param  array  $links The array having default links for the plugin.
	 * @param  string $file  The name of the plugin file.
	 * @return array
	 */
	public static function plugin_row_meta( $links, $file ) {
		$modal_index = 0;

		// Add link for each extension
		foreach ( Ai1wm_Extensions::get() as $slug => $extension ) {
			$modal_index++;

			// Get plugin details
			if ( $file === $extension['basename'] ) {

				// Get updater URL
				$updater_url = add_query_arg( array( 'ai1wm_check_for_updates' => 1, 'ai1wm_nonce' => wp_create_nonce( 'ai1wm_check_for_updates' ) ), network_admin_url( 'plugins.php' ) );

				// Check Purchase ID
				if ( get_option( $extension['key'] ) ) {

					// Add "Check for updates" link
					$links[] = Ai1wm_Template::get_content( 'updater/check', array(
						'url' => $updater_url,
					) );

				} else {

					// Add modal
					$links[] = Ai1wm_Template::get_content( 'updater/modal', array(
						'url'   => $updater_url,
						'modal' => $modal_index,
					) );

				}
			}
		}

		return $links;
	}
}
