<?php
 session_start(); 
//Check whether the session variable SESS_MEMBER_ID is present or not
if (!isset($_SESSION['alogin']) || (trim($_SESSION['alogin']) == '')) { ?>
<script>
window.location = "../index.php";
</script>
<?php
}

$session_id=$_SESSION['alogin'];
$session_role = $_SESSION['arole'];
$session_email = $_SESSION['user_email'];
$session_depart = $_SESSION['departmentid'];
$session_facultyId = $_SESSION['facultyid'];
$session_courseId = $_SESSION['courseid'];
$session_School_Name = $_SESSION['aschoolname'];
$session_studentId = $_SESSION['schoolid'];
$session_firstName = $_SESSION['firstname'];
$session_lastName = $_SESSION['lastname'];
$session_phoneNumber = $_SESSION['phonenumber'];

?>