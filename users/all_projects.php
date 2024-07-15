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

<?php
 if(isset($_POST['upload_project']))
{
	$author_name=$_POST['author_name'];
    $folder_id=$_POST['folder_id'];
    $description=$_POST['project_topic'];
    
    if(empty($id)){
		if($_FILES['upload']['tmp_name'] != ''){
            $fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['upload']['name'];
            $move = move_uploaded_file($_FILES['upload']['tmp_name'],'assets/uploads/'. $fname);

            if($move){
                $file = $_FILES['upload']['name'];
                $file = explode('.',$file);
                $chk = $conn->query("SELECT * FROM project_files where SUBSTRING_INDEX(name,' ||',1) = '".$file[0]."' and folder_id = '".$folder_id."' and file_type='".$file[1]."' ");
                if($chk->num_rows > 0){
                    $file[0] = $file[0] .' ||'.($chk->num_rows);
                }
                $data = " name = '".$file[0]."' ";
                $data .= ", folder_id = '".$folder_id."' ";
                $data .= ", author_name = '".$author_name."' ";
                $data .= ", description = '".$description."' ";
                $data .= ", user_id = '".$_SESSION['login_id']."' ";
                $data .= ", file_type = '".$file[1]."' ";
                $data .= ", file_path = '".$fname."' ";
                $data .= ", is_public = 1 ";

                $save = $conn->query("INSERT INTO project_files set ".$data);
                if($save)
                {
                    $delay = 0;
                    header("Refresh: $delay;"); 
                    echo "<script>alert('New Folder successfully added.','success');</script>";
                }

            }
    
            }
            }else{
                $data = " description = '".$description."' ";
                $data .= ", is_public = 1 ";
                $save = $conn->query("UPDATE project_files set ".$data. " where id=".$id);
                if($save)
                 {
                    $delay = 0;
                    header("Refresh: $delay;"); 
                    echo "<script>alert('New Folder successfully added.','success');</script>";
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
                                    <h4>All Projects</h4>
                                </div>
                                <nav aria-label="breadcrumb" role="navigation">
                                    <ol class="breadcrumb">
                                        
                                        <li class="breadcrumb-item"><a href="javascript:history.go(-1)">Search by All Projects</a></li>
                                        <li class="breadcrumb-item active" aria-current="page" style="color: #1b00ff;"></li>
                                    </ol>
                                </nav>
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
											<a href="../admin/user_view_project.php?project_id=<?php echo $row['project_id']; ?>" target="_blank"><i class="icon-copy dw dw-view"></i></a>
										</div>
									</td>
								</tr>
                                <?php endwhile; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<?php include('includes/footer.php'); ?>
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
		  location.href = 'documents.php?page=files&fid='+$(this).attr('data-id')
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