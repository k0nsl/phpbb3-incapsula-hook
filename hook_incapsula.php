<?php
/**
 * Copyright (C) 2013 by k0nsl (i.am@k0nsl.org)
*/

/**
 * @ignore
 */
if (!defined('IN_PHPBB'))
{
   exit;
}

define('HEADER_NAME','HTTP_INCAP_CLIENT_IP');

/**
 * Our one and only hook
 */
function hook_incapsula(&$hook)
{
try {
	
	//stop process if there is no header
	if (empty($_SERVER[HEADER_NAME])) throw new Exception('No header defined', 1);
	
	//validate header value
	if (function_exists('filter_var')) {
		$ip = filter_var($_SERVER[HEADER_NAME], FILTER_VALIDATE_IP);
		if (false === $ip) throw new Exception('The value is not a valid IP address', 2);
	}
	else {
		$ip = trim($_SERVER[HEADER_NAME]);
		if (false === preg_match('/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/', $ip)) throw new Exception('The value is not a valid IP address', 2);
	}
	
	//At this point the initial IP value is exist and validated
	$_SERVER['REMOTE_ADDR'] = $ip;
} catch (Exception $e) {}
}

/**
 * Initialize our hook
 */

$phpbb_hook->register(array('template', 'display'), 'hook_incapsula');