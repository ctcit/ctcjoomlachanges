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
<h2>Shortcuts</h2>
  <div class="row">
    <div class="col-md-6 col-xl-4 col-shortcuts">
      <a href="/trip-signup" class="btn home-btn">
        <i class="fas fa-hiking home-btn-icon"></i> Trip Signup
      </a>
    </div>
    <div class="col-md-6 col-xl-4 col-shortcuts">
    <a href="/members-menu/create-trip-report" class="btn home-btn">
        <i class="fas fa-edit home-btn-icon"></i> Create Trip Report
      </a>
    </div>
    <div class="col-md-6 col-xl-4 col-shortcuts">
    <a href="/hut-booking" class="btn home-btn">
        <i class="fas fa-calendar home-btn-icon"></i> Hut Booking
      </a>
    </div>
    <div class="col-md-6 col-xl-4 col-shortcuts">
    <a href="/members-menu/gear-hire" class="btn home-btn">
        <i class="fas fa-campground home-btn-icon"></i> Gear Hire
      </a>
    </div>
    <div class="col-md-6 col-xl-4 col-shortcuts">
    <a href="/members-menu/officialdom" class="btn home-btn">
        <i class="fas fa-file-alt home-btn-icon"></i> Club Documents
      </a>
    </div>
    <div class="col-md-6 col-xl-4 col-shortcuts">
    <a href="/members-menu/trip-leaders" class="btn home-btn">
        <i class="fa fa-star home-btn-icon"></i> Trip Leader Info
      </a>
    </div>
  </div>
<?php

} else {
  ?>
<h1 class="home-h1">
Christchurch Tramping Club
  <!--<img src="<?php echo $path; ?>/images/logo_darkgreen.png" class="d-none d-md-inline"/>-->
</h1>
<p>Welcome! The Christchurch Tramping Club (CTC) is one of Christchurch's largest
tramping clubs. We run a range of day and overnight trips most weekends as well
as regular socials. Our trips range from easy (minimal experience
required) to hard (high level of fitness and experience required).
We also organise instruction courses and have a range of equipment for hire</p>
<p> We're a friendly and social
club and always welcome new members, whatever your age, ability or background!</p>
<div class="clearfix">
    <div class="float-right">
      <a href="/about/about-ctc" class="ctc-button-outline btn btn-primary px-5 m-2" role="button">Learn More</a>
      <a href="/join-us" class="ctc-button btn btn-primary px-5 m-2" role="button">Join the Club</a>
    </div>
</div>

<?php }
?>
