<?php

// This script empties out the 'trips' and 'social' categories of the
// 'events' content section and reloads them from the 'Events' table
// in Freddy's Newsletter database. This is probably a oncer -- longer
// term plan is to display trips and social calendar pages directly
// Modified by Doug 9/5/2015 for Joomla 3
// still not doing directly

function deleteEventContent($eventCategoryId){
    $user = JFactory::getUser();
    if (!$user->authorise('core.edit', 'com_content.category.'.$eventCategoryId)) {
        // As a backup, check core.edit.own
        if (!$user->authorise('core.edit.own', 'com_content.category.'.$eventCategoryId)) {
            // No core.edit nor core.edit.own - bounce this one
            die('Error: Not authorised to manage newsletter events');
        }
    }
    $database = JFactory::getDBO();
    // Delete full content (article)
    $query = "DELETE co FROM #__content co ".
             "\n WHERE co.catid=$eventCategoryId";
    $database->setQuery($query);
    $database->query();
}

function addEvent($catId, $title, $body, $order, $date) {
    $fromEnc = 'Windows-1252';
    $body = mb_convert_encoding($body, 'UTF-8', $fromEnc);
    $title = mb_convert_encoding($title, 'UTF-8', $fromEnc);

    $user = JFactory::getUser();
    $tableContent = JTable::getInstance('Content', 'JTable');
    //$tableContent->getDbo()->setDebug(True); // Testing remove this
    $params = array(
        'alias' => $tableContent->alias,
        'catid' => $tableContent->catid
    );

    if ($tableContent->load($params) && ($tableContent->id != $tableContent->id || $tableContent->id == 0)) {
        JError::raiseWarning(
            "Save content",
            JText::_('JLIB_DATABASE_ERROR_ARTICLE_UNIQUE_ALIAS') . ": " . $tableContent->alias
        );
        return false;
    }
    $tableContent->introtext = $body;
    $tableContent->fulltext = "";
    $tableContent->metakey = "" ;
    $tableContent->metadesc = "";
    $tableContent->catid = $catId;
    $tableContent->access = 1; //public
    $tableContent->language = "*";
    $tableContent->created_by = $user->id;
    $tableContent->title = $title;
    $tableContent->alias = JFilterOutput::stringURLSafe($title);
    $tableContent->metadata = "";
    $tableContent->metakey = "";
    $tableContent->metadesc = "";
    $tableContent->created_by_alias = "";
    $tableContent->images = "";
    $tableContent->urls = "";
    $tableContent->attribs = "";
    $tableContent->xreference = "";
    date_default_timezone_set("Pacific/Auckland");
    $tz = new DateTimeZone(date_default_timezone_get());
    $created = new JDate('now', $tz);
    date_modify($created, "- 1 years");
    $tableContent->created = $created->toSql(true);
    $tableContent->publish_up = $created->toSql(true);
    $publishDown = new JDate($date, $tz);
    date_modify($publishDown, "+ 2 days");
    $tableContent->publish_down = $publishDown->toSql(true);
    // testing
      //$publishDown = new JDate("now", $tz);
      //date_modify($publishDown, "+ 2 days");
      //$tableContent->publish_down = $publishDown->toSql(true);
    // end testing comment out above
    $tableContent->state = 1;
    $tableContent->featured = 0;
    $tableContent->ordering = $order;

    // This saves the row, notices that it is new and manages asset_id
    if (!$tableContent->store())
        return false;
    return true;
}

function addSocialEvent($catId, $order, $title, $body, $date) {
    addEvent($catId, $title, $body, $order, $date);
}

function addTrip($catId, $order, $title,
                 $departurePoint, $close, $maps, $cost, $grade,
                 $leader, $body, $date) {
    $fullBody = "{mosleader $leader}<p>Grade $grade. Map(s) $maps. Approximate cost $cost. " .
                " List $close.</p><p>Departure Point: $departurePoint</p><p>$body</p>";
    addEvent($catId, $title, $fullBody, $order, $date);
}

