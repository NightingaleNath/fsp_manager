<?php include('includes/header.php')?>
<?php include('../includes/session.php')?>
<?php 
$sql = "SELECT 
            p.project_id, p.name, p.description, p.year_id, 
           	p.author_name,
            p.co_author_name,
            p.supervisor_name,
			p.file_type,
            p.file_path,
            p.is_public,
			p.upload_date,
            c.course_name, 
            d.department_name, 
            f.faculty_name,
            y.year
        FROM Projects p
        JOIN Courses c ON p.course_id = c.course_id
        JOIN Departments d ON p.department_id = d.department_id
        JOIN Faculties f ON p.faculty_id = f.faculty_id
        JOIN Years y ON p.year_id = y.year_id
        ORDER BY f.faculty_name, d.department_name, c.course_name, y.year, p.name";

$results = $conn->query($sql);
?>
<body>
	<?php include('loader.php')?>

	<?php include('includes/navbar.php')?>

	<?php include('includes/right_sidebar.php')?>

	<?php include('includes/left_sidebar.php')?>

	<div class="mobile-menu-overlay"></div>

	<div class="main-container">
		<div class="xs-pd-20-10 pd-ltr-20">

			<div class="title pb-20">
				<h2 class="h3 mb-0">Project Overview</h2>
			</div>

			<div class="row pb-10">
				<div class="col-xl-3 col-lg-3 col-md-6 mb-20">
					<div class="card-box height-100-p widget-style3">
						<div class="d-flex flex-wrap">
							<div class="widget-data">
								<?php
								$sql = "SELECT COUNT(*) AS student_count FROM users WHERE role = 'student'";
								$query = mysqli_query($conn, $sql);
								$result = mysqli_fetch_assoc($query);
								$student_count = $result['student_count'];
								?>
								<div class="weight-700 font-24 text-dark"><?php echo $student_count; ?></div>
								<div class="font-14 text-secondary weight-500">Students</div>
							</div>
							<div class="widget-icon">
								<div class="icon" data-color="#00eccf"><i class="icon-copy fi-torsos-all"></i></div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-3 col-lg-3 col-md-6 mb-20">
					<div class="card-box height-100-p widget-style3">
						<div class="d-flex flex-wrap">
							<div class="widget-data">
								<?php
								$sql = "SELECT COUNT(*) AS depart_count FROM departments";
								$query = mysqli_query($conn, $sql);
								$result = mysqli_fetch_assoc($query);
								$depart_count = $result['depart_count'];
								?>
								<div class="weight-700 font-24 text-dark"><?php echo $depart_count; ?></div>
								<div class="font-14 text-secondary weight-500">Department</div>
							</div>
							<div class="widget-icon">
								<div class="icon" data-color="#ff5b5b"><span class="icon-copy fa fa-building"></span></div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-3 col-lg-3 col-md-6 mb-20">
					<div class="card-box height-100-p widget-style3">
						<div class="d-flex flex-wrap">
							<div class="widget-data">
								<?php
								$sql = "SELECT COUNT(*) AS project_count FROM projects";
								$query = mysqli_query($conn, $sql);
								$result = mysqli_fetch_assoc($query);
								$project_count = $result['project_count'];
								?>
								<div class="weight-700 font-24 text-dark"><?php echo $project_count; ?></div>
								<div class="font-14 text-secondary weight-500">Total Projects</div>
							</div>
							<div class="widget-icon">
								<div class="icon"><i class="icon-copy fa fa-book" aria-hidden="true"></i></div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-3 col-lg-3 col-md-6 mb-20">
					<div class="card-box height-100-p widget-style3">
						<div class="d-flex flex-wrap">
							<div class="widget-data">
								<?php
								$sql = "SELECT COUNT(*) AS admin_count FROM users WHERE role = 'admin'";
								$query = mysqli_query($conn, $sql);
								$result = mysqli_fetch_assoc($query);
								$admin_count = $result['admin_count'];
								?>
								<div class="weight-700 font-24 text-dark"><?php echo $admin_count; ?></div>
								<div class="font-14 text-secondary weight-500">Administrator</div>
							</div>
							<div class="widget-icon">
								<div class="icon" data-color="#00eccf"><i class="icon-copy fi-torsos-all"></i></div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="card-box mb-30">
				<div class="h5 pd-20 mb-0">Recent Document</div>
				<table class="data-table table nowrap">
					<thead>
						<tr>
							<th class="table-plus">Author</th>
							<th class="table-plus">Co Author</th>
							<th class="table-plus">Supervisor</th>
							<th>Project Topic</th>
							<th>Admit Date</th>
							<th class="datatable-nosort">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php 
                                while($row=$results->fetch_assoc()):
                                    $name = explode(' ||',$row['name']);
                                    $name = isset($name[1]) ? $name[0] ." (".$name[1].").".$row['file_type'] : $name[0] .".".$row['file_type'];
                                    $img_arr = array('png','jpg','jpeg','gif','psd','tif');
                                    $doc_arr =array('doc','docx');
                                    $pdf_arr =array('pdf','ps','eps','prn');
                                    $icon ='fa-file';
                                    if(in_array(strtolower($row['file_type']),$img_arr))
                                        $icon ='fa-image';
                                    if(in_array(strtolower($row['file_type']),$doc_arr))
                                        $icon ='fa-file-word';
                                    if(in_array(strtolower($row['file_type']),$pdf_arr))
                                        $icon ='fa-file-pdf';
                                    if(in_array(strtolower($row['file_type']),['xlsx','xls','xlsm','xlsb','xltm','xlt','xla','xlr']))
                                        $icon ='fa-file-excel';
                                    if(in_array(strtolower($row['file_type']),['zip','rar','tar']))
                                        $icon ='fa-file-archive';
                                ?>
								<tr class='file-item' data-id="<?php echo $row['project_id'] ?>" data-name="<?php echo $name ?>">
									<td class="table-plus"><?php echo $row['author_name'] ?>
                                      <input type="text" class="rename_file" value="<?php echo $row['name'] ?>" data-id="<?php echo $row['project_id'] ?>" data-type="<?php echo $row['file_type'] ?>" style="display: none">
                                    </td>
									<td class="table-plus"><?php echo $row['co_author_name'] ?></td>
									<td class="table-plus"><?php echo $row['supervisor_name'] ?></td>
									<td><?php echo $row['description'] ?> </td>
									<td><?php echo date('jS F, Y h:i A', strtotime($row['upload_date'])) ?></td>
									<td>
										<div class="table-actions">
											<a href="view_project.php?project_id=<?php echo $row['project_id']; ?>" target="_blank" data-color="#265ed7"><i class="icon-copy dw dw-view"></i></a>
											<a href="download_file.php?project_id=<?php echo $row['project_id']; ?>" target="_blank" data-color="#00FF00"><i class="icon-copy dw dw-download"></i></a>
											<a href="#" onclick="deleteRecord(<?php echo $row['project_id']; ?>)" data-color="#C62C2C"><i class="dw dw-delete-3"></i></a>
										</div>
									</td>
								</tr>
						<?php endwhile; ?>
					</tbody>
				</table>
			</div>

			<!-- TODO -->

			<?php include('includes/footer.php'); ?>
		</div>
	</div>
	<!-- js -->
	<?php include('includes/scripts.php')?>
	<script>
		function deleteRecord(recordId) {
			if (confirm("Are you sure you want to delete this record?")) {
				// Create a new form element
				var form = document.createElement('form');
				form.method = 'POST';
				form.action = 'delete_file.php';

				// Add a hidden input field for the record ID
				var input = document.createElement('input');
				input.type = 'hidden';
				input.name = 'id';
				input.value = recordId;
				form.appendChild(input);

				// Submit the form
				document.body.appendChild(form);
				form.submit();
			}
		}
	</script>

</body>
</html>