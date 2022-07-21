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

?>

<a href="<?php echo $overdueTripsUrl; ?>">
  <button class="ctc-important">
    <i class="fa fa-exclamation-circle" aria-hidden="true"></i> Overdue Trip Procedure
  </button>
</a>
<?php if ($social != null) {
?>
<a href="<?php echo $publicSocialCalendarUrl; ?>" class="calendar-header-link">
  <div class="calendar-header">
    <div class="more-events">
      <i class="fa fa-plus" aria-hidden="true"></i> View All
    </div>
    <h2 class="pt-5 pb-1">Next Social</h2>
  </div>
</a>
<div class="event row row-striped">
  <div class="col-3 text-right">
    <span class="event-date badge badge-secondary"><?php echo $social->tripDate->format('j'); ?></span>
    <h2 class="event-day text-uppercase"><?php echo $social->tripDate->format('M'); ?></h2>
  </div>
  <div class="col-9">
    <h3 class="event-title"><?php echo $social->title; ?></h3>
    <ul class="list-inline">
      <li class="list-inline-item"><i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo $social->departureDetails; ?></li>
      <li class="list-inline-item"><i class="fa fa-home" aria-hidden="true"></i> <?php echo $social->departurePoint; ?></li>
    </ul>
  </div>
</div>
<?php
}?>

<a href="<?php echo ($isMember) ? $tripSignupUrl : $publicSocialCalendarUrl; ?>" class="calendar-header-link">
  <div class="calendar-header">
    <div class="more-events">
      <i class="fa fa-plus" aria-hidden="true"></i> View All
    </div>
    <h2 class="pt-5 text-decoration-none">Trip List</h2>
  </div>
</a>

<?php
foreach($trips as $date => $month_trips) {
  echo('<h3 class="calendar-month">'.$date.'</h3>');
  foreach($month_trips as $trip) {
?>
<?php
    if ($isMember) {
      echo "<a href='$tripSignupUrl$tripSignupTripPath/$trip->id' class='event row row-striped'>";
    } else {
      echo "<div class='event row row-striped'>";
    }
?>
    <div class="col-3 text-right">
      <span class="event-date badge badge-secondary"><?php echo $trip->tripDate->format('j') ?></span>
      <h2 class="event-day text-uppercase"><?php echo $trip->tripDate->format('D') ?></h2>
    </div>
    <div class="col-9">
      <h3 class="event-title"><?php echo $trip->title ?></h3>
      <ul class="list-inline">
        <li class="list-inline-item"><i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo $trip->type ?></li>
        <li class="list-inline-item"><i class="fa fa-arrows-v" aria-hidden="true"></i> <?php echo $trip->grade ?></li>
      </ul>
    </div>
    <?php echo $isMember ? "</a>" : "</div>"; ?>
<?php
  }
} ?>