function my_implode($glue, $data) {
    // Like implode only ignores empty strings
    $result = "";
    foreach ($data as $item) {
        if ($item != "") {
            if ($result != "") $result .= $glue;
            $result .= $item;
        }
    }
    return $result;
}

function processEventsTable(){
    define( '_VALID_MOS', 1 );
    define('_JEXEC', 1);
    define('JPATH_BASE', dirname(__DIR__));// Assume scripts at top level in website
    require_once( JPATH_BASE.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'defines.php' );
    require_once (JPATH_BASE.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'framework.php');
    require_once (JPATH_BASE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_content'.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'article.php');
    //require_once (JPATH_BASE.DIRECTORY_SEPARATOR.'libraries'.DIRECTORY_SEPARATOR.'joomla'.DIRECTORY_SEPARATOR.'date'.DIRECTORY_SEPARATOR.'date.php');
    $app = JFactory::getApplication('site');
    $config = new JConfig();

    $NEWSLETTER = "ctcweb9_newsletter";
    $options['format'] = '{DATE}\t{TIME}\t{LEVEL}\t{CODE}\t{MESSAGE}';
    $options['text_file'] = 'NewsletterLog.txt';
    JLog::addLogger($options, JLog::ALL, array('Update', 'databasequery', 'jerror'));

    // Hard code Ids as safest "Magic" constant
    $socialCatId = 32;
    $tripsCatId = 29;
    $con = mysql_connect($config->host, $config->user, $config->password);
    if (!$con)  {
        echo('Could not connect: ' . mysql_error());
        return;
    }
    // First, make sure we have some newsletter events to process
    mysql_select_db($NEWSLETTER, $con);
    $result = mysql_query("SELECT * from events");
    $row = mysql_fetch_array($result);
    if (!$result || !$row) {
        echo "**ERROR**: No events found";
        return;
    }
    deleteEventContent($socialCatId);//social
    deleteEventContent($tripsCatId);//trips

    $newsletterEvents = mysql_query("SELECT * from events where date >= date_sub(NOW(), INTERVAL 1 day) order by date");
    if (!$newsletterEvents) {
        die("**ERROR** Reading of newsletter events table failed: " . mysql_error());
    }
    $tripOrder = 0;
    $socialOrder = 0;

    while ($newsletterEvent = mysql_fetch_array($newsletterEvents, MYSQL_ASSOC)) {
        if ($newsletterEvent['publish'] == 0) {
            continue;
        }
        foreach ($newsletterEvent as $key => $value) {
            printf("%s: %s<br />", $key, $value);
        }
        echo "<br />";
        $dateDisp = $newsletterEvent['dateDisplay'];
        $date = $newsletterEvent['date'];
        if ($newsletterEvent['datePlus'] != '')
            $dateDisp .= " ".$newsletterEvent['datePlus'];
        $title = "$dateDisp: ".$newsletterEvent['title'];
        $body = $newsletterEvent['text'];
        if ($newsletterEvent['type'] == 'Social') {
            addSocialEvent($socialCatId, ++$socialOrder, $title, $body, $date);
        } else if ($newsletterEvent['type'] == 'Trip') {
            $maps = my_implode(", ", array($newsletterEvent['map1'], $newsletterEvent['map2'], $newsletterEvent['map3']));
            $closes = "closes";
            if ($newsletterEvent['close1'] != "")
                $closes = $newsletterEvent['close1'];
            $close = $closes." ".$newsletterEvent['close2'];
            $leader = my_implode(" ", array($newsletterEvent['leader'], $newsletterEvent['leaderplus'], $newsletterEvent['leaderPhone']));
            if ($newsletterEvent['showEmail'] == '1') {
                $leader .= ' '.$newsletterEvent['leaderEmail'];
            }
            $departurePoint = trim($newsletterEvent['departurePoint']);
            if ($departurePoint == '') {
                $departurePoint = "Z Station Papanui";
            }
            addTrip($tripsCatId, ++$tripOrder,
                $title, $departurePoint, $close, $maps, $newsletterEvent['cost'],
                $newsletterEvent['grade'],$leader, $body, $date);
        }
    }

    mysql_close($con);
}


// Main body

processEventsTable();
?>
