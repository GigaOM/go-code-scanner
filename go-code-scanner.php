<?php
/*
Plugin Name: GigaOM Code Scanner
Version: 0.1
Plugin URI: http://gigaom.com
Description: Scan theme/plugin code for GigaOM standards
Author: GigaOM
Author URI: http://gigaom.com
Contributors: borkweb, zbtirrell
Tags: standards
Tested up to: 3.4.1
Stable tag: 3.4
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if( ! class_exists('WP_List_Table') )
{
	include_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}//end if

require_once __DIR__ . '/components/class-go-code-scanner.php';
require_once __DIR__ . '/components/class-go-code-scanner-file-filteriterator.php';
require_once __DIR__ . '/components/class-go-code-scanner-sniff.php';
require_once __DIR__ . '/components/class-go-code-scanner-result-table.php';
require_once __DIR__ . '/components/functions.php';

if ( is_admin() )
{
	go_code_scanner();
}//end if
