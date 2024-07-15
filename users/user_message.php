<?php
if (isset($_POST['send'])) {
    if (isset($_POST['request_id'])) {
        $request_id = $_POST['request_id'];
    } else {
        exit('Error: request_id is not set in $_POST');
    }

    if (!isset($_SESSION['firstname']) || !isset($_SESSION['lastname']) || !isset($_SESSION['user_email'])) {
        exit('Error: Session variables are not set');
    }

    $request_name = $_SESSION['firstname'] . " " . $_SESSION['lastname'];
    $request_email = $_SESSION['user_email'];
    $request_message = $_POST['request_message'];
    $review_message = $_POST['review_message'];
    // Check for empty values
    if (empty($request_name) || empty($request_email) || empty($request_message)) {
		echo "<script>alert('Please fill in all fields.','success');</script>";
    }
    else {
        if ($_POST['notification'] == 'true') {
        // Update existing record
            $sql = "UPDATE requestmessages SET message='$request_message' WHERE request_id='$request_id'";
        } else {
            // Insert new record
            $sql = "INSERT INTO requestmessages (name, email, message, reviewed) VALUES ('$request_name', '$request_email', '$request_message', '$review_message')";
        }

        // Execute SQL query
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Review sent successfully.','success');</script>";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }
}
?>
<style>
    .name-email {
        display: inline-block;
        margin-right: 10px;
    }

    .name-email h3 {
        margin: 0;
    }

    .name-email h6 {
        font-size: 14px; /* Adjust the font size as per your need */
        margin: 0;
    }
</style>

<!-- Write message -->
<div class="user-notification">
    <div class="dropdown">
        <a class="dropdown-toggle no-arrow" href="#" onclick="openWriteMessage()" role="button" data-toggle="dropdown">
            <i class="icon-copy dw dw-writing"></i>
        </a>
        <form method="post">
            <input type="hidden" name="notification" id="notification" value="">
            <input type="hidden" name="request_id" id="request_id">
            <div id="write-message-dialog" class="dropdown-menu dropdown-menu-right">
                <!-- <div class="form-group row">
                    <label class="col-sm-12 col-md-2 col-form-label">From</label>
                    <div class="col-sm-12 col-md-10">
                        <input class="form-control form-control-sm form-control-line" type="text" id="request_name" name="request_name">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-12 col-md-2 col-form-label">Email</label>
                    <div class="col-sm-12 col-md-10">
                        <input class="form-control form-control-sm form-control-line" type="text" id="request_email" name="request_email">
                    </div>
                </div> -->
                <div class="form-group row">
                    <label class="col-sm-12 col-md-2 col-form-label">Message</label>
                    <div class="col-sm-12 col-md-10">
                        <textarea class="form-control" id="request_message" name="request_message" style="height: 100px;"></textarea>
                    </div>
                </div>
                <div class="form-group row" id="review-message-group" style="display:none;">
                    <label class="col-sm-12 col-md-2 col-form-label">Review</label>
                    <div class="col-sm-12 col-md-10">
                        <textarea class="form-control" id="review_message" name="review_message" style="height: 60px;"></textarea>
                    </div>
                </div>
                <div class="text-right">
                    <button class="btn btn-primary" name="send">Send</button>
                    <button class="btn btn-secondary" data-dismiss="modal" onclick="closeWriteMessageDialog()">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Notification Panel -->
<div class="user-notification">
    <div class="dropdown">
        <a class="dropdown-toggle no-arrow" href="#" role="button" data-toggle="dropdown">
            <i class="icon-copy dw dw-notification"></i>
            <span class="badge notification-active"></span>
        </a>
        <div class="dropdown-menu dropdown-menu-right">
            <div class="notification-list mx-h-350 customscroll">
                <?php $query= mysqli_query($conn,"select * from users where user_id = '$session_id'")or die(mysqli_error());
								$record = mysqli_fetch_array($query);
						?>
                <ul>
                    <?php
                    $sql = "SELECT * FROM requestmessages WHERE email = '".$record['email']."'";

                    // Execute SQL query
                    $result = mysqli_query($conn, $sql);

                    // Check if there are any results
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<li>
                                    <a href="#" onclick="openWriteMessageDialog(\'' . $row['name'] . '\', \'' . $row['email'] . '\', \'' . $row['message'] . '\', \'' . $row['reviewed'] . '\', \'' . $row['request_id'] . '\')">
                                        <img src="' . ((!empty($row['location'])) ? '../uploads/'.$row['location'] : '../uploads/NO-IMAGE-AVAILABLE.jpg') . '" alt="">
                                        <h3>' . $row['name'] . '</h3>
                                        <span class="name-email"><h6>' . strtolower($row['email']) . '</h6></span>
                                        <p>' . $row['message'] . '</p>
                                    </a>
                                </li>';
                        }
                    }
                    ?>
                    
                </ul>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function openWriteMessageDialog(requestName, requestEmail, requestMessage, reviewMessage, requestId) {
        // Populate the From and Message fields
        document.getElementById("request_name").value = requestName;
        document.getElementById("request_email").value = requestEmail; 
        document.getElementById("request_message").value = requestMessage;
        document.getElementById("review_message").value = reviewMessage;
        document.getElementById("request_id").value = requestId;

        // Check if the dialog was opened from the notification panel
        if (typeof requestName !== "undefined" && typeof requestEmail !== "undefined" && typeof requestMessage !== "undefined") {
            $('#notification').val('true');
            $('#request_email').prop('readonly', true);
            $('#request_name').prop('readonly', true);
            $('#review_message').prop('readonly', true);
        } else {
            $('#notification').val('');
            $('#request_email').prop('readonly', false);
            $('#request_name').prop('readonly', false);
            $('#review_message').prop('readonly', false);
        }

        $('#review-message-group').show();
        // Show the Write message dialog
        $('#write-message-dialog').show();
    }

    function openWriteMessage() {
        var userEmail = '<?php echo $record["EmailId"] ?>'; // Get user's email from PHP variable
        var userName = '<?php echo $record["FirstName"] ?> <?php echo $record["LastName"] ?>'; // Get user's name from PHP variable
        document.getElementById("request_name").value = userName;
        document.getElementById("request_email").value = userEmail;

         $('#request_email').prop('readonly', true);
         $('#request_name').prop('readonly', true);

        $('#write-message-dialog').show();
    }

    function closeWriteMessageDialog() {
    
        // Populate the From and Message fields
        document.getElementById("request_name").value = '';
        document.getElementById("request_email").value = '';
        document.getElementById("request_message").value = '';
        document.getElementById("request_id").value = '';

        $('#review-message-group').hide();

        $('#write-message-dialog').hide();
    }
</script>