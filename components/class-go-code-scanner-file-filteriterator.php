<?php

class GO_Code_Scanner_File_FilterIterator extends FilterIterator
{
	/**
	 * constructor
	 */
	public function __construct( $iterator )
	{
		parent::__construct( $iterator );
	}//end __construct

	public function accept()
	{
		$item = $this->current();

		$accept_state = true;

		if ( $item->isDot() )
		{
			$accept_state = false;
		}//end if

		if ( '.' == substr( $item->getFilename(), 0, 1 ) )
		{
			$accept_state = false;
		}//end if

		if ( '.zip' == substr( $item->getFilename(), -4 ) )
		{
			$accept_state = false;
		}//end if

		return $accept_state;
	}//end accept
}//end class
