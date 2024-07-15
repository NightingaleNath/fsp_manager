<?php 
include('includes/header.php');
$qry = $conn->query("SELECT * FROM projects where project_id=".$_GET['project_id'])->fetch_array();

extract($_POST);

$fname=$qry['file_path'];   
$file = ("assets/uploads/".$fname);

// header ("Content-Type: ".filetype($file));
// header ("Content-Length: ".filesize($file));
// header ("Content-Disposition: attachment; filename=".$qry['name'].'.'.$qry['file_type']);

// readfile($file);
?>
  <body>

    <iframe id="pdfframe" src="<?php echo $file; ?>" frameborder="0" height="100%" width="100%">
    </iframe>

  </body>

</html>