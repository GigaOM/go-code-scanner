<?php

class GO_Code_Scanner
{
	public $base_sniff_dir = null;

	/**
	 * constructor
	 */
	public function __construct()
	{
		$this->base_sniff_dir = preg_replace( '!wp-content/.*!', 'wp-content', __DIR__ );

		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}//end __construct

	/**
	 * enqueue scripts and styles
	 */
	public function admin_enqueue_scripts()
	{
		$version = 1;
		wp_register_style( 'go-code-scanner-admin', plugins_url( 'css/go-code-scanner.css', __FILE__ ), array(), $version );

		wp_enqueue_style( 'go-code-scanner-admin' );

		wp_register_script( 'go-code-scanner', plugins_url( 'js/go-code-scanner.js', __FILE__ ), array(), $version, TRUE );
		wp_register_script( 'go-code-scanner-behavior', plugins_url( 'js/go-code-scanner-behavior.js', __FILE__ ), array(), $version, TRUE );

		wp_enqueue_script( 'go-code-scanner' );
		wp_enqueue_script( 'go-code-scanner-behavior' );

		$data = array(
			'type'                                                       => isset( $_REQUEST['type'] )                                                        ? esc_js( $_REQUEST['type'] )                                                        : null,
			'plugin'                                                     => isset( $_REQUEST['plugin'] )                                                      ? esc_js( $_REQUEST['plugin'] )                                                      : null,
			'theme'                                                      => isset( $_REQUEST['theme'] )                                                       ? esc_js( $_REQUEST['theme'] )                                                       : null,
			'vip-theme'                                                  => isset( $_REQUEST['vip-theme'] )                                                   ? esc_js( $_REQUEST['vip-theme'] )                                                   : null,
		);

		if ( isset( $_REQUEST['theme'] ) )
		{
			$data['theme-file-' . sanitize_key( $_REQUEST['theme'] )] = isset( $_REQUEST['theme-file-' . sanitize_key( $_REQUEST['theme'] ) ] ) ? esc_js( $_REQUEST['theme-file-' . sanitize_key( $_REQUEST['theme'] ) ] ) : null;
		}//end if

		if ( isset( $_REQUEST['vip-theme'] ) )
		{
			$data['vip-theme-file-' . sanitize_key( $_REQUEST['vip-theme'] )] = isset( $_REQUEST['vip-theme-file-' . sanitize_key( $_REQUEST['vip-theme'] ) ] ) ? esc_js( $_REQUEST['vip-theme-file-' . sanitize_key( $_REQUEST['vip-theme'] ) ] ) : null;
			$data['vip-theme-plugin-' . sanitize_key( $_REQUEST['vip-theme'] )] = isset( $_REQUEST['vip-theme-plugin-' . sanitize_key( $_REQUEST['vip-theme'] ) ] ) ? esc_js( $_REQUEST['vip-theme-plugin-' . sanitize_key( $_REQUEST['vip-theme'] ) ] ) : null;
		}//end if

		wp_localize_script( 'go-code-scanner', 'go_code_scanner_selection', $data );
	}//end admin_enqueue_scripts

	/**
	 * register administration menus
	 */
	public function admin_menu()
	{
		add_submenu_page( 'tools.php', 'GO Code Scanner', 'GO Code Scanner', 'manage_options', 'go-code-scanner', array( $this, 'admin_page' ) );
	}//end admin_menu

	/**
	 * render the admin page
	 */
	public function admin_page()
	{
		$command       = null;
		$results       = null;
		$directory     = null;
		$show_errors   = 1;
		$show_warnings = 1;

		if ( 'POST' == $_SERVER['REQUEST_METHOD'] )
		{
			$show_errors   = isset( $_POST['show-errors'] ) ? $_POST['show-errors'] : 0;
			$show_warnings = isset( $_POST['show-warnings'] ) ? $_POST['show-warnings'] : 0;

			$type = sanitize_key( $_POST['type'] );

			$sniff = new GO_Code_Scanner_Sniff( 'GigaOM', $type, $_POST );
			$results = $sniff->execute();
		}//end if

		include_once __DIR__ . '/templates/admin.php';
	}//end admin_page

	/**
	 * grab files from a path
	 */
	public function files( $path )
	{
		$dir_path = $this->base_sniff_dir . '/' . $path;

		if ( ! file_exists( $dir_path ) )
		{
			return new EmptyIterator();
		}//end if

		$dir = new DirectoryIterator( $dir_path );

		$files = new GO_Code_Scanner_File_FilterIterator( $dir );
		$all_files = array();

		foreach ( $files as $file )
		{
			$file_data = new stdClass;
			$file_data->name = $file->getFilename();
			$file_data->is_dir = $file->isDir();

			$all_files[ $file_data->name ] = $file_data;
		}//end foreach

		ksort( $all_files );

		return $all_files;
	}//end files
}//end class
