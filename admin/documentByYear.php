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

$sql = "SELECT * FROM Years ORDER BY year ASC";
$years = $conn->query($sql);
?>

<?php 
if (isset($_POST['save_year'])) {
    $year = $_POST['year'];

    $check = $conn->query("SELECT * FROM Years WHERE year = '$year'")->num_rows;

    if ($check > 0) {
        echo "<script>alert('Year already exists','success');</script>";
    } else {
        $save = $conn->query("INSERT INTO Years (year) VALUES ('$year')");
        if ($save) {
            $delay = 0;
            header("Refresh: $delay;");
            echo "<script>alert('New Year successfully added.','success');</script>";
        } else {
            echo "<script>alert('Failed to add new Year.','error');</script>";
        }
    }
}
?>

<body>

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
                            <div class="col-md-6 col-sm-12 text-right">
                                <div class="pull-right">
                                    <a href="#" class="btn btn-primary btn-sm scroll-click" rel="content-y"  data-backdrop="static" data-toggle="modal" data-target="#login-modal" role="button" style="background-color: #007bff; border-color: #007bff; "><i class="icon-copy ion-plus"></i>  Add Project Year</a>
                                </div>
                                <div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
								<?php 
                                    if(isset($_GET['id'])){
                                    $qry = $conn->query("SELECT * FROM folders where id=".$_GET['id']);
                                        if($qry->num_rows > 0){
                                            foreach($qry->fetch_array() as $k => $v){
                                                $meta[$k] = $v;
                                            }
                                        }
                                    }
                                ?> 
                                <div class="modal-dialog modal-dialog-centered">
									<div class="modal-content">
										<div class="login-box bg-white box-shadow border-radius-10">
											<div class="login-title">
												<h2 class="text-center text-primary">Create Project Year Group</h2>
											</div>
											<form name="save_year" method="post">
                                                <input type="hidden" name="id" value="<?php echo isset($_GET['year_id']) ? $_GET['year_id'] :'' ?>">
												<div class="input-group custom">
													<input type="text" name="year" class="form-control form-control-lg" placeholder="Enter Year(e.g 2023)">
													<div class="input-group-append custom">
														<span class="input-group-text"><i class="icon-copy dw dw-folder-46"></i></span>
													</div>
												</div>
											
												<div class="row col-sm-12">
													<div class="col-sm-6" style="padding-right: 0px; padding-left: 5px;">
                                                        <!-- <button type="button" class="btn btn-primary save_year" type="submit">Save Folder</button> -->
                                                        <input class="btn btn-primary" name="save_year" id="save_year" type="submit" value="Add Year">
											        </div>
                                                    <div class="col-sm-6" style="padding-right: 5px; padding-left: 0px;">
														<button type="button" class="btn btn-primary" style="background-color: #6c757d; border-color: #6c757d;" data-dismiss="modal">Close Modal</button>
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