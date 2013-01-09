<div class="wrap go-code-scanner">
	<?php screen_icon('tools'); ?>
	<h2>GigaOM Code Scanner</h2>
	<form method="POST">
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">
						<label for="go-code-scanner-type">What type of file/directory?</label>
					</th>
					<td>
						<select name="type" id="go-code-scanner-type">
							<option value="plugin">Plugin</option>
							<option value="theme-file">Theme File/Directory</option>
							<option value="vip-theme">VIP Theme</option>
							<option value="vip-theme-plugin">VIP Theme Plugin</option>
						</select>
					</td>
				</tr>
				<tr valign="top" class="plugin">
					<th scope="row">
						<label for="go-code-scanner-plugin">Plugin</label>
					</th>
					<td>
						<select name="type" id="go-code-scanner-plugin">
							<?php
							foreach ( $this->files( 'plugins' ) as $file )
							{
								?>
								<option value="<?php echo esc_attr( $file->getFilename() ); ?>">plugins/<?php echo esc_attr( $file->getFilename() ); ?></option>
								<?php
							}//end foreach

							foreach ( $this->files( 'mu-plugins' ) as $file )
							{
								?>
								<option value="<?php echo esc_attr( $file->getFilename() ); ?>">mu-plugins/<?php echo esc_attr( $file->getFilename() ); ?></option>
								<?php
							}//end foreach
							?>
						</select>
					</td>
				</tr>
				<tr valign="top" class="theme-file">
					<th scope="row">
						<label for="go-code-scanner-theme-file">Theme</label>
					</th>
					<td>
						<select name="type" id="go-code-scanner-theme-file">
							<?php
							foreach ( $this->files( 'themes' ) as $file )
							{
								?>
								<option value="<?php echo esc_attr( $file->getFilename() ); ?>"><?php echo esc_attr( $file->getFilename() ); ?></option>
								<?php
							}//end foreach
							?>
						</select>
					</td>
				</tr>
				<tr valign="top" class="vip-theme">
					<th scope="row">
						<label for="go-code-scanner-vip-theme">Theme</label>
					</th>
					<td>
						<select name="type" id="go-code-scanner-vip-theme">
							<?php
							foreach ( $this->files( 'themes/vip' ) as $file )
							{
								?>
								<option value="<?php echo esc_attr( $file->getFilename() ); ?>"><?php echo esc_attr( $file->getFilename() ); ?></option>
								<?php
							}//end foreach
							?>
						</select>
					</td>
				</tr>
				<tr valign="top" class="vip-theme-plugin">
					<th scope="row">
						<label for="go-code-scanner-vip-theme-plugin">Plugin</label>
					</th>
					<td>
						<?php
							foreach ( $this->files( 'themes/vip' ) as $dir )
							{
								if ( ! $dir->isDir() )
								{
									continue;
								}//end if

								if ( 'EmptyIterator' != get_class( $this->files( 'themes/vip/' . $dir->getFilename() . '/plugins' ) ) )
								{
									?>
										<select name="type" id="go-code-scanner-vip-theme-plugin" class="<?php echo sanitize_key( $dir->getFilename() ); ?>">
										<?php
										foreach ( $this->files( 'themes/vip/' . $dir->getFilename() . '/plugins' ) as $file )
										{
											?>
											<option value="<?php echo esc_attr( $file->getFilename() ); ?>"><?php echo esc_attr( $file->getFilename() ); ?></option>
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
						<label for="go-code-scanner-directory">Directory</label>
					</th>
					<td>
						<input type="text" name="directory" id="go-code-scanner-directory" value="<?php echo esc_attr( $directory ); ?>"/>
					</td>
				</tr>
			</tbody>
		</table>
	</form>
	<?php
		if ( $command )
		{
			?>
			<div class="command"><?php echo wp_filter_nohtml_kses( $command ); ?></div>
			<?php
		}//end if
	?>
	<pre>
		<?php echo $results; ?>
	</pre>
</div>
