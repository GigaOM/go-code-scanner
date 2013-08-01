<?php

/**
 * singleton function
 */
function go_code_scanner()
{
	global $go_code_scanner;

	if ( ! isset( $go_code_scanner ) && ! $go_code_scanner )
	{
		$go_code_scanner = new GO_Code_Scanner;
	}//end if

	return $go_code_scanner;
}//end go_code_scanner
