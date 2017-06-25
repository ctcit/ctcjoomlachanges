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

class plgContentCTCDocs extends JPlugin {

    static $basePath;
    static $liveSite;
    static $editallowed = false;

    public function makeDocumentIndex($root, $subdir, $sort = ALPHA_SORT) {
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
        $dirname = plgContentCTCDocs::$basePath . DIRECTORY_SEPARATOR . $root . DIRECTORY_SEPARATOR . $subdir;
        $dir = opendir($dirname);
        $result = '<h2>'; //.

        if (plgContentCTCDocs::$editallowed) {
            $result .= '<button title = "Upload document" onclick="UploadCTCDocuments(\'' . $root . '\',\'' . $subdir . '\')">+</button>' .
                    ' <progress class="progress' . $subdir . '" style="display:none"></progress> ';
        }
        $result .= $subdir; //.';//</h2><ul>';
        if (plgContentCTCDocs::$editallowed) {
            $result .= '  <button title = "Rename folder" onclick="RenameDocumentFolder(\'' . $root . '\',\'' . $subdir . '\')">...</button>';
        }
        $result .= '</h2><ul>';
        $lines = array();
        while (($file = readdir($dir)) !== false) {
            if ($file[0] == '.')
                continue;
            $ext = substr($file, -3, 3);
            if ($ext != "doc" && $ext != "pdf")
                continue;
            $path = $dirname . DIRECTORY_SEPARATOR . $file;
            $status = stat($path);
            $time = $status['mtime'];
            $size = round($status['size'] / 1000);
            $fileBits = explode(".", $file);
            $descr = $fileBits[0] . ".$ext";
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
            $line = "<li><a href=\"" . plgContentCTCDocs::$liveSite . "/$root/$subdir/$file\" target=\"_blank\">$descr</a>$extra";
            if (plgContentCTCDocs::$editallowed) {
                $line .= '<button style="width:20px;height:20px;margin:1px 0px 1px 5px; padding: 0px 2px 7px 2px;" title = "Remove file"  onclick="RemoveFile(\'' . $root . '\',\'' . $subdir . '\',\'' . $file . '\')">-</button>';
                $line .= '<button style="width:20px;height:20px;margin:1px 0px 1px 5px; padding: 0px 2px 7px 2px;" title = "Rename file" " onclick="RenameFile(\'' . $root . '\',\'' . $subdir . '\',\'' . $file . '\')">...</button>';
            }
            $line .= "</li>";
            $lines[$key] = $line;
        }
        ksort($lines);
        foreach ($lines as $line) {
            $result .= $line;
        }
        $result .= "</ul>";
        return $result;
    }

