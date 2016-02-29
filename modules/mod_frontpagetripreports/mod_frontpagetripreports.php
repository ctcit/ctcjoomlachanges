<?php
/**
* @version $Id: mod_recenttrips.php 5071 2011-01-04 $
* @package Joomla
* @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_JEXEC') or die( 'Restricted access' );

?>
<table><tr>
<?php
use Joomla\Registry\Registry;
$config = JFactory::getConfig();
$live_site = $config->get('live_site');
echo "<script type=\"text/javascript\" src=\"{$live_site}/db/scripts/iframeResizer/js/iframeResizer.js\"></script>";
$params = new Registry;
$params->loadString($module->params);
$width = $params->get("width");
$height = $params->get("height");
$maxrecent = $params->get("maxrecent");
$maxdays = $params->get("maxdays");
$url = htmlspecialchars($live_site."/tripreports/index.html#/recenttripreports/".$maxrecent ."/".$maxdays);
?>
<script>
    jQuery(document).ready(function(){
        iFrameResize( {log:true} );
    });
</script>
<iframe
    id="tripreports"
    name="recenttripreports"
    <?php
      echo "src=\"$url\" ";
      echo "width = \"$width\" ";
      echo "height = \"$height\" ";
    ?>
    scrolling="no"
    frameborder="1"
    class="tripreport"
</iframe>
<div class="spacer">&nbsp;</div>


