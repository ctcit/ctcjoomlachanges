<?php
/**
* @version $Id: mod_ctc_recent.php 5071 2011-01-04 $
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
defined( '_JEXEC' ) or die( 'Restricted access' );

$config = JFactory::getConfig();
$live_site = $config->get('live_site');
$database = new mysqli("localhost", $config->get('user'), $config->get('password')) or die("Could not connect: " . mysql_error());
// select our database
$database->select_db("ctcweb9_tripreports") or die($db->error());
// Select a random set of images with roughly 4:3 aspect ratio and shortish captions
$sql = "
SELECT id, caption, name
FROM image
WHERE ABS(3 * t_width - 4 * t_height) < 30
AND LENGTH(caption) < 30
AND LENGTH(caption) > 4
ORDER BY RAND()
LIMIT 3;
";
$result = $database->query($sql) or die("Invalid query: " . $database->error());

echo "<div class='row-fluid'>\n";
while (true){
    $row = $result->fetch_object();
    if (!$row)
        break;
    $imageId = $row->id;
    $caption = stripslashes($row->caption);
    $name = urlencode($row->name);
    $link = "$live_site/dbcaptionedimage.php?id=$imageId";
    echo '<div class="span4 fp-image">';
    echo '<div class="captioned-thumbnail">';
    echo "<a href='$live_site/dbcaptionedimage.php?id=$imageId' target='_blank'>";
    echo "<img class='img-thumbnail' src='$live_site/dbthumb.php?id=$imageId' alt='$name'/>";
    echo "</a>";
    echo "<p class='thumbnail-caption'>$caption</p>"; // Caption is already html-encoded
    echo "</div>\n";
    echo "</div>\n";
}
echo "</div>\n";

echo '<div class="spacer">&nbsp;</div>';

?>