    static public function processDirectory($match) {
        // Called to process a matching {mostripimage directory} insertion
        //global $mosConfig_absolute_path;
        $user = JFactory::getUser();
        plgContentCTCDocs::$editallowed = $user->authorise('core.edit');
        if (count($match) != 2) {
            return "";
        } else {
            $param = $match[1];
            $output = '<script src="media/jui/js/jquery.min.js" type="text/javascript"></script>' .
                    '<script src="plugins/content/CTCDocs/ManageDocuments.js"></script>' .
                    '<DIALOG class = "InputDialog"><input class = "InputDialogText"></input><button class = "InputDialogOK">OK</button></DIALOG>';
            if (plgContentCTCDocs::$editallowed)
                $output .= '<button title = "New folder" onclick="NewDocumentFolder(\'' . $param . '\')">+ Folder</button>';

            $dirname = plgContentCTCDocs::$basePath . DIRECTORY_SEPARATOR . $param;
            if (!file_exists($dirname) || !is_dir($dirname)) {
                return "**ERROR: MISSING DIRECTORY** $dirname";  // No such directory
            }
            $dir = opendir($dirname);
            $subdirs = array();  // List of subdirectories
            $are_dates = True;  // True if subdirectories look like dates
            while (($subdir = readdir($dir)) !== false) {
                if ($subdir[0] == '.' || !is_dir($dirname . DIRECTORY_SEPARATOR . $subdir)) {
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

    public function onContentPrepare($context, &$row, $params, $page = 0) {
        if (strpos($row->text, '{mosctcdocs') === false) {  // Quick check
            return true;
        }
        $config = JFactory::getConfig();
        plgContentCTCDocs::$basePath = JPATH_BASE;
        plgContentCTCDocs::$liveSite = $config->get("live_site");
        // define the regular expression for the bot
        $regex = "#{mosctcdocs +(.*?)}#";
        // Do all the individual replacements in this document
        // (can handle multiple directories, though that's not a likely use).
        $row->text = preg_replace_callback($regex, function($match) {
            return plgContentCTCDocs::processDirectory($match);
        }, $row->text);
        return true;
    }

    public function onAjaxManagectcdocuments() {
        $user = JFactory::getUser();
        if (!$user->authorise('core.edit'))
            die("Unauthorised access");
        $data = array('success' => false, 'message' => 'Operation failed');
        if (isset($_POST['action']) && isset($_POST['root']) && is_dir($_POST['root'])) {
            // Assume Ajax call intended for attention here
            ob_start(); // Buffer any unexpected output
            $action = $_POST['action'];
            $root = $_POST['root'];
            $subdir = $_POST['subdir'];
            $error = false;
            if ($action === 'UploadFile' && isset($subdir)) {
                $uploaddir = JPATH_BASE . DIRECTORY_SEPARATOR . $root . DIRECTORY_SEPARATOR . $subdir;
                $files = array();
                $currentfile = '';
                foreach ($_FILES as $file) {
                    $currentfile = $file['name'];
                    if (move_uploaded_file($file['tmp_name'], $uploaddir . DIRECTORY_SEPARATOR . basename($file['name']))) {
                        $files[] = $uploaddir . $file['name'];
                    } else {
                        $error = true;
                        break;
                    }
                }
                $data = ($error) ? array('success' => false, 'message' => 'Error uploading file ' + $currentfile)
                                 : array('success' => true, 'message' => $currentfile . ' uploaded');
            } else if ($action === 'RemoveFile' && isset($subdir) && isset($_POST['filename'])) {
                $filename = $_POST['filename'];
                $newfilename = JPATH_BASE . DIRECTORY_SEPARATOR . $root . DIRECTORY_SEPARATOR . $filename;
                $oldfilename = JPATH_BASE . DIRECTORY_SEPARATOR . $root . DIRECTORY_SEPARATOR . $subdir . DIRECTORY_SEPARATOR . $filename;
                $error = !rename($oldfilename, $newfilename);
                $data = ($error) ? array('success' => false, 'message' => 'Error attempting to remove file ' . $filename)
                                 : array('success' => true, 'message' => $filename . ' removed');
            } else if ($action === 'RenameFile' && isset($subdir)) {
                if (isset($_POST['newname']) && isset($_POST['filename']) && strpbrk($_POST['newname'], "\\/?%*:|\"<>") === FALSE) {
                    $newfilepath = JPATH_BASE . DIRECTORY_SEPARATOR . $root . DIRECTORY_SEPARATOR . $subdir . DIRECTORY_SEPARATOR . $_POST['newname'];
                    $oldfilepath = JPATH_BASE . DIRECTORY_SEPARATOR . $root . DIRECTORY_SEPARATOR . $subdir . DIRECTORY_SEPARATOR . $_POST['filename'];
                    $error = file_exists($newfilepath) || !rename($oldfilepath, $newfilepath);
                    $data = ($error) ? array('success' => false, 'message' => 'Error attempting to rename file ' . $_POST['filename'])
                                     : array('success' => true, 'message' => $oldfilename . ' renamed to ' . $_POST['newname']);
                }
            } else if ($action === 'RenameFolder' && isset($subdir)) {
                if (isset($_POST['newname']) && strpbrk($_POST['newname'], "\\/?%*:|\"<>") === FALSE) {
                    $uploaddir = JPATH_BASE . DIRECTORY_SEPARATOR . $root . DIRECTORY_SEPARATOR . $subdir;
                    $newfoldername = JPATH_BASE . DIRECTORY_SEPARATOR . $root . DIRECTORY_SEPARATOR . $_POST['newname'];
                    $error = is_dir($newfoldername) || !rename($uploaddir, $newfoldername);
                    $data = ($error) ? array('success' => false, 'message' => 'Error attempting to rename folder ' . $subdir)
                                     : array('success' => true, 'message' => $subdir . ' renamed to ' . $_POST['newname']);
                }
            } else if ($action === 'NewFolder') {
                if (isset($_POST['newname']) && strpbrk($_POST['newname'], "\\/?%*:|\"<>") === FALSE) {
                    $newfoldername = JPATH_BASE . DIRECTORY_SEPARATOR . $root . DIRECTORY_SEPARATOR . $_POST['newname'];
                    $error = is_dir($newfoldername) || !mkdir($newfoldername);
                    $data = ($error) ? array('success' => false, 'message' => 'Error attempting to create folder ' . $newfoldername)
                                     : array('success' => true, 'message' => $newfoldername . ' created');
                }
            }
            ob_end_clean(); // Discard any potential output generated internally by php or other ajax handlers
            return json_encode($data);
        }
        return "";       
    }

}

?>
