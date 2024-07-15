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
	$yearId = $paramsArray['year_id'];
	$yearName = $paramsArray['year_name'];

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
$year_id = $yearId;
$year_name = $yearName;

$sql = "SELECT 
            p.project_id,
            p.name,
            p.description,
            p.user_id,
            p.faculty_id,
            p.department_id,
            p.course_id,
            p.year_id,
            p.upload_date,
            p.author_name,
            p.co_author_name,
            p.supervisor_name,
            p.file_type,
            p.file_path,
            p.is_public,
            c.course_name, 
            d.department_name, 
            f.faculty_name,
            y.year
        FROM Projects p
        JOIN Courses c ON p.course_id = c.course_id
        JOIN Departments d ON p.department_id = d.department_id
        JOIN Faculties f ON p.faculty_id = f.faculty_id
        JOIN Years y ON p.year_id = y.year_id
        WHERE p.year_id = $year_id 
        AND p.faculty_id = $faculty_id 
        AND p.department_id = $department_id 
        AND p.course_id = $course_id
        ORDER BY p.upload_date DESC";

$project_results = $conn->query($sql);

?>
<?php
 if(isset($_POST['upload_project']))
{
	$author_name=isset($_POST['author_name']) ? $_POST['author_name'] : ''; // Check for empty value
    $co_author_name=isset($_POST['co_author_name']) ? $_POST['co_author_name'] : '';
	$supervisor_name=isset($_POST['supervisor_name']) ? $_POST['supervisor_name'] : '';
	$department_id=$_POST['department_id'];
	$faculty_id=$_POST['faculty_id'];
	$course_id=$_POST['course_id'];
	$year_id=$_POST['year_id'];
    $description=$_POST['project_topic'];

	if(empty($author_name)){
		echo "<script>alert('Author name is required');</script>";
    } elseif(empty($supervisor_name)){
		echo "<script>alert('Supervisor name is required');</script>";
    }
	else {
		if (empty($id)) {
			if ($_FILES['upload']['tmp_name'] != '') {
				$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['upload']['name'];
				$move = move_uploaded_file($_FILES['upload']['tmp_name'],'assets/uploads/'. $fname);

					if($move){
						$file = $_FILES['upload']['name'];
						$file = explode('.',$file);
						$chk = $conn->query("SELECT * FROM projects where SUBSTRING_INDEX(name,' ||',1) = '".$file[0]."' and course_id = '".$course_id."' and file_type='".$file[1]."' ");
						if($chk->num_rows > 0){
							$file[0] = $file[0] .' ||'.($chk->num_rows);
						}
						$data = " name = '".$file[0]."' ";
						$data .= ", department_id = '".$department_id."' ";
						$data .= ", faculty_id = '".$faculty_id."' ";
						$data .= ", course_id = '".$course_id."' ";
						$data .= ", year_id = '".$year_id."' ";
						$data .= ", author_name = '".$author_name."' ";
						$data .= ", co_author_name = '".$co_author_name."' ";
						$data .= ", supervisor_name = '".$supervisor_name."' ";
						$data .= ", description = '".$description."' ";
						$data .= ", user_id = '".$session_id."' "; 
						$data .= ", file_type = '".$file[1]."' ";
						$data .= ", file_path = '".$fname."' ";
						$data .= ", is_public = 1 ";

						$save = $conn->query("INSERT INTO projects set ".$data);
						if($save)
						{
							$delay = 0;
							header("Refresh: $delay;"); 
							echo "<script>alert('New Project successfully uploaded.','success');</script>";
						}

					}
			
				}
            } else {
                $data = " description = '".$description."' ";
                $data .= ", is_public = 1 ";
                $save = $conn->query("UPDATE projects set ".$data. " where project_id=".$id);
                if($save) {
                    $delay = 0;
                    header("Refresh: $delay;"); 
                    echo "<script>alert('New Project successfully uploaded.','success');</script>";
                 }
        }
	}
}

