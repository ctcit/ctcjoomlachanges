<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 *
 * @copyright   (C) 2009 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;
use Joomla\Module\CtcUserMenu\Site\Helper\CtcUserMenuHelper;

$list       = CtcUserMenuHelper::getList($params);
$base       = CtcUserMenuHelper::getBase($params);
$active     = CtcUserMenuHelper::getActive($params);
$default    = CtcUserMenuHelper::getDefault();
$active_id  = $active->id;
$default_id = $default->id;
$path       = $base->tree;
$showAll    = $params->get('showAllChildren', 1);
$class_sfx  = htmlspecialchars($params->get('class_sfx', ''), ENT_COMPAT, 'UTF-8');

use Joomla\CMS\Factory;
$user = Factory::getUser();

if (count($list))
{
	require ModuleHelper::getLayoutPath('mod_ctc_user_menu', $params->get('layout', 'default'));
}
