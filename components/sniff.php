#!/usr/bin/php
<?php
/**
 * This sniff file is based on the phpcs pear executable PHP file created by squiz.net
 */

error_reporting( E_ALL | E_STRICT );

require_once __DIR__ . '/external/CodeSniffer/CLI.php';

$phpcs = new PHP_CodeSniffer_CLI();
$phpcs->checkRequirements();

$num_errors = $phpcs->process();

if ( 0 === $num_errors )
{
	exit(0);
}//end if
else
{
	exit(1);
}//end else
