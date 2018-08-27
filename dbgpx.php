<?php

if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    require_once './configuration.php';
    $config = new JConfig();

	$db = new mysqli("localhost", $config->user, $config->password) or die("Could not connect to database: ". $db->connect_error);

	$db->select_db("tripreports") or die('Failed to select database ctcweb9_tripreports');

	$id = $_GET['id'];

	$sql = "SELECT gpx, name FROM gpx WHERE id=$id";
	$result = $db->query($sql) or die('db query failed: ' . $db->error);
	($row = $result->fetch_object()) or die('Missing GPX record');
	header("Content-Disposition: attachment; filename=\"{$row->name}\"");
	header("Content-Type: text/gpx");
	echo $row->gpx;

	$db->close();
}

