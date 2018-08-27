<?php

if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    require_once './configuration.php';
    $config = new JConfig();

	$link = mysql_connect("localhost", $config->user, $config->password) or die("Could not connect: " . mysql_error());

	mysql_select_db("tripreports") or die(mysql_error());

	$id = $_GET['id'];

	$sql = "SELECT gpx, name FROM gpx WHERE id=$id";
	$result = mysql_query($sql) or die(mysql_error());
	($row = mysql_fetch_object($result)) or die(mysql_error());
	header("Content-Disposition: attachment; filename=\"{$row->name}\"");
	header("Content-Type: text/gpx");
	echo $row->gpx;

	mysql_close($link);
}
?>
