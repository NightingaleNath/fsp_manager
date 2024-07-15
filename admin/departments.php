<?php include('includes/header.php')?>
<?php include('../includes/session.php')?>

<?php
if (isset($_GET['faculty_id'])) {
    $faculty_id = $_GET['faculty_id'];
    echo "<script>console.log('Received faculty_id: " . $faculty_id . "');</script>";
} else {
    echo "<script>console.log('No faculty_id received from URL');</script>";
    exit;
}

$faculty_id = intval($faculty_id);
$sql = "SELECT * FROM Departments WHERE faculty_id = $faculty_id ORDER BY department_name ASC";
$department = $conn->query($sql);

if (!$department) {
	echo "<script>console.log('Error fetching departments: " . $conn->error. "');</script>";
    exit;
}

// Debug output
echo "<script>console.log('Number of rows fetched: " . $department->num_rows . "');</script>";
?>

<?php 
if (isset($_POST['save_department'])) {
    $department_name = $_POST['name'];
	$faculty_id = $_POST['faculty_id'];

    $check = $conn->query("SELECT * FROM Departments WHERE department_name = '$department_name' AND faculty_id = $faculty_id")->num_rows;

    if ($check > 0) {
        echo "<script>alert('Department name already exists','success');</script>";
    } else {
        $save = $conn->query("INSERT INTO Departments (department_name, faculty_id) VALUES ('$department_name', $faculty_id)");
        if ($save) {
            $delay = 0;
            header("Refresh: $delay;");
            echo "<script>alert('New Department successfully added.','success');</script>";
        } else {
            echo "<script>alert('Failed to add new department.','error');</script>";
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
									<h4>Project by Department</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
										<li class="breadcrumb-item active" aria-current="page" style="color: #1b00ff;">Project Department</li>
									</ol>
								</nav>
							</div>
                            <div class="col-md-6 col-sm-12 text-right">
                                <div class="pull-right">
                                    <a href="#" class="btn btn-primary btn-sm scroll-click" rel="content-y"  data-backdrop="static" data-toggle="modal" data-target="#login-modal" role="button" style="background-color: #007bff; border-color: #007bff; "><i class="icon-copy ion-plus"></i>  Add Department</a>
                                </div>
                                <div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
								<div class="modal-dialog modal-dialog-centered">
									<div class="modal-content">
										<div class="login-box bg-white box-shadow border-radius-10">
											<div class="login-title">
												<h2 class="text-center text-primary">Create Department Folder</h2>
											</div>
											<form name="save_department" method="post">
												<input type="hidden" name="department_id" value="<?php echo isset($_GET['department_id']) ? $_GET['department_id'] :'' ?>">
		                                        <input type="hidden" name="faculty_id" value="<?php echo isset($_GET['faculty_id']) ? $_GET['faculty_id'] :'' ?>">
												<div class="input-group custom">
													<input type="text" name="name" class="form-control form-control-lg" placeholder="Department Name">
													<div class="input-group-append custom">
														<span class="input-group-text"><i class="icon-copy dw dw-folder-46"></i></span>
													</div>
												</div>
											
												<div class="row col-sm-12">
													<div class="col-sm-6" style="padding-right: 0px; padding-left: 5px;">
                                                        <input class="btn btn-primary" name="save_department" id="save_department" type="submit" value="Create">
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
                            while($row = $department->fetch_assoc()): ?>
                             <li class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
								<div class="contact-directory-box" style="min-height: 0px;">
									<div class="contact-dire-info text-center">
										<div class="producct-img" style="margin-bottom: 10px;">
											<span>
												<?php
												  if (str_contains($row['department_name'], "Computer Science")) {
													echo '<img src="../vendors/images/product-img4.jpg" alt="">';
												  } else if (str_contains($row['department_name'], "Mathematics")) {
													echo '<img src="../vendors/images/product-img1.jpg" alt="">';
												  }else if (str_contains($row['department_name'], "Physics")) {
													echo '<img src="../vendors/images/product-img2.jpg" alt="">';
												  }else if (str_contains($row['department_name'], "Chmistry")) {
													echo '<img src="../vendors/images/product-img1.jpg" alt="">';
												  } else if (str_contains($row['department_name'], "Eng")) {
													echo '<img src="../vendors/images/product-img4.jpg" alt="">';
												  } else if (str_contains($row['department_name'], "Electrical")) {
													echo '<img src="../vendors/images/product-img4.jpg" alt="">';
												  }
												  else {
													echo '<img src="../vendors/images/product-img3.jpg" alt="">';
												  }
												?>
											</span>
										</div>
										
										
										<div class="profile-sort-desc">
											View all past questions for this Department
										</div>
									</div>
									<div class="view-contact folder-item" data-id="<?php echo $row['department_id'] ?>" data-name="<?php echo $row['department_name'] ?>" data-faculty="<?php echo $_GET['faculty_id'] ?>">
										<a><?php echo $row['department_name'] ?></a>
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
			var params = btoa('department_id=' + departmentId + '&department_name=' + departmentName + '&faculty_id=' + facultyId);
			location.href = 'programme.php?params=' + params;
		})
	</script>
</body>
</html>