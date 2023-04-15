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
<div class="usermenu">
<?php
	if ($user->id == 0)
	{
?>
      <a href="/index.php/log-in" class="login-button"><i class="fa fa-user pr-2" aria-hidden="true"></i>Log In</a>
<?php
	} else {
?>
	<div class="dropdown show">
		<a href="#" class="dropdown-toggle login-button" data-toggle="dropdown" >
		<i class="fa fa-user pr-2 login-user" aria-hidden="true"></i>
		<?php echo $user->name; ?>
		</a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
<?php
	$isChild = false;
	foreach ($list as $i => &$item)
	{
		$itemParams = $item->getParams();
		if ($item->type === 'separator')
		{
			// Don't include separators!
			continue;
		}

		$class = ($isChild) ? 'dropdown-item' : 'nav-item';

		if ($item->id == $default_id)
		{
			$class .= ' default';
		}

		if ($item->id == $active_id || ($item->type === 'alias' && $itemParams->get('aliasoptions') == $active_id))
		{
			$class .= ' current';
		}

		if (in_array($item->id, $path))
		{
			$class .= ' active';
		}
		elseif ($item->type === 'alias')
		{
			$aliasToId = $itemParams->get('aliasoptions');

			if (count($path) > 0 && $aliasToId == $path[count($path) - 1])
			{
				$class .= ' active';
			}
			elseif (in_array($aliasToId, $path))
			{
				$class .= ' alias-parent-active';
			}
		}

		if ($item->deeper)
		{
			$class .= ' deeper';
		}

		if ($item->parent)
		{
			$class .= ' parent dropdown';
		}
		echo '<li class="' . $class . '">';

		if ($item->parent)
		{
			// This is a bit of an ugly hack, but we don't want to create links for category pages
			// just have them act as openers for the dropdown
			echo '<a class="ctc-nav-link nav-link nav-link dropdown-toggle" href="#" id="navbarAbout"
					role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.$item->title.'</a>';
		}
		else
		{
			switch ($item->type) :
				case 'separator':
				case 'component':
				case 'heading':
				case 'url':
					require JModuleHelper::getLayoutPath('mod_menu', 'default_' . $item->type);
					break;

				default:
					require JModuleHelper::getLayoutPath('mod_menu', 'default_url');
					break;
			endswitch;
		}

		// The next item is deeper.
		if ($item->deeper)
		{
			echo '<ul class="dropdown-menu ctc-navbar">';
			$isChild = true;
		}
		// The next item is shallower.
		elseif ($item->shallower)
		{
			echo '</li>';
			echo str_repeat('</ul></li>', $item->level_diff);
			$isChild = false;
		}
		// The next item is on the same level.
		else
		{
			echo '</li>';
		}
	}
	echo '</div>';
}
?>

	</div>
</div>
