<?php include('includes/header.php')?>
<?php include('../includes/session.php')?>

<?php
if (isset($_POST['faculty_id'])) {
    $faculty_id = intval($_POST['faculty_id']);

    $sql = "SELECT department_id, department_name FROM departments WHERE faculty_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $faculty_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $departmentsOptions = '<option value="" selected="" disabled>Select Department</option>';
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $departmentsOptions .= '<option value="' . htmlspecialchars($row['department_id']) . '">' . htmlspecialchars($row['department_name']) . '</option>';
        }
    } else {
        $departmentsOptions .= '<option value="" disabled>No Departments</option>';
    }

    echo $departmentsOptions;
    exit; 
}

if (isset($_POST['department_id'])) {
    $department_id = intval($_POST['department_id']);

    $sql = "SELECT course_id, course_name FROM courses WHERE department_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $department_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $coursesOptions = '<option value="" selected="" disabled>Select Programme</option>';
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $coursesOptions .= '<option value="' . htmlspecialchars($row['course_id']) . '">' . htmlspecialchars($row['course_name']) . '</option>';
        }
    } else {
        $coursesOptions .= '<option value="" disabled>No Programmes</option>';
    }

    echo $coursesOptions;
    exit;
}
?>

<?php
if (isset($_POST['add_student'])) {
	
	$fname=$_POST['firstname'];
	$lname=$_POST['lastname'];   
	$email=$_POST['email']; 
	$password=md5($_POST['password']); 
	$studentId=$_POST['student_id']; 
	$phonenumber=$_POST['phonenumber']; 
	$faculty=$_POST['faculty']; 
	$department=$_POST['department']; 
	$programme=$_POST['programme']; 
	$role="student"; 

    $check = $conn->query("select * from users where email = '$email'") -> num_rows;

    if ($check > 0) {
        echo "<script>alert('User already exists','success');</script>";
    } else {
        $save = $conn->query("INSERT INTO users (first_name, last_name, school_id, email, password, faculty_id, 
                                                department_id, course_id, phone_number, location, role)
                                                VALUES ('$fname', '$lname', '$studentId', '$email', '$password', $faculty, $department,
                                                        $programme, '$phonenumber', 'NO-IMAGE-AVAILABLE.jpg', '$role')");
        if ($save) {
            $delay = 0;
            header("Refresh: $delay;");
            echo "<script>alert('New User successfully added.','success');</script>";
        } else {
            echo "<script>alert('Failed to add new User.','error');</script>";
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
		<div class="xs-pd-20-10 pd-ltr-20">
            <div class="min-height-200px">
                <div class="page-header">
					<div class="row">
						<div class="col-md-6 col-sm-12">
							<div class="title">
								<h4>Staff Portal</h4>
							</div>
							<nav aria-label="breadcrumb" role="navigation">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
									<li class="breadcrumb-item active" aria-current="page">Staff Module</li>
								</ol>
							</nav>
						</div>
					</div>
				</div>
                <div class="pd-20 card-box mb-30">
                    <div class="clearfix">
						<div class="pull-left">
							<h4 class="text-blue h4">Staff Form</h4>
							<p class="mb-20"></p>
						</div>
					</div>
                    <div class="wizard-content">
                        <form method="post" novalidate="">
                            <div class="row">
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label >First Name :</label>
                                        <input name="firstname" type="text" class="form-control wizard-required" required="true" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label >Last Name :</label>
                                        <input name="lastname" type="text" class="form-control" required="true" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label>Email Address :</label>
                                        <input name="email" type="email" class="form-control" required="true" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label>Password :</label>
                                        <input name="password" type="password" placeholder="**********" class="form-control" required="true" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label >Student ID :</label>
                                        <input name="student_id" type="text" class="form-control" required="true" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label>Phone Number :</label>
                                        <input name="phonenumber" type="text" class="form-control" required="true" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label>Faculty :</label>
                                        <?php
                                            // Fetch all Faculty from the database
                                            $sql = "SELECT faculty_id, faculty_name FROM faculties";
                                            $result = mysqli_query($conn, $sql);

                                            $facultyOptions = '<option value="" disabled selected>Select Faculty</option>';
                                            if ($result && mysqli_num_rows($result) > 0) {
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    $facultyOptions .= '<option value="' . htmlspecialchars($row['faculty_id']) . '">' . htmlspecialchars($row['faculty_name']) . '</option>';
                                                }
                                            }
                                        ?>
                                        <select id="faculty" name="faculty" class="custom-select form-control" required="true" autocomplete="off">
                                            <?php echo $facultyOptions; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label>Department :</label>
                                        <select id="department" name="department" class="custom-select form-control" required="true" autocomplete="off">
                                             <option value="" disabled selected>Select Department</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label>Programme :</label>
                                        <select id="programme" name="programme" class="custom-select form-control" required="true" autocomplete="off">
                                            <option value="" disabled selected>Select Programme</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-center">
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <div class="modal-footer justify-content-center">
                                            <button class="btn btn-primary" name="add_student" id="add_student" data-toggle="modal">Add&nbsp;Staff</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
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

    <script>
        $(document).ready(function() {
            $('#faculty').change(function() {
                var faculty_id = $(this).val();
                if (faculty_id) {
                    $.ajax({
                        url: '',
                        type: 'POST',
                        data: { faculty_id: faculty_id },
                        success: function(response) {
                            console.log('RESPONSE HERE: ' + response);
                            try {
                                $('#department').html(response);
                                $('#programme').html('<option value="">Select Programme</option>');
                            } catch (e) {
                                console.error('Error parsing JSON:', e);
                                console.error('Response:', response);
                            }
                        }
                    });
                } else {
                    $('#department').html('<option value="">Select Department</option>');
                }
            });

            $('#department').change(function() {
                var department_id = $(this).val();
                if (department_id) {
                    $.ajax({
                        url: '',
                        type: 'POST',
                        data: { department_id: department_id },
                        success: function(response) {
                            console.log('RESPONSE HERE: ' + response);
                            try {
                                $('#programme').html(response);
                            } catch (e) {
                                console.error('Error parsing JSON:', e);
                                console.error('Response:', response);
                            }
                        }
                    });
                } else {
                    $('#programme').html('<option value="">Select Programme</option>');
                }
            });
        });
    </script>
</body>
</html>