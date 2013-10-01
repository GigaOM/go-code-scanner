<div class="wrap go-code-scanner">
	<?php screen_icon('tools'); ?>
	<h2>Gigaom Code Scanner</h2>
	<form method="POST">
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">
						<label for="go-code-scanner-standards">Standards</label>
					</th>
					<td>
						<select name="standards" id="go-code-scanner-standards">
							<option value="">&raquo; Select Standards</option>
							<option value="WordPress" <?php selected( $standards, 'WordPress' ); ?>>WordPress</option>
							<option value="Gigaom" <?php selected( $standards, 'Gigaom' ); ?>>Gigaom</option>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="go-code-scanner-type">What type of file/directory?</label>
					</th>
					<td>
						<select name="type" id="go-code-scanner-type">
							<option value="">&raquo; Select Type</option>
							<option value="plugin">Plugin</option>
							<option value="theme">Theme File/Directory</option>
							<option value="vip-theme">VIP Theme</option>
						</select>
					</td>
				</tr>
				<tr valign="top" class="go-code-scanner-type plugin">
					<th scope="row">
						<label for="go-code-scanner-plugin">Plugin</label>
					</th>
					<td>
						<select name="plugin" id="go-code-scanner-plugin">
							<option value="">&raquo; Select Plugin</option>
							<?php
							foreach ( $this->files( 'plugins' ) as $file )
							{
								?>
								<option value="plugins/<?php echo esc_attr( $file->name ); ?>">plugins/<?php echo esc_attr( $file->name ); ?></option>
								<?php
							}//end foreach

							foreach ( $this->files( 'mu-plugins' ) as $file )
							{
								?>
								<option value="mu-plugins/<?php echo esc_attr( $file->name ); ?>">mu-plugins/<?php echo esc_attr( $file->name ); ?></option>
								<?php
							}//end foreach
							?>
						</select>
					</td>
				</tr>
				<tr valign="top" class="go-code-scanner-type theme">
					<th scope="row">
						<label for="go-code-scanner-theme">Theme</label>
					</th>
					<td>
						<select name="theme" id="go-code-scanner-theme">
							<option value="">&raquo; Select Theme</option>
							<?php
							foreach ( $this->files( 'themes' ) as $file )
							{
								?>
								<option value="<?php echo esc_attr( $file->name ); ?>"><?php echo esc_attr( $file->name ); ?></option>
								<?php
							}//end foreach
							?>
						</select>
					</td>
				</tr>
				<tr valign="top" class="go-code-scanner-type theme-file">
					<th scope="row">
						<label for="go-code-scanner-theme-file">File</label>
					</th>
					<td>
						<p class="note">Select a theme to select a file/dir.</p>
						<?php
						foreach ( $this->files( 'themes' ) as $dir )
						{
							if ( ! $dir->is_dir )
							{
								continue;
							}//end if
							?>
							<select name="theme-file-<?php echo sanitize_key( $dir->name ); ?>" class="file <?php echo sanitize_key( $dir->name ); ?>">
								<option value="">&raquo; Select File</option>
								<?php
								foreach ( $this->files( 'themes/' . $dir->name ) as $file )
								{
									?>
									<option value="<?php echo esc_attr( $file->name ); ?>"><?php echo esc_attr( $file->name ); ?></option>
									<?php
								}//end foreach
								?>
							</select>
							<?php
						}//end foreach
						?>
					</td>
				</tr>
				<tr valign="top" class="go-code-scanner-type vip-theme">
					<th scope="row">
						<label for="go-code-scanner-vip-theme">Theme</label>
					</th>
					<td>
						<select name="vip-theme" id="go-code-scanner-vip-theme">
							<option value="">&raquo; Select Theme</option>
							<?php
							foreach ( $this->files( 'themes/vip' ) as $file )
							{
								?>
								<option value="<?php echo esc_attr( $file->name ); ?>"><?php echo esc_attr( $file->name ); ?></option>
								<?php
							}//end foreach
							?>
						</select>
					</td>
				</tr>
				<tr valign="top" class="go-code-scanner-type vip-theme-file">
					<th scope="row">
						<label for="go-code-scanner-vip-theme-file">File</label>
					</th>
					<td>
						<p class="note">Select a theme to select a file/dir.</p>
						<?php
						foreach ( $this->files( 'themes/vip' ) as $dir )
						{
							if ( ! $dir->is_dir )
							{
								continue;
							}//end if
							?>
							<select name="vip-theme-file-<?php echo sanitize_key( $dir->name ); ?>" class="file <?php echo sanitize_key( $dir->name ); ?>">
								<option value="">&raquo; Select File</option>
								<?php
								foreach ( $this->files( 'themes/vip/' . $dir->name ) as $file )
								{
									?>
									<option value="<?php echo esc_attr( $file->name ); ?>"><?php echo esc_attr( $file->name ); ?></option>
									<?php
								}//end foreach
								?>
							</select>
							<?php
						}//end foreach
						?>
					</td>
				</tr>
				<tr valign="top" class="go-code-scanner-type vip-theme-plugin">
					<th scope="row">
						<label for="go-code-scanner-vip-theme-plugin">Plugin</label>
					</th>
					<td>
						<p class="note">Select a theme with plugins.</p>
						<?php
							foreach ( $this->files( 'themes/vip' ) as $dir )
							{
								if ( ! $dir->is_dir )
								{
									continue;
								}//end if

								if ( is_array( $this->files( 'themes/vip/' . $dir->name . '/plugins' ) ) )
								{
									?>
									<select name="vip-theme-plugin-<?php echo sanitize_key( $dir->name ); ?>" id="go-code-scanner-vip-theme-plugin" class="vip-theme-plugin-selection <?php echo sanitize_key( $dir->name ); ?>">
										<option value="">&raquo; Select Plugin</option>
										<?php
										foreach ( $this->files( 'themes/vip/' . $dir->name . '/plugins' ) as $file )
										{
											?>
											<option value="<?php echo esc_attr( $file->name ); ?>"><?php echo esc_attr( $file->name ); ?></option>
											<?php
										}//end foreach
										?>
									</select>
									<?php
								}//end if
							}//end foreach
						?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="go-code-scanner-type-filter">Show</label>
					</th>
					<td>
						<input type="checkbox" name="show-errors" id="show-errors" value="1" <?php checked( $show_errors ); ?>/> <label for="show-errors">Errors</label>
						<input type="checkbox" name="show-warnings" id="show-warnings" value="1" <?php checked( $show_warnings ); ?>/> <label for="show-warnings">Warnings</label>
					</td>
				</tr>
			</tbody>
		</table>
		<p class="submit">
			<input type="submit" name="submit" class="button button-primary" value="Sniff Code" />
		</p>
	</form>
	<?php
		if ( $command )
		{
			?>
			<div class="command"><?php echo wp_filter_nohtml_kses( $command ); ?></div>
			<?php
		}//end if
	?>

	<?php
	if ( $results )
	{
		?>
		<h3>
			Scanning: <code><?php echo $target; ?></code>
		</h3>
		<table class="wp-list-table widefat summary">
			<tbody>
				<tr valign="middle">
					<th scope="row">
						Test Results:
					</th>
					<td>
						<?php
							if ( $results->errors )
							{
								?><div class="result result-fail">Fail</div><?php
							}//end if
							elseif ( $results->warnings )
							{
								?><div class="result result-sorta">Sorta-Pass</div><?php
							}//end elseif
							else
							{
								?><div class="result result-pass">Pass</div><?php
							}//end else
						?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						Errors:
					</th>
					<td>
						<?php echo intval( $results->errors ); ?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						Warnings:
					</th>
					<td>
						<?php echo intval( $results->warnings ); ?>
					</td>
				</tr>
			</tbody>
		</table>
		<?php

		if ( $results->files && ( $results->errors + $results->warnings ) > 0 )
		{
			foreach ( $results->files as $file )
			{
				?>
				<p class="file-stats">
					(errors: <?php echo $file->errors; ?>, warnings: <?php echo $file->warnings; ?>)
				</p>
				<h3 class="filename">
					File: <code class="path"><?php echo $file->name; ?></code>
				</h3>
				<?php
				// only show the table if there are warnings/errors that we want to see
				if ( ( $file->errors * $show_errors + $file->warnings * $show_warnings ) > 0 )
				{
					$args = array(
						'show-errors'   => $show_errors,
						'show-warnings' => $show_warnings,
					);

					$table = new GO_Code_Scanner_Result_Table( $file->results, $args );
					$table->prepare_items();
					$table->display();
				}//end if
			}//end foreach
		}//end if
	}//end if
	?>
</div>
