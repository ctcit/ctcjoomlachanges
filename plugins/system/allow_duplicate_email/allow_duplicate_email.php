<?php
	/**
	 * Plugin to allow multiple users to register using the same email address
	 * 
	 * This script overrides the core class in:
	 *	2.5	/libraries/joomla/database/table/user.php 
	 *	3.n	/libraries/joomla/table/user.php 
	 * 
	 * @author      John Phillips
	 * @copyright   Copyright (C) 2015 ravenswood IT Services
	 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
	 *
	 * Inspired by users_same_email plugin for Joomla 2.5
	 * @author      ISS Profesionalia, SL
	 * @copyright   Copyright (C) 2013 ISS Profesionalia, SL
	 */
	// no direct access
	defined('_JEXEC') or die;

	jimport('joomla.version');
	$version = new JVersion();

switch ($version->RELEASE) {
	case '2.5':
		include_once JPATH_ROOT.'/plugins/system/allow_duplicate_email/version/2.5/user.php';
		break;
	case '3.3':
		include_once JPATH_ROOT.'/plugins/system/allow_duplicate_email/version/3.3/user.php';
		break;
	case '3.4':
		include_once JPATH_ROOT.'/plugins/system/allow_duplicate_email/version/3.4/user.php';
		break;
	case '3.5':
		include_once JPATH_ROOT.'/plugins/system/allow_duplicate_email/version/3.5/user.php';
		break;
	default:
		break;
	}