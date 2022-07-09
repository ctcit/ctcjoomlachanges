<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_ctc_welcome
 *
 * @copyright   (C) 2022 Christchurch Tramping Club
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Module\TripCalendar\Site\Helper;

\defined('_JEXEC') or die;

use DateTimeImmutable;
use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;
use Joomla\Component\Content\Site\Helper\RouteHelper;

/**
 * Helper for mod_menu
 *
 * @since  1.5
 */
class TripCalendarHelper
{
	private const MonthFormat = 'F';

	public static $Trips = [];
	public static $Social = null;

	public static function loadTrips(&$params)
	{
		$app   = Factory::getApplication();
		$collection_name = 'trips';
		$request_url = $params->get('apiUrl') . '/' . $collection_name;
		$curl = curl_init($request_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, [
		#'API-Key: 7G0I3BSHPDXV69QWX1IWK4PR3WRJ6KAC',
		'Content-Type: application/json'
		]);
		$response = curl_exec($curl);
		curl_close($curl);
		$trips = json_decode($response);

		// Now we want to group by month
		$now = new DateTimeImmutable();
		$firstMonth = $now->modify("first day of");
		$lastMonth = $firstMonth->modify("+".($params->get('monthsToShow')-1)." month");
		$currentMonth = $firstMonth;
		$sortedTrips = [];
		$currentMonthsTrips = [];
		$tripCount = 0;
		foreach($trips as $trip) {
			$date = DateTimeImmutable::createFromFormat('Y-m-d', $trip->tripDate);
			$trip->tripDate = $date;
			if ($date < $now) {
				continue;
			}
			if ($trip->isSocial) {
				if (TripCalendarHelper::$Social == null) {
					TripCalendarHelper::$Social = $trip;
				}
			} else if ($tripCount < $params->get('maximumTrips')) {
				$trip->type = TripCalendarHelper::tripType(($trip->length));
				if ($date->format('Y-m') == $currentMonth->format('Y-m')) {
					$currentMonthsTrips[] = $trip;
				} else if ($date->format('Y-m') <= $lastMonth->format('Y-m')) {
					$sortedTrips[$currentMonth->format(TripCalendarHelper::MonthFormat)] = $currentMonthsTrips;
					$currentMonth = $currentMonth->modify("+1 month");
					$currentMonthsTrips = [];
					$currentMonthsTrips[] = $trip;
				} else {
					$sortedTrips[$currentMonth->format(TripCalendarHelper::MonthFormat)] = $currentMonthsTrips;
					break;
				}
				$tripCount++;
			}
		}
		$sortedTrips[$currentMonth->format(TripCalendarHelper::MonthFormat)] = $currentMonthsTrips;
		TripCalendarHelper::$Trips = $sortedTrips;
	}

	public static function overdueTripsUrl(&$params) : string
	{
		return TripCalendarHelper::linkToArticle($params->get('overdueArticle'));
	}

	public static function publicTripCalendarUrl(&$params) : string
	{
		// Really, these need to be links to menu items
		return TripCalendarHelper::linkToArticle($params->get('publicTripCalendarArticle'));
	}

	public static function publicSocialCalendarUrl(&$params) : string
	{
		// Really, these need to be links to menu items
		return TripCalendarHelper::linkToArticle($params->get('publicSocialCalendarArticle'));
	}

	private static function tripType($length) : string
	{
		if ($length == 1) {
			return "Day";
		} else if ($length == 2) {
			return "Weekend";
		} else {
			return "Multiday";
		}
	}

	private static function linkToArticle($id) : string
	{
		$url =  Route::_(RouteHelper::getArticleRoute(
			$id,
		));
		return $url;
	}
}
