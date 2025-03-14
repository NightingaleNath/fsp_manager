<?php
session_start();
include('includes/config.php');
if(isset($_POST['signin']))
{
	$emailId=$_POST['emailId'];
	$password=md5($_POST['password']);

	$sql ="SELECT * FROM users where email ='$emailId' AND password ='$password'";
	$query= mysqli_query($conn, $sql);
	$count = mysqli_num_rows($query);
	if($count > 0)
	{
		while ($row = mysqli_fetch_assoc($query)) {
		    if ($row['role'] == 'admin') {
		    	$_SESSION['alogin']=$row['user_id'];
				$_SESSION['user_email']=$row['email'];
		    	$_SESSION['arole']=$row['role'];
		    	$_SESSION['departmentid']=$row['department_id'];
				$_SESSION['facultyid ']=$row['faculty_id'];
				$_SESSION['courseid']=$row['course_id'];
				$_SESSION['phonenumber']=$row['phone_number'];
				$_SESSION['firstname']=$row['first_name'];
				$_SESSION['lastname']=$row['last_name'];
				$_SESSION['aschoolname']=$row['school_name'];
				$_SESSION['schoolid']=$row['school_id'];
				
			    //login active status
                $user_id =  $_SESSION['alogin'];

			 	echo "<script type='text/javascript'> document.location = 'admin/index.php'; </script>";
		    }
		    elseif ($row['role'] == 'staff') {
		    	$_SESSION['alogin']=$row['user_id'];
				$_SESSION['user_email']=$row['email'];
		    	$_SESSION['arole']=$row['role'];
		    	$_SESSION['departmentid']=$row['department_id'];
				$_SESSION['facultyid']=$row['faculty_id'];
				$_SESSION['courseid']=$row['course_id'];
				$_SESSION['phonenumber']=$row['phone_number'];
				$_SESSION['firstname']=$row['first_name'];
				$_SESSION['lastname']=$row['last_name'];
				$_SESSION['aschoolname']=$row['school_name'];
				$_SESSION['schoolid']=$row['school_id'];
				
				//login active status
                $user_id =  $_SESSION['alogin'];

			 	echo "<script type='text/javascript'> document.location = 'staff/index.php'; </script>";
		    }
		    else {
		    	$_SESSION['alogin']=$row['user_id'];
				$_SESSION['user_email']=$row['email'];
		    	$_SESSION['arole']=$row['role'];
		    	$_SESSION['departmentid']=$row['department_id'];
				$_SESSION['facultyid']=$row['faculty_id'];
				$_SESSION['courseid']=$row['course_id'];
				$_SESSION['phonenumber']=$row['phone_number'];
				$_SESSION['firstname']=$row['first_name'];
				$_SESSION['lastname']=$row['last_name'];
				$_SESSION['aschoolname']=$row['school_name'];
				$_SESSION['schoolid']=$row['school_id'];
				
			    //login active status
                $user_id =  $_SESSION['alogin'];

			 	echo "<script type='text/javascript'> document.location = 'users/all_projects.php'; </script>";
		    }

		}

	} 
	else{
	  echo "<script>alert('Invalid Details');</script>";

	}

}
?>

<!DOCTYPE html>
<html>
<head>
	<!-- Basic Page Info -->
	<meta charset="utf-8">
	<title>School Project Manager</title>

	<!-- Site favicon -->
	<link rel="apple-touch-icon" sizes="180x180" href="vendors/images/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="vendors/images/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="vendors/images/favicon-16x16.png">

	<!-- Mobile Specific Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!-- Google Font -->
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="vendors/styles/core.css">
	<link rel="stylesheet" type="text/css" href="vendors/styles/icon-font.min.css">
	<link rel="stylesheet" type="text/css" href="vendors/styles/style.css">

	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-119386393-1"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', 'UA-119386393-1');
	</script>
</head>
<body class="login-page">
	<?php include('loader.php')?>
	
	<div class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-md-6 col-lg-7">
					<img src="vendors/images/login-page-img.png" alt="">
				</div>
				<div class="col-md-6 col-lg-5">
					<div class="login-box bg-white box-shadow border-radius-10">
						<div class="login-title">
							<h2 class="text-center text-primary">School Project Manager</h2>
						</div>
						<form name="signin" method="post">
						
							<div class="input-group custom">
								<input type="email" class="form-control form-control-lg" placeholder="Email ID" name="emailId" id="emailId">
								<div class="input-group-append custom">
									<span class="input-group-text"><i class="icon-copy fa fa-envelope-o" aria-hidden="true"></i></span>
								</div>
							</div>
							<div class="input-group custom">
								<input type="password" class="form-control form-control-lg" placeholder="**********"name="password" id="password">
								<div class="input-group-append custom">
									<span class="input-group-text"><i class="dw dw-padlock1"></i></span>
								</div>
							</div>
							<div class="row pb-30">
								
								<div class="col-6">
									<div class="forgot-password"><a href="forgot-password.html">Forgot Password</a></div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<div class="input-group mb-0">
									   <input class="btn btn-primary btn-lg btn-block" name="signin" id="signin" type="submit" value="Sign In">
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- js -->
	<script src="vendors/scripts/core.js"></script>
	<script src="vendors/scripts/script.min.js"></script>
	<script src="vendors/scripts/process.js"></script>
	<script src="vendors/scripts/layout-settings.js"></script>
</body>
</html>