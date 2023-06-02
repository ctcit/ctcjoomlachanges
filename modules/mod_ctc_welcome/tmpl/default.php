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


$app    = JFactory::getApplication();
$path   = JURI::base(true).'/templates/'.$app->getTemplate().'/';

if ($isMember) {
  ?>
<h1 class="home-h1">Members Menu</h1>
<!--<h1 class="home-h1">Welcome <?=$user->name;?></h1>-->
  <a href="index.php/trip-signup" class="btn home-btn"><i class="fas fa-hiking home-btn-icon"></i><br>Trip Signup</a>
  <a href="index.php/tramping-info/create-trip-report" class="btn home-btn"><i class="fas fa-edit home-btn-icon"></i><br>Create Trip Report</a>
  <a href="https://docs.google.com/spreadsheets/d/1xzz5pRv9Oj0UXxwI0TAJyfBbfUqAyXWKHKj6HJp8crE/edit#gid=816103837" class="btn home-btn"><i class="fas fa-calendar home-btn-icon"></i><br>Hut Booking Calendar</a>
  <a href="index.php/gear-hire" class="btn home-btn"><i class="fas fa-campground home-btn-icon"></i><br>Gear Hire</a>
  <a href="index.php/user-details" class="btn home-btn"><i class="fas fa-user home-btn-icon"></i><br>Your CTC Account</a>
  <a href="index.php/officialdom" class="btn home-btn"><i class="fas fa-file-alt home-btn-icon"></i><br>Officialdom</a>
<?php

} else {
  ?>
<h1 class="home-h1">
The Christchurch Tramping Club
  <!--<img src="<?php echo $path; ?>/images/logo_darkgreen.png" class="d-none d-md-inline"/>-->
</h1>
<p>The Christchurch Tramping Club (CTC) is one of Christchurch's largest
tramping clubs. We run a range of day and overnight trips most weekends as well
as regular socials. Our trips range from easy (minimal experience
required) to hard (high level of fitness and experience required).
We also organise instruction courses and have a range of equipment for hire</p>
<p> We're a friendly and social
club and always welcome new members, whatever your age, ability or background!</p>
<div class="clearfix">
    <div class="float-right">
      <a href="index.php/about/about-ctc" class="ctc-button-outline btn btn-primary px-5 m-2" role="button">Learn More</a>
      <a href="index.php/join-us" class="ctc-button btn btn-primary px-5 m-2" role="button">Join the Club</a>
    </div>
</div>

<?php }
?>
