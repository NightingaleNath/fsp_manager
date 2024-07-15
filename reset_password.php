<?php
session_start();
include('includes/config.php');
if (isset($_POST['reset'])) {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $user_email = $_SESSION['user_email'];

    // Check if new password matches confirm password
    if ($new_password !== $confirm_password) {
        echo "<script>alert('New password and confirm password do not match');</script>";
    } else {
        // Fetch the old password from the database
        $sql = "SELECT password FROM users WHERE email = '$user_email'";
        $query = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($query);
        $old_password_hash = $row['password'];

        // Hash the new password using md5
        $new_password_md5 = md5($new_password);

        // Check if the old password is the same as the new password
        if ($new_password_md5 === $old_password_hash) {
            echo "<script>alert('New password cannot be the same as the old password');</script>";
        } else {
            // Update the new password in the database
            $update_sql = "UPDATE users SET password = '$new_password_md5' WHERE email = '$user_email'";
            if (mysqli_query($conn, $update_sql)) {
                echo "<script>alert('Password changed successfully');</script>";

                // Logout the user
                session_unset();
                session_destroy();
                echo "<script>window.location.href = 'logout.php';</script>";
            } else {
                echo "<script>alert('Error changing password: " . mysqli_error($conn) . "');</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
	<!-- Basic Page Info -->
	<meta charset="utf-8">
	<title>DeskApp - Bootstrap Admin Dashboard HTML Template</title>

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
<body>
	<div class="login-header box-shadow">
		<div class="container-fluid d-flex justify-content-between align-items-center">
			<div class="brand-logo">
				<a href="login.html">
					<img src="vendors/images/deskapp-logos-svg.png" alt="">
				</a>
			</div>
			<div class="login-menu">
				<ul>
					<li><a href="javascript:history.go(-1)">Go back</a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-md-6">
					<img src="vendors/images/forgot-password.png" alt="">
				</div>
				<div class="col-md-6">
					<div class="login-box bg-white box-shadow border-radius-10">
						<div class="login-title">
							<h2 class="text-center text-primary">Reset Password</h2>
						</div>
						<h6 class="mb-20">Enter your new password, confirm and submit</h6>
						<form name="reset" method="post">
							<div class="input-group custom">
								<input name="new_password" type="text" class="form-control form-control-lg" placeholder="New Password">
								<div class="input-group-append custom">
									<span class="input-group-text"><i class="dw dw-padlock1"></i></span>
								</div>
							</div>
							<div class="input-group custom">
								<input name="confirm_password" type="text" class="form-control form-control-lg" placeholder="Confirm New Password">
								<div class="input-group-append custom">
									<span class="input-group-text"><i class="dw dw-padlock1"></i></span>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<div class="input-group mb-0">
									   <input class="btn btn-primary btn-lg btn-block" name="reset" id="reset" type="submit" value="Change Password">
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