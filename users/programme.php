<?php include('includes/header.php')?>
<?php include('../includes/session.php')?>

<?php
// Fetch user details along with department and faculty information
$user_id = intval($session_id);
$sql = "
    SELECT 
        u.department_id,
        d.department_name,
        f.faculty_id,
        f.faculty_name
    FROM 
        users u
    JOIN 
        departments d ON u.department_id = d.department_id
    JOIN 
        faculties f ON d.faculty_id = f.faculty_id
    WHERE 
        u.user_id = $user_id
";

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $department_id = $row['department_id'];
    $department_name = $row['department_name'];
    $faculty_id = $row['faculty_id'];
    $faculty_name = $row['faculty_name'];
} else {
    echo "<script>console.log('Error fetching user details: " . $conn->error . "');</script>";
    exit;
}

// Fetch courses based on department_id
$sql_courses = "SELECT * FROM Courses WHERE department_id = $department_id and course_id = $session_courseId ORDER BY course_name ASC";
$course = $conn->query($sql_courses);

if (!$course) {
    echo "<script>console.log('Error fetching courses: " . $conn->error . "');</script>";
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
										<li class="breadcrumb-item"><a href="javascript:history.go(-1)"><?php echo $department_name; ?></a></li>
										<li class="breadcrumb-item active" aria-current="page" style="color: #1b00ff;">Project Programme</li>
									</ol>
								</nav>
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