?>
<body>
    <!-- <?php include('loader.php')?> -->
	
    <?php include('includes/navbar.php')?>

	<?php include('includes/right_sidebar.php')?>

	<?php include('includes/left_sidebar.php')?>

	<div class="mobile-menu-overlay"></div>

	<div class="main-container">
		<div class="pd-ltr-20 xs-pd-20-10">
			<div class="min-height-200px">
				<div class="container pd-0">
					<div class="page-header">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item"><a href="javascript:history.go(-1)">All Projects for <?php echo $course_name; ?> in <?php echo $year_name; ?></a></li>
									</ol>
								</nav>
							</div>
                            <div class="col-md-6 col-sm-12 text-right">
                                <div class="pull-right">
                                    <a href="#" class="btn btn-primary btn-sm scroll-click" rel="content-y"  data-backdrop="static" href="modal" data-toggle="modal" data-target="#modal" role="button" style="background-color: #007bff; border-color: #007bff; "><i class="icon-copy dw dw-upload1"></i>  Upload Project</a>
                                </div>
                                
						    </div>
					    </div>
					
				</div>
                <!-- Simple Datatable start -->
				<div class="card-box mb-30">
					<div class="pd-20">
						<h4 class="text-blue h4">Project Documents</h4>
					</div>
					<div class="pb-20">
						<table class="data-table table stripe hover nowrap">
							<thead>
								<tr>
									<th class="table-plus datatable-nosort">Author</th>
									<th class="table-plus datatable-nosort">Co Author</th>
									<th class="table-plus datatable-nosort">Supervisor</th>
									<th>Project Topic</th>
									<th>Upload Date</th>
									<th class="datatable-nosort">Action</th>
								</tr>
							</thead>
							<tbody>
                                <?php 
                                while($row=$project_results->fetch_assoc()):
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
										<div class="dropdown">
											<a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
												<i class="dw dw-more"></i>
											</a>
											<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
												<a class="dropdown-item" href="view_project.php?project_id=<?php echo $row['project_id']; ?>" target="_blank"><i class="dw dw-eye"></i> View</a>
												<a class="dropdown-item" href="#"><i class="dw dw-edit2"></i> Edit</a>
												<a class="dropdown-item" href="#"><i class="dw dw-delete-3"></i> Delete</a>
											</div>
										</div>
									</td>
								</tr>
                                <?php endwhile; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
            <form method="post" enctype="multipart/form-data">
                <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                             <div class="login-title" style="padding-top: 20px; padding-bottom: 10px;">
                                <h5 class="text-center text-primary" style="color: #6c757d;">Upload Project Document</h5>
                             </div>
                            <div class="weight-500 col-md-12 pd-5" style="padding-right: 15px; padding-left: 15px;">
                                <input type="hidden" name="id" value="<?php echo isset($_GET['project_id']) ? $_GET['project_id'] :'' ?>">
								<input type="hidden" name="department_id" id="department_id" value="<?php echo $department_id; ?>">
								<input type="hidden" name="faculty_id" id="faculty_id" value="<?php echo $faculty_id; ?>">
								<input type="hidden" name="course_id" id="course_id" value="<?php echo $course_id; ?>">
								<input type="hidden" name="year_id" id="year_id" value="<?php echo $year_id; ?>">

                                <div class="form-group">
                                    <div class="custom-file">
                                        <input name="upload" id="upload" type="file" for="upload" class="custom-file-input" accept="application/pdf,application/msword,
  application/vnd.openxmlformats-officedocument.wordprocessingml.document" onchange="validateImage('upload')">
                                        <label class="custom-file-label" for="file" id="selector">Choose file</label>		
                                    </div>
                                </div>
                                <div class="input-group custom">
                                    <input type="text" name="author_name" class="form-control form-control-lg" placeholder="Author Name">
                                    <div class="input-group-append custom">
                                        <span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
                                    </div>
                                </div>
								<div class="input-group custom">
                                    <input type="text" name="co_author_name" class="form-control form-control-lg" placeholder="Co-Author Name">
                                    <div class="input-group-append custom">
                                        <span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
                                    </div>
                                </div>
								<div class="input-group custom">
                                    <input type="text" name="supervisor_name" class="form-control form-control-lg" placeholder="Supervisor Name">
                                    <div class="input-group-append custom">
                                        <span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Project Topic :</label>
                                    <textarea class="form-control" name="project_topic"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <input type="submit" name="upload_project" value="Upload" class="btn btn-primary">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
			<!-- <?php include('includes/footer.php'); ?> -->
		</div>
	</div>
	<!-- js -->
	<!-- js -->
	<script src="../vendors/scripts/core.js"></script>
	<script src="../vendors/scripts/script.min.js"></script>
	<script src="../vendors/scripts/process.js"></script>
	<script src="../vendors/scripts/layout-settings.js"></script>
	<script src="../src/plugins/datatables/js/jquery.dataTables.min.js"></script>
	<script src="../src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
	<script src="../src/plugins/datatables/js/dataTables.responsive.min.js"></script>
	<script src="../src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
	<!-- buttons for Export datatable -->
	<script src="../src/plugins/datatables/js/dataTables.buttons.min.js"></script>
	<script src="../src/plugins/datatables/js/buttons.bootstrap4.min.js"></script>
	<script src="../src/plugins/datatables/js/vfs_fonts.js"></script>
	<!-- Datatable Setting js -->
	<script src="../vendors/scripts/datatable-setting.js"></script></body>

	<script>
		$('.folder-item').dblclick(function(){
		  location.href = 'departments.php?page=files&fid='+$(this).attr('data-id')
		})
        
	</script>

    <script type="text/javascript">
		var loader = function(e) {
			let file = e.target.files;

			let show = file[0].name;
			let output = document.getElementById("selector");
			output.innerHTML = show;
			output.classList.add("active");
		};

		let fileInput = document.getElementById("upload");
		fileInput.addEventListener("change", loader);
	</script>
	<script type="text/javascript">
		 function validateImage(id) {
            var fileName = document.getElementById(id).value;
            var idxDot = fileName.lastIndexOf(".") + 1;
            var extFile = fileName.substr(idxDot, fileName.length).toLowerCase();
            if (extFile=="pdf" || extFile=="docx" || extFile=="doc"){
                //TO DO
            }else{
                alert('Please select a valid document file');
                document.getElementById(id).value = '';
                return false;
            }   
		    if (fileName.size > 1050000) {
		        alert('Max Upload size is 1MB only');
		        document.getElementById(id).value = '';
		        return false;
		    }
		    return true;
		}
	</script>

</body>
</html>