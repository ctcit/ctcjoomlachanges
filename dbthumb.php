<?php
    error_reporting(E_ALL);
    // some basic sanity checks
    if(isset($_GET['id']) && is_numeric($_GET['id'])) {
        //connect to the db
        require_once './configuration.php';
        $config = new JConfig();
        $db = new mysqli($config->host, $config->user, $config->password);
        if (!$db) {
            die("Could not connect: " . $db->error());
        }

        // select our database
        $db->select_db("tripreports") or die($db->error());

        $id = $_GET['id'];

        // get the image from the db
        $sql = "SELECT thumb FROM image WHERE id=$id";
        $result = $db->query($sql) or die("Invalid query: " . $db->error());

        // set the header for the image
        header("Content-type: image/jpeg");

        // Echo the actual thumbnail (a blob)
        $row = $result->fetch_row();
        echo $row[0];

        // close the db link
        $db->close();
    }
?>
