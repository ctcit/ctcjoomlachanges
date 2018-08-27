<?php
function my_ucwords($str, $is_name=false) {
   // exceptions to standard case conversion
   if ($is_name) {
       $all_uppercase = '';
       $all_lowercase = 'De La|De Las|Der|Van De|Van Der|Vit De|Von|Or|And';
   } else {
       // addresses, essay titles ... and anything else
       $all_uppercase = 'Po|Rr|Se|Sw|Ne|Nw';
       $all_lowercase = 'A|And|As|By|In|Of|Or|To';
   }
   $prefixes = 'Mc';
   $suffixes = "'S";

   // captialize all first letters
   $str = preg_replace('/\\b(\\w)/e', 'strtoupper("$1")', strtolower(trim($str)));

   if ($all_uppercase) {
       // capitalize acronymns and initialisms e.g. PHP
       $str = preg_replace("/\\b($all_uppercase)\\b/e", 'strtoupper("$1")', $str);
   }
   if ($all_lowercase) {
       // decapitalize short words e.g. and
       if ($is_name) {
           // all occurences will be changed to lowercase
           $str = preg_replace("/\\b($all_lowercase)\\b/e", 'strtolower("$1")', $str);
       } else {
           // first and last word will not be changed to lower case (i.e. titles)
           $str = preg_replace("/(?<=\\W)($all_lowercase)(?=\\W)/e", 'strtolower("$1")', $str);
       }
   }
   if ($prefixes) {
       // capitalize letter after certain name prefixes e.g 'Mc'
       $str = preg_replace("/\\b($prefixes)(\\w)/e", '"$1".strtoupper("$2")', $str);
   }
   if ($suffixes) {
       // decapitalize certain word suffixes e.g. 's
       $str = preg_replace("/(\\w)($suffixes)\\b/e", '"$1".strtolower("$2")', $str);
   }
   return $str;
}

