<?php

class GO_Code_Scanner_Result_Table extends WP_List_Table
{
	public $args    = array();
	public $results = array();

	/**
	 * constructor
	 */
	public function __construct( $results, $args = array() )
	{
		$defaults = array(
			'show-errors'   => true,
			'show-warnings' => true,
		);

		$this->args = wp_parse_args( $args, $defaults );

		$this->parse_results( $results );

		parent::__construct( array(
			'singular' => 'file scan result',
			'plural'   => 'file scan results',
			'ajax'     => false,
		));
	}//end __construct

	/**
	 * parse results array into object property
	 *
	 * @param $results array() to parse into object property
	 */
	public function parse_results( $results )
	{
		if ( isset( $results['error'] ) )
		{
			$this->results = array_merge( $this->results, $results['error'] );
		}//end if

		if ( isset( $results['warning'] ) )
		{
			$this->results = array_merge( $this->results, $results['warning'] );
		}//end if

		if ( isset( $results['message'] ) )
		{
			$this->results = array_merge( $this->results, $results['message'] );
		}//end if
	}//end parse_results

	/**
	 * failover column function.  If a column output isn't defined, this will dump the contents
	 *
     * @param $column_name string and $item string
     *
	 * @return $column_name string and $item string to associate with it
	 */
	public function column_default( $item, $column_name )
	{
		return $column_name . ': ' .print_r( $item, TRUE );
	}//end column_default

	/**
	 * retrieve column information
	 *
	 * @return $columns array() of collected columns
	 */
	public function get_columns()
	{
		$columns = array(
			'type' => 'Type',
			'line' => 'Line',
			'column' => 'Column',
			'message' => 'Message',
			'severity' => 'Severity',
		);

		return $columns;
	}//end get_columns

	/**
	 * retrieve sortable columns
	 *
	 * @return $sortable_columns array() of columns for sorting
	 */
	public function get_sortable_columns()
	{
		// @TODO: implement sorting in class-go-code-scanner

		return array();
		$sortable_columns = array(
			'type' => array( 'type', TRUE ),
			'line' => array( 'line', FALSE ),
			'column' => array( 'column', FALSE ),
			'message' => array( 'message', FALSE ),
			'severity' => array( 'severity', FALSE ),
		);

		return $sortable_columns;
	}//end get_sortable_columns

	public function column_type( $item )
	{
		return sprintf(
			'<span class="%s">%s</span>',
			esc_attr( $item['type'] ),
			sanitize_key( $item['type'] )
		);
	}//end column_type

	public function column_line( $item )
	{
		return intval( $item['line'] );
	}//end column_line

	public function column_column( $item )
	{
		return intval( $item['column'] );
	}//end column_column

	public function column_message( $item )
	{
		return stripslashes( wp_filter_nohtml_kses( $item['message'] ) );
	}//end column_message

	public function column_severity( $item )
	{
		return intval( $item['severity'] );
	}//end column_severity

	public function prepare_items()
	{
		$columns  = $this->get_columns();
		$hidden   = array();
		$sortable = $this->get_sortable_columns();

		$this->_column_headers = array(
			$columns,
			$hidden,
			$sortable,
		);

		$data = $this->results;

		// filter out errors and warnings if we need to
		if ( ! $this->args['show-errors'] || ! $this->args['show-warnings'] )
		{
			foreach ( $data as $index => $row )
			{
				if ( ! $this->args['show-errors'] && 'error' == $row['type'] )
				{
					unset( $data[ $index ] );
				}//end if

				if ( ! $this->args['show-warnings'] && 'warning' == $row['type'] )
				{
					unset( $data[ $index ] );
				}//end if
			}//end foreach
		}//end if

		usort( $data, function( $a, $b ) {
			$orderby = isset( $_REQUEST['orderby'] ) && $_REQUEST['orderby'] ? $_REQUEST['orderby'] : 'type';
			$order   = isset( $_REQUEST['order'] ) && $_REQUEST['order'] ? $_REQUEST['order'] : 'asc';
			$result  = strcmp( $a[ $orderby ], $b[ $orderby ] );
			return 'asc' === $order ? $result : -$result;
		});

		$this->items = $data;
	}//end prepare_items
}//end class
