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
<ul>
  <li><a href="">Trip Signup</a></li>
  <li><a href="">Edit Member Details</a></li>
  <li><a href="">Change Password</a></li>
</ul>
<?php

} else {
  ?>
<h1 class="home-h1">Welcome to the CTC!</h1>
<p>The Christchurch Tramping Club (CTC) has around 350 members of all ages and runs
tramping trips every weekend. Our trips range from easy (minimal experience
required) to hard (high level of fitness and experience required). We also
organise some instruction courses, have some equipment for hire, and hold
informal weekly meetings and other social events. We're a friendly and social
club and always welcome new members, whatever your age, ability or background!</p>
<div class="clearfix">
    <a href="index.php/join-us" class="ctc-button btn btn-primary px-5 float-right" role="button">Join Us</a>
</div>

<?php }
?>
