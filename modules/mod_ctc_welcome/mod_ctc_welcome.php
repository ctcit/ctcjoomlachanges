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
use Joomla\Module\TripCalendar\Site\Helper\TripCalendarHelper;
use Joomla\CMS\Factory;

TripCalendarHelper::loadTrips($params);
$social = TripCalendarHelper::$Social;
$trips = TripCalendarHelper::$Trips;
$publicTripCalendarUrl = $params->get('publicTripCalendarUrl');
$publicSocialCalendarUrl = $params->get('publicSocialCalendarUrl');
$tripSignupUrl = $params->get('tripSignupUrl');
$tripSignupTripPath = $params->get('tripSignupTripPath');
$overdueTripsUrl = TripCalendarHelper::overdueTripsUrl($params);

$user = Factory::getUser();
$isMember = $user->guest == 0;

require ModuleHelper::getLayoutPath('mod_ctc_welcome', $params->get('layout', 'default'));
