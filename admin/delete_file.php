<?php
include('includes/header.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // get the id of the file to delete
    $id = $_POST['id'];

    $sql = "DELETE FROM project_files WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        
        $sql = "SELECT * FROM project_files WHERE id = $id";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $file_path = $row['file_path'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
        echo "<script>
                if (confirm('Record deleted successfully')) {
                    window.location.href = '".$_SERVER['HTTP_REFERER']."?success=Record deleted successfully';
                }
              </script>";
    } else {
        echo "Error deleting record: " . $conn->error;
        exit();
    }
}
?>
