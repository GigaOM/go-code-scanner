<?php

class GO_Code_Scanner_Result_Table extends WP_List_Table
{
	public $results = array();

	/**
	 * constructor
	 */
	public function __construct( $results )
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

		parent::__construct( array(
			'singular' => 'file scan result',
			'plural' => 'file scan results',
			'ajax' => false,
		));
	}//end __construct

	/**
	 * failover column function.  If a column output isn't defined, this will dump the contents
	 */
	public function column_default( $item, $column_name )
	{
		return $column_name . ': ' .print_r( $item, TRUE );
	}//end column_default

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

		usort( $data, function( $a, $b ) {
			$orderby = isset( $_REQUEST['orderby'] ) && $_REQUEST['orderby'] ? $_REQUEST['orderby'] : 'type';
			$order   = isset( $_REQUEST['order'] ) && $_REQUEST['order'] ? $_REQUEST['order'] : 'asc';
			$result  = strcmp( $a[ $orderby ], $b[ $orderby ] );
			return 'asc' === $order ? $result : -$result;
		});

		$this->items = $data;
	}//end prepare_items
}//end class
