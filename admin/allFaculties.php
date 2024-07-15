<?php include('includes/header.php')?>
<?php include('../includes/session.php')?>
<?php 
$sql = "SELECT * FROM faculties";
$faculty = $conn->query($sql);

?>
<?php
 if(isset($_POST['save_faculty']))
{
	$faculty_name = $_POST['name'];
        
	$check = $conn -> query("SELECT * FROM faculties where faculty_name ='$name'") -> num_rows;

	if ($check > 0) {
		echo "<script>alert('Faculty name already exist','success');</script>";
	} else {
		$save = $conn -> query("INSERT INTO Faculties (faculty_name) VALUES ('$faculty_name')");
		if ($save) {
			$delay = 0;
			header("Refresh: $delay;"); 
			echo "<script>alert('New Faculty successfully added.','success');</script>";
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
									<h4>All Faculties</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
										<li class="breadcrumb-item active" aria-current="page" style="color: #1b00ff;">Faculties</li>
									</ol>
								</nav>
							</div>
                            <div class="col-md-6 col-sm-12 text-right">
                                <div class="pull-right">
                                    <a href="#" class="btn btn-primary btn-sm scroll-click" rel="content-y"  data-backdrop="static" data-toggle="modal" data-target="#login-modal" role="button" style="background-color: #007bff; border-color: #007bff; "><i class="icon-copy ion-plus"></i>  Add Faculty</a>
                                </div>
                                <div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
								<div class="modal-dialog modal-dialog-centered">
									<div class="modal-content">
										<div class="login-box bg-white box-shadow border-radius-10">
											<div class="login-title">
												<h2 class="text-center text-primary">Create New Faculty</h2>
											</div>
											<form name="save_faculty" method="post">
												<input type="hidden" name="faculty_id" value="<?php echo isset($_GET['faculty_id']) ? $_GET['faculty_id'] :'' ?>">
												<div class="input-group custom">
													<input type="text" name="name" class="form-control form-control-lg" placeholder="Faculty Name">
													<div class="input-group-append custom">
														<span class="input-group-text"><i class="icon-copy dw dw-folder-46"></i></span>
													</div>
												</div>
											
												<div class="row col-sm-12">
													<div class="col-sm-6" style="padding-right: 0px; padding-left: 5px;">
                                                        <input class="btn btn-primary" name="save_faculty" id="save_faculty" type="submit" value="Create">
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
                            while($row = $faculty->fetch_assoc()):
                            ?>
                             <li class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
								<div class="contact-directory-box" style="min-height: 0px;">
									<div class="contact-dire-info text-center">
										<div class="producct-img" style="margin-bottom: 10px;">
											<span>
												<?php
												  echo '<img src="../vendors/images/fac.jpg" alt="">';
												?>
											</span>
										</div>
										<!-- 										
										
										<div class="profile-sort-desc">
											View all Departments under this 
										</div> -->
									</div>
									<div class="view-contact folder-item" data-id="<?php echo $row['faculty_id'] ?>">
										<a><?php echo $row['faculty_name'] ?></a>
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
		  location.href = 'departments.php?faculty_id='+$(this).attr('data-id')
		})
	</script>
</body>
</html>