<?php

class GO_Code_Scanner
{
	public $sniffer = null;
	public $base_sniff_dir = null;

	/**
	 * constructor
	 */
	public function __construct()
	{
		$this->sniffer = __DIR__ . '/sniff.php';
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

		wp_localize_script( 'go-code-scanner', 'go_code_scanner_selection', array(
			'type'                                                     => isset( $_REQUEST['type'] )                                                      ? esc_js( $_REQUEST['type'] )                                                      : null,
			'plugin'                                                   => isset( $_REQUEST['plugin'] )                                                    ? esc_js( $_REQUEST['plugin'] )                                                    : null,
			'theme'                                                    => isset( $_REQUEST['theme'] )                                                     ? esc_js( $_REQUEST['theme'] )                                                     : null,
			'theme-file-' . sanitize_key( $_REQUEST['theme'] )         => isset( $_REQUEST['theme-file-' . sanitize_key( $_REQUEST['theme'] ) ] )         ? esc_js( $_REQUEST['theme-file-' . sanitize_key( $_REQUEST['theme'] ) ] )         : null,
			'vip-theme'                                                => isset( $_REQUEST['vip-theme'] )                                                 ? esc_js( $_REQUEST['vip-theme'] )                                                 : null,
			'vip-theme-file-' . sanitize_key( $_REQUEST['vip-theme'] ) => isset( $_REQUEST['vip-theme-file-' . sanitize_key( $_REQUEST['vip-theme'] ) ] ) ? esc_js( $_REQUEST['vip-theme-file-' . sanitize_key( $_REQUEST['vip-theme'] ) ] ) : null,
			'vip-theme-plugin'                                         => isset( $_REQUEST['vip-theme-plugin'] )                                          ? esc_js( $_REQUEST['vip-theme-plugin'] )                                          : null,
		));
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
		$command = null;
		$results = null;
		$directory = null;

		if ( 'POST' == $_SERVER['REQUEST_METHOD'] )
		{
			$type = sanitize_key( $_POST['type'] );

			switch ( $type )
			{
				case 'plugin':
					$directory = $this->sanitize_filename( $_POST['plugin'] );
				break;
				case 'theme':
					$theme = $this->sanitize_filename( $_POST['theme'] );

					$directory = 'themes/' . $theme;

					if ( $file = $this->sanitize_filename( $_POST['theme-file-' . $theme] ) )
					{
						$directory .= '/' . $file;
					}//end if
				break;
				case 'vip-theme':
					$theme = $this->sanitize_filename( $_POST['vip-theme'] );

					$directory = 'themes/vip/' . $theme;

					if ( $file = $this->sanitize_filename( $_POST['vip-theme-file-' . $theme] ) )
					{
						$directory .= '/' . $file;
					}//end if
				break;
				case 'vip-theme-plugin':
					$theme = $this->sanitize_filename( $_POST['vip-theme'] );
					$directory = 'themes/vip/' . $theme . '/plugins/' . $this->sanitize_filename( $_POST['vip-theme-plugin-' . sanitize_key( $theme )] );
				break;
			}//end switch

			$directory = str_replace( '../', '', $directory );

			if ( $directory )
			{
				$command = 'php ' . $this->sniffer . ' --standard=GigaOM ' . $this->base_sniff_dir . '/' . $directory;
				if ( ! ( $results = shell_exec( $command ) ) )
				{
					$results = 'no-problems';
				}//end if
			}//end if
		}//end if

		include_once __DIR__ . '/templates/admin.php';
	}//end admin_page

	public function sanitize_filename( $filename )
	{
		$filename = preg_replace( '/[^a-zA-Z0-9\.\/\-_]/', '', $filename );

		return $filename;
	}//end sanitize_filename

	public function files( $path )
	{
		$dir_path = $this->base_sniff_dir . '/' . $path;

		if ( ! file_exists( $dir_path ) )
		{
			return new EmptyIterator();
		}//end if

		$dir = new DirectoryIterator( $dir_path );

		return new GO_Code_Scanner_File_FilterIterator( $dir );
	}//end files
}//end class
