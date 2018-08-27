<?php
    // Dump a given database image (URL arg id) to the output stream.
    if(isset($_GET['id']) && is_numeric($_GET['id'])) {
        require_once './configuration.php';
        $config = new JConfig();
        $db = new mysqli($config->host, $config->user, $config->password);
        if (!$db) {
            die("Could not connect: " . $db->error());
        }

        $db->select_db("tripreports") or die($db->error());

        $id = $_GET['id'];

        $sql = "SELECT image, type FROM image WHERE id=$id";
        $result = $db->query($sql) or die($db->error());
        $row = $result->fetch_object() or die($db->error());
        header("Content-type: image/{$row->type}");
        echo $row->image;

        $db->close();
    }
?>
