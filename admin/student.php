<?php include('includes/header.php')?>
<?php include('../includes/session.php')?>
<?php
if (isset($_GET['delete'])) {
	$delete = $_GET['delete'];
	$sql = "DELETE FROM tblemployees where emp_id = ".$delete;
	$result = mysqli_query($conn, $sql);
	if ($result) {
		echo "<script>alert('Staff deleted Successfully');</script>";
     	echo "<script type='text/javascript'> document.location = 'staff.php'; </script>";
		
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
		<div class="pd-ltr-20">
			<div class="title pb-20">
				<h2 class="h3 mb-0">Student List</h2>
			</div>

			<div class="card-box mb-30">
				<div class="pd-20">
						<h2 class="text-blue h4">ALL STUDENTS</h2>
					</div>
				<div class="pb-20">
					<table class="data-table table stripe hover nowrap">
						<thead>
							<tr>
								<th class="table-plus">FULL NAME</th>
								<th>EMAIL</th>
                                <th>PHONE NO.</th>
								<th>FACULTY</th>
								<th>DEPARTMENT</th>
								<th>PROGRAMME</th>
								<th class="datatable-nosort">ACTION</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								 <?php
                                    $student_query = mysqli_query($conn,"SELECT u.user_id, u.first_name, u.last_name, u.school_id, 
                                                                    u.email,
                                                                    u.faculty_id,
                                                                    u.department_id,
                                                                    u.course_id,
                                                                    u.phone_number,
                                                                    u.location,
                                                                    u.reg_date,
                                                                    u.role,
                                                                    c.course_name, 
                                                                    d.department_name, 
                                                                    f.faculty_name
                                                                    FROM Users u
                                                                    JOIN Courses c ON u.course_id = c.course_id
                                                                    JOIN Departments d ON u.department_id = d.department_id
                                                                    JOIN Faculties f ON u.faculty_id = f.faculty_id
                                                                    WHERE u.role = 'student' 
                                                                    ORDER BY u.reg_date DESC") or die(mysqli_error($conn));
                                    while ($row = mysqli_fetch_array($student_query)) {
                                    $id = $row['user_id'];
		                         ?>

								<td class="table-plus">
									<div class="name-avatar d-flex align-items-center">
										<div class="avatar mr-2 flex-shrink-0">
											<img src="<?php echo (!empty($row['location'])) ? '../uploads/'.$row['location'] : '../uploads/NO-IMAGE-AVAILABLE.jpg'; ?>" class="border-radius-100 shadow" width="40" height="40" alt="">
										</div>
										<div class="txt">
											<div class="weight-600"><?php echo $row['first_name'] . " " . $row['last_name']; ?></div>
										</div>
									</div>
								</td>
								<td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['phone_number']; ?></td>
	                            <td><?php echo $row['faculty_name']; ?></td>
								<td><?php echo $row['department_name']; ?></td>
								<td><?php echo $row['course_name']; ?></td>
								<td>
									<div class="dropdown">
										<a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
											<i class="dw dw-more"></i>
										</a>
										<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
											<a class="dropdown-item" href="chat.php?sender=<?php echo $session_id; ?>&receiver=<?php echo $row['user_id']; ?>"><i class="micon dw dw-chat3"></i> Chat Staff</a>
											<a class="dropdown-item" href="edit_staff.php?edit=<?php echo $row['user_id'];?>"><i class="dw dw-edit2"></i> Edit</a>
											<a class="dropdown-item" href="staff.php?delete=<?php echo $row['user_id'] ?>"><i class="dw dw-delete-3"></i> Delete</a>
										</div>
									</div>
								</td>
							</tr>
							<?php } ?>  
						</tbody>
					</table>
			   </div>
			</div>

			<?php include('includes/footer.php'); ?>
		</div>
	</div>
	<!-- js -->

	<?php include('includes/scripts.php')?>
</body>
</html>