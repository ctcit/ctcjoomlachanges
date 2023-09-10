<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_ctc_welcome
 *
 * @copyright   (C) 2022 Christchurch Tramping Club
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Factory;

$user = Factory::getUser();
$isMember = $user->guest == 0;

require ModuleHelper::getLayoutPath('mod_ctc_welcome', $params->get('layout', 'default'));
