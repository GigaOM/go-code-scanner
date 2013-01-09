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
		$directory = isset( $_REQUEST['directory'] ) ? trim( $_REQUEST['directory'], ' /' ) : null;

		$directory = str_replace( '../', '', $directory );

		if ( 'POST' == $_SERVER['REQUEST_METHOD'] && $directory )
		{
			$command = 'php ' . $this->sniffer . ' --standard=GigaOM ' . $this->base_sniff_dir . '/' . $directory;
			$results = shell_exec( $command );
		}//end if

		include_once __DIR__ . '/templates/admin.php';
	}//end admin_page

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
