<?php include('includes/header.php')?>
<?php include('../includes/session.php')?>
<?php 
if (isset($_GET['params'])) {
    $decodedParams = base64_decode($_GET['params']);
    parse_str($decodedParams, $paramsArray);
    $departmentId = $paramsArray['department_id'];
    $departmentName = $paramsArray['department_name'];
    $facultyId = $paramsArray['faculty_id'];
	$courseId = $paramsArray['course_id'];
	$courseName = $paramsArray['course_name'];
    echo "<script>console.log('Received departmentId: " . $departmentId . "');</script>";
} else {
    echo "<script>console.log('No department_id received from URL');</script>";
    exit;
}

$department_id = intval($departmentId);
$department_name = $departmentName;
$faculty_id = $facultyId;
$course_id = $courseId;
$course_name = $courseName;

echo "<script>console.log('Received departmentId: " . $departmentId . " ".$department_name."');</script>";

$sql = "SELECT * FROM Years ORDER BY year ASC";
$years = $conn->query($sql);
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
										<li class="breadcrumb-item active" aria-current="page" style="color: #1b00ff;"><?php echo $course_name; ?></li>
									</ol>
								</nav>
							</div>
						</div>
					</div>
					<div class="contact-directory-list">
						<ul class="row">
                            <?php 
                            while($row=$years->fetch_assoc()):
                            ?>
                             <li class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
								<div class="contact-directory-box" style="min-height: 0px;">
									<div class="view-contact folder-item" data-year-id="<?php echo $row['year_id'] ?>" data-year="<?php echo $row['year'] ?>" data-course="<?php echo $course_name; ?>" data-course-id="<?php echo $course_id; ?>" data-department-id="<?php echo $department_id; ?>" data-department-name="<?php echo $department_name; ?>" data-faculty-id="<?php echo $faculty_id; ?>">
										<a ><?php echo $row['year'] ?></a>
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
		$('.folder-item').dblclick(function() {
			var departmentId = $(this).attr('data-department-id');
            var departmentName = $(this).attr('data-department-name');
            var facultyId = $(this).attr('data-faculty-id');
            var courseName = $(this).attr('data-course');
            var courseId = $(this).attr('data-course-id');
			var yearId = $(this).attr('data-year-id');
			var yearName = $(this).attr('data-year');
            var params = btoa('department_id=' + departmentId + '&department_name=' + departmentName + '&faculty_id=' + facultyId + '&course_id=' + courseId + '&course_name=' + courseName + '&year_id=' + yearId + '&year_name=' + yearName);
		  	location.href = 'project_file_list.php?params=' + params;
		})
        
	</script>

</body>
</html>