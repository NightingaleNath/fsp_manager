<?php
include('includes/header.php');
$qry = $conn->query("SELECT * FROM projects where project_id=".$_GET['project_id'])->fetch_array();

extract($_POST);

$fname=$qry['file_path'];   
$file = ("assets/uploads/".$fname);

// check if the file exists and is readable
if (file_exists($file) && is_readable($file)) {
    // set the headers to force download
       
       header ("Content-Type: ".filetype($file));
       header ("Content-Length: ".filesize($file));
       header ("Content-Disposition: attachment; filename=".$qry['name'].'.'.$qry['file_type']);

       readfile($file);
} else {
    // file not found or not readable
    echo 'Error: File not found or not readable.';
}