function getMembershipListQuery($surnameFirst = false)
// Returns a mysql query object suitable for generating the website membership lists.
// See the various membershiplist forms in chronocontact for uses. Names are
// generated as e.g. Fred Bloggs unless $surnameFirst is true in which case they're
// of the form Bloggs, Fred.
{
	$host = 'localhost';
	$user = 'visitor';
	$password = 'highonhills';
	$dbase = 'ctc';
	$dbprefix = '';
	$conn = mysql_connect($host, $user, $password);
	$db = mysql_select_db($dbase);
	$nameQuery = $surnameFirst ? "concat(lastName,', ',firstName)" : "concat(firstName,' ',lastName)";
	$query = mysql_query(
	   "SELECT $nameQuery as name,
			primaryEmail,
			homePhone,
			mobilePhone,
			concat(address1, IF(address2 = '',', ',CONCAT(', ',address2,', ')), city, ' ', postcode) as address
	    FROM members, memberships
	    WHERE membershipId = memberships.id
	    AND statusAdmin='Active'
	    ORDER BY name");
	return $query;
}

function membershipListHeader()
{
	$header = <<<_HEADER_
		<h2>CTC Membership List</h2>
		<p>The membership list below is formatted for on-screen viewing.
		Printable versions are also available:
		<ul>
			<li><a
				href="http://www.ctc.org.nz/index2.php?option=com_chronocontact&pop=1&page=0&chronoformname=NewPFMembershipList"
				target="_blank"> printable version, sorted by first name</a></li>
			<li><a
				href="http://www.ctc.org.nz/index2.php?option=com_chronocontact&pop=1&page=0&chronoformname=NewPFMembershipListSurnameSort"
				target="_blank"> printable version, sorted by last name</a></li>
		</ul>
		</p>
		<p>Please note that this list is intended for the use of Christchurch
		Tramping Club members only and for Christchurch Tramping Club related
		matters only. Please respect the privacy of your fellow members. In
		particular, please do not use emails obtained from this list for mass
		mailouts to club members. Such mailouts should be sent to the <a
			href="mailto:members@ctc.org.nz">moderated members mail list</a>.</p>
		<p>Click on the checkboxes below to select either email address, street
		address or mobile phone for the third column.</p>
		<p>Note: if clicking the checkboxes has no effect, your browser may not
		have javascript enabled. In that case you can still find out someone's
		address by holding the mouse cursor over the person's name for a second
		or so -- a small window should then pop up with the address in it.
		Similarly, holding the cursor over a person's phone will reveal their
		mobile number (if they have one).</p>
		<p></p>

		<table width="75%">
			<tr>
				<td>Address: <input type="checkbox" id="showAddress"
					onclick="javascript:showHideCols(2)"></td>
				<td>Email: <input type="checkbox" id="showEmail"
					onclick="javascript:showHideCols(3)" CHECKED></td>
				<td>Mobile phone: <input type="checkbox" id="showMob"
					onclick="javascript:showHideCols(4)"></td>
			</tr>
		</table>
		</p>

		<h3>Membership List</h3>
		<p>Members can change their personal details via the <i>User Details</i>
                link in the members menu. Alternatively, you can email corrections
                to <a href="mailto:trampgeek@gmail.com?subject=Change%20of%20CTC%20Contact%20Details">Richard Lobb</a>.</p>
_HEADER_;
	return $header;
}

function PFMembershipListHeader($sortKey)
{
	$header = "<h2>CTC Membership List (sorted by $sortKey)</h2>";
	$rest = <<<_HEADER_

		<p>Please note that this list is intended for the use of Christchurch Tramping Club members only and
		for Christchurch Tramping Club related matters only. Please respect the privacy of your fellow
		members. In particular, please do not use emails obtained from this list for mass mailouts to
		club members. Such mailouts should be sent to the
		<a href="mailto:members@ctc.org.nz">moderated members mail list</a>.</p>
_HEADER_;
	return $header.$rest;
}

function PFMembershipList($surnameFirst = false)
// Generate printer-friendly membership list ordered by firstName if $surnameFirst = false,
// else by lastName
{
	global $my;
	global $database;
	global $mosConfig_absolute_path;
	$userID = $my->username;
	if ($userID == "") {
		echo "Sorry, but you must be logged in to see the membership list.<br />";
	}
	else {
		echo PFMembershipListHeader($surnameFirst ? "surname": "first name");
	    $query = getMembershipListQuery($surnameFirst);

        echo "<table>";
        echo "<tr><th class=\"col0\">Name</th><th class=\"col1\">Phone</th><th class=\"col2\">Address</th><th class=\"col3\">Email</th></tr>";

        $rowClass = "sectiontableentry1";
		$user = mysql_fetch_object($query);
		while ($user !== FALSE) {
			print "<tr class=\"$rowClass\">";
        	print "<td class=\"col0\">$user->name</td><td class=\"col1\">$user->homePhone</td><td class=\"col2\">$user->address</td><td class=\"col3\">$user->primaryEmail</td>";
            print "</tr>";
            if ($rowClass == "sectiontableentry1") {
            	$rowClass = "sectiontableentry2";
			}
			else {
				$rowClass = "sectiontableentry1";
            }
            $user = mysql_fetch_object($query);
        }
		echo "</thead></table>";
	}
}

function membershipList()
// The function to display the normal interactive on-line membershiplist.
{
	global $my;
	global $database;
	global $mosConfig_absolute_path;
	require_once "$mosConfig_absolute_path/includes/ctcfuncs2.php";

	$userID = $my->username;
	if ($userID == "") {
		echo "Sorry, but you must be logged in to see the membership list.<br />";
	}
	else {
		echo membershipListHeader();
	    $query = getMembershipListQuery();
		echo "<table id=\"members\">";
		echo "<thead>";
		$rowClass = "sectiontableentry1";
		echo "<tr class=\"$rowClass\"><td class=\"col0\"><b>Name</b></td><td class=\"col1\"><b>Phone</b></td><td class=\"col2\"  style=\"display:none\"><b>Address</b></td><td  class=\"col3\"  style=\"display\"><b>Email</b></td><td class=\"col4\"
	style=\"display:none\"><b>Mobile</b></td></tr>";
		echo "</thead><tbody>";
		$user = mysql_fetch_object($query);
		while ($user !== FALSE) {
			$phone = $user->homePhone;
			$mobTitle = $user->mobilePhone;
			if ($mobTitle == "") $mobTitle = "No mobile";
			print "<tr class=\"$rowClass\">";
			$addr = "$user->address";
			print "<td class=\"col0\" title=\"$addr\">$user->name</td>" .
				"<td class=\"col1\" title=\"$mobTitle \">$phone</td><td class=\"col2\"  " .
				"style=\"display:none\">$addr</td><td class=\"col3\"  style=\"display\">".
				"$user->primaryEmail</td><td class=\"col4\" style=\"display:none\">$user->mobilePhone</td>";
			print "</tr>";
			if ($rowClass == "sectiontableentry1") {
				$rowClass = "sectiontableentry2";
			}
			else {
				$rowClass = "sectiontableentry1";
			}
			$user = mysql_fetch_object($query);
		}
		echo "</thead></table>";
	}
}

