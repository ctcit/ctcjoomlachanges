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

if ($isMember) {
  ?>
<h1 class="home-h1">Welcome <?=$user->name;?></h1>
  <a href="" class="btn btn-outline-warning home-btn"><i class="fas fa-hiking home-btn-icon"></i><br>Trip Signup</a>
  <a href="" class="btn btn-outline-warning home-btn"><i class="fas fa-edit home-btn-icon"></i><br>Create Trip Report</a>
  <a href="" class="btn btn-outline-warning home-btn"><i class="fas fa-calendar home-btn-icon"></i><br>Hut Booking Calendar</a>
  <a href="" class="btn btn-outline-warning home-btn"><i class="fas fa-user home-btn-icon"></i><br>Your CTC Account</a>
  <a href="" class="btn btn-outline-warning home-btn"><i class="fas fa-key home-btn-icon"></i><br>Change Password</a>
  <a href="" class="btn btn-outline-warning home-btn"><i class="fas fa-campground home-btn-icon"></i><br>Gear Hire</a>

<ul>
  <!--<li><a href="">Trip Signup <i class="fas fa-hiking"></i></a></li>
  <li><a href="">Create Trip Report <i class="fas fa-edit"></i></a></li>
  <li><a href="">Hut Booking Calendar <i class="fas fa-calendar"></i></a></li>
  <li><a href="">Your CTC Account <i class="fas fa-user"></i></a></li>
  <li><a href="">Change Password <i class="fas fa-key"></i></a></li>-->
</ul>
<?php

} else {
  ?>
<h1 class="home-h1">Welcome to the CTC</h1>
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
      <a href="index.php/join-us" class="ctc-button btn btn-primary px-5 m-2" role="button">Join Us</a>
    </div>
</div>

<?php }
?>
