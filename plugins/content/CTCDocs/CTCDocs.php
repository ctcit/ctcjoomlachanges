<?php
/**
 * plugin to generate a set of download links to documents
 * in a directory tree.
 *
 * Usage: {mosctcdocs docTreeRoot}
 * Builds the list of links to documents in the given directory
 * and subdirectories (only two levels). If the user is a normal
 * non-committee member, the links are restricted to showing just pdf
 * documents. Otherwise all documents are shown.
 */

defined('_JEXEC') or die();

define('ALPHA_SORT', 0);
define('LAST_MODIFIED_SORT', 1);

class plgContentCTCDocs extends JPlugin{

    static $basePath;
    static $liveSite;

    public function makeDocumentIndex($root, $subdir, $sort=ALPHA_SORT) {
        // Makes a list of links to all documents in the given subdirectory
        // of the given root directory. The subdirectory name is used as a level
        // 2 header. Files within the given directory are assumed to have
        // 2- or 3-component names. The third component (extension) must
        // be .doc or .pdf -- all other files are ignored. The first part
        // appears in the link as the document name. The middle part,
        // if present, is used as a description after the document name,
        // and is enclosed in parentheses.
        // Sort order is currently experimental; only call to this uses
        // ALPHA_SORT (the default).
        $dirname = plgContentCTCDocs::$basePath.DIRECTORY_SEPARATOR.$root.DIRECTORY_SEPARATOR.$subdir;
        $dir = opendir($dirname);
        $result = "<h2>$subdir</h2><ul>";
        $lines = array();
        while (($file = readdir($dir)) !== false) {
            if ($file[0] == '.') continue;
            $ext = substr($file,-3,3);
            if ($ext != "doc" && $ext != "pdf") continue;
                $path = $dirname.DIRECTORY_SEPARATOR.$file;
                $status = stat($path);
                $time = $status['mtime'];
                $size = round($status['size']/1000);
                $fileBits = explode(".",$file);
                $descr = $fileBits[0].".$ext";
                $extra = "";
                if (count($fileBits) > 2) {
                    $extra .= " ($fileBits[1])";
                }
                $extra .= " -- $size kB";
                if ($sort === ALPHA_SORT) {
                    $key = $fileBits[0];
                } else {
                    $key = $time;
                }
                $lines[$key] = "<li><a href=\"".plgContentCTCDocs::$liveSite."/$root/$subdir/$file\" target=\"_blank\">$descr</a>$extra</li>";
            }
        ksort($lines);
        foreach ($lines as $line) {
            $result .= $line;
        }
        $result .= "</ul>";
        return $result;
    }

    static public function processDirectory ( $match ) {
        // Called to process a matching {mostripimage directory} insertion
        //global $mosConfig_absolute_path;
        if (count($match) != 2) {
            return "";
        }
        else {
            $param = $match[1];
            $output = "";
            $dirname = plgContentCTCDocs::$basePath.DIRECTORY_SEPARATOR.$param;
            if (!file_exists($dirname) || !is_dir($dirname)) {
                return "**ERROR: MISSING DIRECTORY** $dirname";  // No such directory
            }
            $dir = opendir($dirname);
            $subdirs = array();  // List of subdirectories
            $are_dates = True;  // True if subdirectories look like dates
            while (($subdir = readdir($dir)) !== false) {
                if ($subdir[0] == '.' || !is_dir($dirname.DIRECTORY_SEPARATOR.$subdir)) {
                    continue;
                }
                $subdirs[] = $subdir;
                if (!is_numeric($subdir) || $subdir < 1900 || $subdir > 2200) {
                    $are_dates = False;
                }
            }

            if ($are_dates) {
                rsort($subdirs);  // Sort date directories in descending order
            } else {
                sort($subdirs);  // Sort other directories ascending
            }

            foreach ($subdirs as $subdir) {
                $output .= plgContentCTCDocs::makeDocumentIndex($param, $subdir);
            }
        }
        return $output;
    }


    public function onContentPrepare($context, &$row, $params, $page = 0){
        if ( strpos( $row->text, '{mosctcdocs' ) === false ) {  // Quick check
            return true;
        }
        $config = JFactory::getConfig();
        plgContentCTCDocs::$basePath = JPATH_BASE;
        plgContentCTCDocs::$liveSite = $config->get("live_site");
         // define the regular expression for the bot
        $regex = "#{mosctcdocs +(.*?)}#";
        // Do all the individual replacements in this document
        // (can handle multiple directories, though that's not a likely use).
        $row->text = preg_replace_callback( $regex, function($match){return plgContentCTCDocs::processDirectory($match);}, $row->text );
        return true;
    }
}

?>
