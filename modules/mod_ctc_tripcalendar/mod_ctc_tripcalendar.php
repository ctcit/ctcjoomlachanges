<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_ctc_tripcalendar
 *
 * @copyright   (C) 2009 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;
use Joomla\Module\CtcTripCalendar\Site\Helper\CtcTripCalendarHelper;
use Joomla\CMS\Factory;

CtcTripCalendarHelper::loadTrips($params);
$social = CtcTripCalendarHelper::$Social;
$trips = CtcTripCalendarHelper::$Trips;
$publicTripCalendarUrl = $params->get('publicTripCalendarUrl');
$publicSocialCalendarUrl = $params->get('publicSocialCalendarUrl');
$tripSignupUrl = $params->get('tripSignupUrl');
$tripSignupTripPath = $params->get('tripSignupTripPath');
$overdueTripsUrl = CtcTripCalendarHelper::overdueTripsUrl($params);

$user = Factory::getUser();
$isMember = $user->guest == 0;

require ModuleHelper::getLayoutPath('mod_ctc_tripcalendar', $params->get('layout', 'default'));
