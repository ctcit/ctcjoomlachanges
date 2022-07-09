<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$id = '';

if ($tagId = $params->get('tag_id', ''))
{
	$id = ' id="' . $tagId . '"';
}

// The menu class is deprecated. Use nav instead
?>
<ul class="nav navbar-nav mr-auto ctc-navbar-menu"<?php echo $id; ?>>
<?php
$isChild = false;
foreach ($list as $i => &$item)
{
	if ($item->type === 'separator')
	{
		// Don't include separators!
		continue;
	}

	$class = ($isChild) ? 'dropdown-item ctc-dropdown-item' : 'nav-item';

	if ($item->id == $default_id)
	{
		$class .= ' default';
	}

	if ($item->id == $active_id || ($item->type === 'alias' && $item->params->get('aliasoptions') == $active_id))
	{
		$class .= ' current';
	}

	if (in_array($item->id, $path))
	{
		$class .= ' active';
	}
	elseif ($item->type === 'alias')
	{
		$aliasToId = $item->params->get('aliasoptions');

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
?></ul>
