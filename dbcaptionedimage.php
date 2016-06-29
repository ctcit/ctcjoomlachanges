<?php
// Displays a full-size image from the database plus its caption.
// It is assumed that the output will be in a new blank window, and
// a close button is provided to close this.
// Extended 13/11/11 to include links to previous and next photos in the
// trip report that this image belongs to, if such a trip report and such
// an image can be found.

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1" />
        <title>CTC image</title>
        <link rel="stylesheet" type="text/css" media="screen" href="./templates/ctcprotostar/css/template.css" />
        <script type='text/javascript'>
            function closeWindow() {
                window.open('', '_self', '');
                window.close();
            }

            function setHeight() {
                // Limit the height of the image to 70% of the available screen height
                var img = document.getElementById('image');
                var scrHeight = screen.availHeight;
                if (img.height > 0.7 * scrHeight) {
                    img.height = 0.7 * scrHeight;
                }
            }
        </script>
    </head>
    <body class='dbcaptionedimage' onload="setHeight()" >

        <?php
        require_once './configuration.php';
        $config = new JConfig();
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $link = mysql_connect("localhost", $config->user, $config->password) or die("Could not connect: " . mysql_error());

            mysql_select_db("ctcweb9_tripreports") or die(mysql_error());
            $id = $_GET['id'];
            $sql = "
SELECT name, caption, title, tripreport_id
FROM   image as img, tripreport_image as tri, tripreport as tr
WHERE  img.id = $id
AND    tri.image_id = img.id
AND    tr.id = tri.tripreport_id

UNION

SELECT name, caption, title, tripreport_id
FROM   image as img, tripreport_map as trm, tripreport as tr
WHERE  img.id = $id
AND    trm.map_id = img.id
AND    tr.id = trm.tripreport_id
";
            $result = mysql_query("$sql");
            if (!$result || mysql_num_rows($result) != 1) {
                die("Invalid query: " . mysql_error());
            }
            $row = mysql_fetch_object($result);

            // Try to find predecessor and successor images
            $prevIdInMaps = $id - 1;
            $nextIdInMaps = $id + 1;
            $sql = "SELECT iPrev.image_id as prevId
FROM  tripreport_image as iThis, tripreport_image as iPrev
WHERE iThis.ordering = iPrev.ordering + 1
AND   iThis.image_id = $id
AND   iThis.tripreport_id = iPrev.tripreport_id

UNION

SELECT iPrev.map_id as prevId
FROM  tripreport_map as iThis, tripreport_map as iPrev
WHERE   iThis.map_id = $id
AND   iPrev.map_id = $prevIdInMaps
AND   iThis.tripreport_id = iPrev.tripreport_id";
            $result = mysql_query("$sql");
            if ($result && mysql_num_rows($result) == 1) {
                $prevImg = mysql_fetch_object($result)->prevId;
            }

            $sql = "SELECT iNext.image_id as nextId
FROM  tripreport_image as iThis, tripreport_image as iNext
WHERE iThis.ordering = iNext.ordering - 1
AND   iThis.image_id = $id
AND   iThis.tripreport_id = iNext.tripreport_id

UNION

SELECT iNext.map_id as prevId
FROM  tripreport_map as iThis, tripreport_map as iNext
WHERE   iThis.map_id = $id
AND   iNext.map_id = $nextIdInMaps
AND   iThis.tripreport_id = iNext.tripreport_id";
            $result = mysql_query("$sql");
            if ($result && mysql_num_rows($result) == 1) {
                $nextImg = mysql_fetch_object($result)->nextId;
            }

            $spacer = "&nbsp;&nbsp;&nbsp;&nbsp;";
            ?>
            <div class='fullimagepage'>
                <img class="dbfullimage" src="dbimage.php?id=<?php echo $id; ?>"
                     id="image" alt="<?php echo urlencode($row->name); ?>" />
                <p class="fullimagecaption">
    <?php
    echo stripslashes($row->caption); // Already htmlencoded
    echo $spacer;
    $title = stripslashes($row->title);
    $reportId = $row->tripreport_id;
    //$tripurl = "./tripreports/index.html#/tripreports/$reportId/a";
    $tripurl= "./index.php/trip-reports?goto=tripreports/$reportId";
    echo "(from trip <a class='tripreportlink' href='$tripurl'>";
    echo "<em>$title</em></a>)\n";
    ?>

                </p>
                <p class='imageButtons'>
    <?php
    $prevTxt = "&lt;&nbsp;Previous Image";
    if (isset($prevImg)) {
        echo "<a class='imagelink' href='dbcaptionedimage.php?id=$prevImg'>$prevTxt</a>$spacer";
    } else {
        echo "<span class='inactiveimagelink'>$prevTxt</span>$spacer";
    }
    ?>
                    <input type="button" class="closewindowbutton" value="Close Image Window" onclick="closeWindow()" />
                    <?php
                    $nextTxt = "Next Image&nbsp;&gt;";
                    if (isset($nextImg)) {
                        echo "$spacer<a class='imagelink' href='dbcaptionedimage.php?id=$nextImg'>$nextTxt</a>";
                    } else {
                        echo "$spacer<span class='inactiveimagelink'>$nextTxt</span>";
                    }
                    ?>
                </p>
            </div>
                    <?php
                    mysql_close($link);
                }
                ?>

    </body>
</html>
