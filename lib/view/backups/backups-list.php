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
?>

<?php if ( $backups ) : ?>
	<form action="" method="post" id="ai1wm-backups-form" class="ai1wm-clear">
		<table class="ai1wm-backups">
			<thead>
				<tr>
					<th class="ai1wm-column-name"><?php _e( 'Name', AI1WM_PLUGIN_NAME ); ?></th>
					<th class="ai1wm-column-date"><?php _e( 'Date', AI1WM_PLUGIN_NAME ); ?></th>
					<th class="ai1wm-column-size"><?php _e( 'Size', AI1WM_PLUGIN_NAME ); ?></th>
					<th class="ai1wm-column-actions"></th>
				</tr>
			</thead>
			<tbody>
				<tr class="ai1wm-backups-list-spinner-holder ai1wm-hide">
					<td colspan="4" class="ai1wm-backups-list-spinner">
						<span class="spinner"></span>
						<?php _e( 'Refreshing backup list...', AI1WM_PLUGIN_NAME ); ?>
					</td>
				</tr>

				<?php foreach ( $backups as $backup ) : ?>
				<tr>
					<td class="ai1wm-column-name">
						<?php if ( ! empty( $backup['path'] ) ) : ?>
							<i class="ai1wm-icon-folder"></i>
							<?php echo esc_html( $backup['path'] ); ?>
							<br />
						<?php endif; ?>
						<i class="ai1wm-icon-file-zip"></i>
						<span class="ai1wm-backup-filename">
							<?php echo esc_html( basename( $backup['filename'] ) ); ?>
						</span>
						<span class="ai1wm-backup-label-description ai1wm-hide <?php echo empty( $labels[ $backup['filename'] ] ) ? null : 'ai1wm-backup-label-selected'; ?>">
							<br />
							<?php _e( 'Click to set a label for this backup', AI1WM_PLUGIN_NAME ); ?>
							<i class="ai1wm-icon-edit-pencil ai1wm-hide"></i>
						</span>
						<span class="ai1wm-backup-label-text <?php echo empty( $labels[ $backup['filename'] ] ) ? 'ai1wm-hide' : null; ?>">
							<br />
							<span class="ai1wm-backup-label-colored">
								<?php if ( ! empty( $labels[ $backup['filename'] ] ) ) : ?>
									<?php echo esc_html( $labels[ $backup['filename'] ] ); ?>
								<?php endif; ?>
							</span>
							<i class="ai1wm-icon-edit-pencil ai1wm-hide"></i>
						</span>
						<span class="ai1wm-backup-label-holder ai1wm-hide">
							<br />
							<input type="text" class="ai1wm-backup-label-field" data-archive="<?php echo esc_attr( $backup['filename'] ); ?>" data-value="<?php echo empty( $labels[ $backup['filename'] ] ) ? null : esc_attr( $labels[ $backup['filename'] ] ); ?>" value="<?php echo empty( $labels[ $backup['filename'] ] ) ? null : esc_attr( $labels[ $backup['filename'] ] ); ?>" />
						</span>
					</td>
					<td class="ai1wm-column-date">
						<?php echo esc_html( sprintf( __( '%s ago', AI1WM_PLUGIN_NAME ), human_time_diff( $backup['mtime'] ) ) ); ?>
					</td>
					<td class="ai1wm-column-size">
						<?php if ( ! is_null( $backup['size'] ) ) : ?>
							<?php echo ai1wm_size_format( $backup['size'], 2 ); ?>
						<?php else : ?>
							<?php _e( '2GB+', AI1WM_PLUGIN_NAME ); ?>
						<?php endif; ?>
					</td>
					<td class="ai1wm-column-actions ai1wm-backup-actions">
						<a href="<?php echo esc_url( ai1wm_backup_url( array( 'archive' => $backup['filename'] ) ) ); ?>" class="ai1wm-button-green ai1wm-backup-download" download="<?php echo esc_attr( $backup['filename'] ); ?>" aria-label="<?php _e( 'Download backup', AI1WM_PLUGIN_NAME ); ?>">
							<i class="ai1wm-icon-arrow-down"></i>
							<span><?php _e( 'Download', AI1WM_PLUGIN_NAME ); ?></span>
						</a>
						<a href="#" data-archive="<?php echo esc_attr( $backup['filename'] ); ?>" class="ai1wm-button-gray ai1wm-backup-restore" aria-label="<?php _e( 'Restore backup', AI1WM_PLUGIN_NAME ); ?>">
							<i class="ai1wm-icon-cloud-upload"></i>
							<span><?php _e( 'Restore', AI1WM_PLUGIN_NAME ); ?></span>
						</a>
						<a href="#" data-archive="<?php echo esc_attr( $backup['filename'] ); ?>" class="ai1wm-button-red ai1wm-backup-delete" aria-label="<?php _e( 'Delete backup', AI1WM_PLUGIN_NAME ); ?>">
							<i class="ai1wm-icon-close"></i>
							<span><?php _e( 'Delete', AI1WM_PLUGIN_NAME ); ?></span>
						</a>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<input type="hidden" name="ai1wm_manual_restore" value="1" />
	</form>
<?php endif; ?>
