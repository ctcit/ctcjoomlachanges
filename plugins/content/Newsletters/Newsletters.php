<?php
/**
* plugin to generate a set of download links to all archived newsletters.
*
* Usage: {mosnewsletters}
* Builds the list from the newsletter directory assuming that the
* First part of the file name (up to the first '.') is the month
* followed by a two-digit year, the last part is the extension and
* the middle part (if given) is extra description, e.g. "noImages".
*/
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

class plgContentNewsletters extends JPlugin
{

    function makeNewsletterIndex($year) {
    	$dirname = "images/newsletters/$year";
    	if (!file_exists($dirname) || !is_dir($dirname)) {
    	 	return "";  // No such directory
     	}
     	$dir = opendir($dirname);
     	$months = "janfebmaraprmayjunjulaugsepoctnovdec";
     	$fullMonths = array("January","February","March","April","May","June","July","August","September","October","November","December");
    	$result = "";
    	
    	// Build a table of $files, where $files[month][type] is a list of all files found
    	// with the first 3 letters matching the reqd month and an extension of .doc for type 0
    	// and .pdf for type 1.
    	$files = array();
    	for ($i = 0; $i < 12; $i++) $files[$i] = array(array(),array());
    	$numFiles = 0;
    	while (($file = readdir($dir)) !== false) {
    		if ($file[0] == '.') continue;
    		$month = strpos($months, strtolower(substr($file,0,3)))/3; // Classify it
    		if ($month === false) continue;
    		$ext = substr($file,-3,3);
    		if ($ext == "doc") $type = 0;
    		else if ($ext == "pdf") $type = 1;
    		else continue;
    		array_push($files[$month][$type], $file);
    		$numFiles++;
    	}
    	if ($numFiles > 0) {
    		$result .= "<h2>$year</h2>"; //<table class=\"newsletterIndex\" border=1 cellpadding=3><tr><th></th><th>.doc</th><th>.pdf</th></tr>";
    		$result .= "<ul>";
    		for ($month = 11; $month >= 0; $month--) {
    			if (count($files[$month][0]) > 0 || count($files[$month][1]) > 0) {
    				for ($type = 1; $type < 2; $type++) { // Ignoring .docs!
    					$n = 0;
    					foreach ($files[$month][$type] as $file) {
    						$path = "images/newsletters/$year/$file";
    						$status = stat($path);
    						$size = round($status['size']/1000);
    						$fileBits = explode(".",$file);
    						$descr = substr($fileBits[0],0,-2);
    						if (count($fileBits) > 2) {
    							$descr .= " ($fileBits[1])";
    						}
    						$descr .= " -- $size kB";
    						$result .= "<li><a href=\"$path\" target=\"_blank\">$descr</a></li>";
    						$n++;
    					}
    				}
    			}
    		}
    		$result .= "</ul>";
    	}
    	return $result;
    }


    public function onContentPrepare($context, &$row, $params, $page = 0){
    	if ( strpos( $row->text, 'mosnewsletters' ) === false ) {  // Quick check
    		return true;
    	}
    	
    	$output = "";
     	$year = 2025;  // Should see me out :-)
     	while ($year >= 2000) {
    	 	$output .= $this->makeNewsletterIndex($year);
    	 	$year--;
     	}
     	$row->text = str_replace("{mosnewsletters}", $output, $row->text);
     	return true;
    }
}

?>
