<?php

class GO_Code_Scanner_Sniff
{
	public $args = array();
	public $base_sniff_dir = null;
	public $results = array();
	public $sniffer = null;
	public $standards = null;
	public $target = null;
	public $type = null;
	public $types = array(
		'plugin',
		'theme',
		'vip-theme',
		'vip-theme-plugin',
	);

	public function __construct( $standards, $type, $args = array() )
	{
		$this->sniffer = __DIR__ . '/sniff.php';
		$this->base_sniff_dir = preg_replace( '!wp-content/.*!', 'wp-content', __DIR__ );

		$this->standards = $standards;
		$this->type = $type;
		$this->args = $args;

		$this->parse_args();
	}//end __construct

	/**
	 * parse passed arguments into a target
	 */
	public function parse_args()
	{
		$this->target = null;

		switch ( $this->type )
		{
			case 'plugin':
				$this->target = $this->sanitize_filename( $this->args['plugin'] );
			break;
			case 'theme':
				$theme = $this->sanitize_filename( $this->args['theme'] );

				$this->target = 'themes/' . $theme;

				if ( $file = $this->sanitize_filename( $this->args['theme-file-' . $theme] ) )
				{
					$this->target .= '/' . $file;
				}//end if
			break;
			case 'vip-theme':
				$theme = $this->sanitize_filename( $this->args['vip-theme'] );

				$this->target = 'themes/vip/' . $theme;

				if ( $file = $this->sanitize_filename( $this->args['vip-theme-file-' . $theme] ) )
				{
					$this->target .= '/' . $file;
				}//end if
			break;
			case 'vip-theme-plugin':
				$theme = $this->sanitize_filename( $this->args['vip-theme'] );
				$this->target = 'themes/vip/' . $theme . '/plugins/' . $this->sanitize_filename( $this->args['vip-theme-plugin-' . sanitize_key( $theme )] );
			break;
		}//end switch

		// let's remove ../ so submissions can't go up the tree
		$this->target = str_replace( '../', '', $this->target );

		if ( ! $this->target )
		{
			return new WP_Error( 'go-code-scanner-target-generation-failed', 'A target could not be generated from the provided arguments', $_POST );
		}//end if
	}//end parse_args

	/**
	 * make sure the filename is legit
	 */
	public function sanitize_filename( $filename )
	{
		$filename = preg_replace( '/[^a-zA-Z0-9\.\/\-_]/', '', $filename );

		return $filename;
	}//end sanitize_filename

	/**
	 * run the sniff and capture the results
	 */
	public function execute()
	{
		if ( ! $this->target )
		{
			return new WP_Error( 'go-code-scanner-no-target', 'A sniff cannot be executed when no target has been set', array() );
		}//end if

		$command = 'php ' . $this->sniffer;
		$command .= ' --standard=' . escapeshellarg( $this->standards );
		$command .= ' --report=xml';
		$command .= ' ' . escapeshellarg( $this->base_sniff_dir . '/' . $this->target );

		$results = trim( shell_exec( $command ) );

		$doc = new DOMDocument;
		$doc->loadXML( $results );

		if ( ! $doc->getElementsByTagName( 'file' )->length )
		{
			$data = new stdClass;
			$data->errors = 0;
			$data->warnings = 0;
			$data->files = array();

			$file = new stdClass;
			$file->name = preg_replace( '!.*/wp-content/!', '', $this->target );
			$file->errors = 0;
			$file->warnings = 0;
			$file->results = array(
				'message' => array(
					array(
						'type' => 'message',
						'line' => 0,
						'column' => 0,
						'message' => 'The code for ' . wp_filter_nohtml_kses( $this->target ) . ' looks glorious!',
						'severity' => 0,
					),
				),
			);

			$data->files[] = $file;

			return $data;
		}//end if

		return $this->parse_results( $doc );
	}//end execute

	public function parse_results( $doc )
	{
		$data = new stdClass;
		$data->errors = 0;
		$data->warnings = 0;
		$data->files = array();

		$files = $doc->getElementsByTagName( 'file' );
		foreach ( $files as $file )
		{
			if ( ! $file->hasChildNodes() )
			{
				continue;
			}//end if

			$file_data = new stdClass;
			$file_data->errors = 0;
			$file_data->warnings = 0;
			$file_data->results = array();

			foreach ( $file->attributes as $attribute => $value )
			{
				$file_data->$attribute = $value->value;
			}//end foreach

			$file_data->name = preg_replace( '!.*/wp-content/!', '', $file_data->name );
			$data->errors += $file_data->errors;
			$data->warnings += $file_data->warnings;

			$file_key = md5( $file_data->name );

			foreach ( $file->childNodes as $result )
			{
				$result_data = array();

				if ( ! $result->attributes )
				{
					continue;
				}//end if

				foreach ( $result->attributes as $attribute => $value )
				{
					$result_data[ $attribute ] = $value->value;
				}//end foreach

				$result_data['message'] = $result->nodeValue;
				$result_data['type'] = $result->nodeName;

				$file_data->results[ $result->nodeName ][] = $result_data;
			}//end foreach

			$data->files[ $file_key ] = $file_data;
		}//end foreach

		return $data;
	}//end parse_results
}//end class
