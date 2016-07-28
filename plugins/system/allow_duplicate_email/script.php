<?php
	/**
	 * Plugin to allow multiple users to register using the same email address
	 * 
	 * This script overrides the core class in /libraries/joomla/database/table/user.php 
	 * 
	 * @author      John Phillips
	 * @copyright   Copyright (C) 2014 ravenswood IT Services
	 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
	 *
	 * Inspired by users_same_email plugin by www.iss-profesionalia.com
	 */
	// no direct access
	defined('_JEXEC') or die;

class plgsystemallow_duplicate_emailInstallerScript
{
function preflight( $type, $parent ) {
 
	// abort if the current Joomla release is older
	if( file_exists(JPATH_ROOT.'/plugins/system/users_same_email' ) ) {
		Jerror::raiseWarning(null, 'Cannot install allow_duplicate_email plugin when users_same_email plugin is already installed');
		return false;
	}
 
}

}