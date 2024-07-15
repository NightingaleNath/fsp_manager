<?php include('includes/header.php')?>
<?php include('../includes/session.php')?>

<?php
if (isset($_GET['params'])) {
    $decodedParams = base64_decode($_GET['params']);
    parse_str($decodedParams, $paramsArray);
    $departmentId = $paramsArray['department_id'];
    $departmentName = $paramsArray['department_name'];
    $facultyId = $paramsArray['faculty_id'];
    echo "<script>console.log('Received departmentId: " . $departmentId . "');</script>";
} else {
    echo "<script>console.log('No department_id received from URL');</script>";
    exit;
}

$department_id = intval($departmentId);
$department_name = $departmentName;
$faculty_id = $facultyId;
$sql = "SELECT * FROM Courses WHERE department_id = $department_id ORDER BY course_name ASC";
$course = $conn->query($sql);

if (!$course) {
	echo "<script>console.log('Error fetching course: " . $conn->error. "');</script>";
    exit;
}

// Debug output
echo "<script>console.log('Number of rows fetched: " . $course->num_rows . "');</script>";
?>

<?php 
if (isset($_POST['save_course'])) {
    $course_name = $_POST['name'];
	$department_id = $_POST['department_id'];

    $check = $conn->query("SELECT * FROM Courses WHERE course_name = '$course_name' AND department_id = $department_id")->num_rows;

    if ($check > 0) {
        echo "<script>alert('Programme already exists','success');</script>";
    } else {
        $save = $conn->query("INSERT INTO Courses (course_name, department_id) VALUES ('$course_name', $department_id)");
        if ($save) {
            $delay = 0;
            header("Refresh: $delay;");
            echo "<script>alert('New Programme successfully added.','success');</script>";
        } else {
            echo "<script>alert('Failed to add new Programme.','error');</script>";
        }
    }
}
?>

<body>
    <?php include('loader.php')?>

	<?php include('includes/navbar.php')?>

	<?php include('includes/right_sidebar.php')?>

	<?php include('includes/left_sidebar.php')?>

	<div class="mobile-menu-overlay"></div>

	<div class="main-container">
		<div class="pd-ltr-20 xs-pd-20-10">
			<div class="min-height-200px">
				<div class="container pd-0" style="max-width: 100%;">
					<div class="page-header">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="title">
									<h4>Project by Programme</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item"><a href="departments.php?faculty_id=<?php echo $faculty_id; ?>"><?php echo $department_name; ?></a></li>
										<li class="breadcrumb-item active" aria-current="page" style="color: #1b00ff;">Project Programme</li>
									</ol>
								</nav>
							</div>
                            <div class="col-md-6 col-sm-12 text-right">
                                <div class="pull-right">
                                    <a href="#" class="btn btn-primary btn-sm scroll-click" rel="content-y"  data-backdrop="static" data-toggle="modal" data-target="#login-modal" role="button" style="background-color: #007bff; border-color: #007bff; "><i class="icon-copy ion-plus"></i>  Add Programme</a>
                                </div>
                                <div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
								<div class="modal-dialog modal-dialog-centered">
									<div class="modal-content">
										<div class="login-box bg-white box-shadow border-radius-10">
											<div class="login-title">
												<h2 class="text-center text-primary">Create Programme</h2>
											</div>
											<form name="save_course" method="post">
												<input type="hidden" name="department_id" value="<?php echo $departmentId ? $departmentId : ''; ?>">
		                                        <input type="hidden" name="course_id" value="<?php echo isset($_GET['course_id']) ? $_GET['course_id'] :'' ?>">
												<div class="input-group custom">
													<input type="text" name="name" class="form-control form-control-lg" placeholder="Programme Name">
													<div class="input-group-append custom">
														<span class="input-group-text"><i class="icon-copy dw dw-folder-46"></i></span>
													</div>
												</div>
											
												<div class="row col-sm-12">
													<div class="col-sm-6" style="padding-right: 0px; padding-left: 5px;">
                                                        <input class="btn btn-primary" name="save_course" id="save_course" type="submit" value="Create">
											        </div>
                                                    <div class="col-sm-6" style="padding-right: 5px; padding-left: 0px;">
														<button type="button" class="btn btn-primary" style="background-color: #6c757d; border-color: #6c757d;" data-dismiss="modal">Cancel</button>
													</div>
												</div>
											</form>
										</div>
									</div>
								</div>
							</div>
						    </div>
						</div>
					</div>
					<div class="contact-directory-list">
						<ul class="row">
                            <?php 
                            while($row = $course->fetch_assoc()): ?>
                             <li class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
								<div class="contact-directory-box" style="min-height: 0px;">
									<div class="contact-dire-info text-center">
										<div class="producct-img" style="margin-bottom: 10px;">
											<span>
												<img src="../vendors/images/img5.jpg" alt="">
											</span>
										</div>
									</div>
									<div class="view-contact folder-item" data-course="<?php echo $row['course_name'] ?>" data-course-id="<?php echo $row['course_id'] ?>" data-id="<?php echo $department_id; ?>" data-name="<?php echo $department_name; ?>" data-faculty="<?php echo $faculty_id; ?>">
										<a><?php echo $row['course_name'] ?></a>
									</div>
								</div>
							</li>
                            <?php endwhile; ?>
							
						</ul>
					</div>
				</div>
			</div>
			<?php include('includes/footer.php'); ?>
		</div>
	</div>
	<!-- js -->
	<?php include('includes/scripts.php')?>
	<script>
		$('.folder-item').dblclick(function(){
            var departmentId = $(this).attr('data-id');
            var departmentName = $(this).attr('data-name');
            var facultyId = $(this).attr('data-faculty');
            var courseName = $(this).attr('data-course');
            var courseId = $(this).attr('data-course-id');
            var params = btoa('department_id=' + departmentId + '&department_name=' + departmentName + '&faculty_id=' + facultyId + '&course_id=' + courseId + '&course_name=' + courseName);
		    location.href = 'documentByYear.php?params=' + params;
		})
	</script>
</body>
</html>