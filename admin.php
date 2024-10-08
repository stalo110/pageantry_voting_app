<?php
session_start();
include 'db.php';

// Handle adding a contestant
if (isset($_POST['add_contestant'])) {
    $name = $_POST['contestant_name'];
    $about = $_POST['about_contestant'];
    $profile_picture = $_FILES['profile_picture']['name'];

    // Upload profile picture
    if (!empty($profile_picture)) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($profile_picture);
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
            echo "Profile picture uploaded successfully.";
        } else {
            echo "Error uploading profile picture.";
        }
    }

    if (!empty($name)) {
        $query = "INSERT INTO contestants (name, about_contestant, profile_picture, votes) 
                  VALUES ('$name', '$about', '$profile_picture', 0)";
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Contestant added successfully!'); window.location.href = 'admin.php';</script>";
        } else {
            echo "<script>alert('Error adding contestant: " . mysqli_error($conn) . "');</script>";
        }
    } else {
        echo "<script>alert('Please enter a contestant name!');</script>";
    }
}

// Handle deletion of a contestant
if (isset($_POST['delete_contestant'])) {
    $contestant_id = $_POST['contestant_id'];

    // First, delete the contestant's profile picture from the server
    $result = mysqli_query($conn, "SELECT profile_picture FROM contestants WHERE id = $contestant_id");
    $contestant = mysqli_fetch_assoc($result);
    $profile_picture = $contestant['profile_picture'];
    
    if ($profile_picture && file_exists("uploads/" . $profile_picture)) {
        unlink("uploads/" . $profile_picture); // Delete the file from the server
    }

    // Then, delete the contestant from the database
    $delete_query = "DELETE FROM contestants WHERE id = $contestant_id";
    
    if (mysqli_query($conn, $delete_query)) {
        echo "<script>alert('Contestant deleted successfully!'); window.location.href = 'admin.php';</script>";
    } else {
        echo "<script>alert('Error deleting contestant: " . mysqli_error($conn) . "');</script>";
    }
}


// Fetch contestant details if 'edit_contestant' is set
if (isset($_POST['edit_contestant'])) {
    $contestant_id = $_POST['contestant_id'];
    $result = mysqli_query($conn, "SELECT * FROM contestants WHERE id = $contestant_id");
    $contestant_data = mysqli_fetch_assoc($result);

    // Check if contestant data is fetched successfully
    if ($contestant_data) {
        $contestant_name = $contestant_data['name'];
        $about_contestant = $contestant_data['about_contestant'];
        $profile_picture = $contestant_data['profile_picture'];
    }
}




// Fetch all contestants
$contestants = mysqli_query($conn, "SELECT * FROM contestants");

// Fetch pending payments
$payments = mysqli_query($conn, "SELECT payments.*, contestants.name FROM payments 
                                 JOIN contestants ON payments.contestant_id = contestants.id 
                                 WHERE payments.status = 'pending'");

if (isset($_POST['confirm_payment'])) {
    $payment_id = $_POST['payment_id'];
    $amount = $_POST['amount']; // Get the amount input from the form

    // Fetch the payment data from the database
    $payment_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM payments WHERE id = $payment_id"));

    if ($payment_data['status'] == 'pending') {
        // Update payment status to confirmed
        mysqli_query($conn, "UPDATE payments SET status = 'confirmed' WHERE id = $payment_id");

        // Calculate votes to be added (assuming 1 vote = 100 units of amount)
        $votes = $amount / 100;

        // Update contestant's vote count
        $contestant_id = $payment_data['contestant_id'];
        mysqli_query($conn, "UPDATE contestants SET votes = votes + $votes WHERE id = $contestant_id");

        // Prevent form re-submission by redirecting after the payment is confirmed
        echo "<script>alert('Payment confirmed and votes updated!'); window.location.href = 'admin.php';</script>";
    } else {
        echo "<script>alert('This payment has already been confirmed.'); window.location.href = 'admin.php';</script>";
    }
}



?>


<?php
// Handle updating contestant details
if (isset($_POST['update_contestant'])) {
    $contestant_id = $_POST['contestant_id'];
    $name = $_POST['contestant_name'];
    $about = $_POST['about_contestant'];
    $profile_picture = $_FILES['profile_picture']['name'];
    $profile_picture_tmp = $_FILES['profile_picture']['tmp_name'];

    // If a new profile picture is uploaded
    if (!empty($profile_picture)) {
        // Delete the old profile picture
        $old_picture_query = mysqli_query($conn, "SELECT profile_picture FROM contestants WHERE id = $contestant_id");
        $old_picture = mysqli_fetch_assoc($old_picture_query)['profile_picture'];
        if ($old_picture && file_exists("uploads/" . $old_picture)) {
            unlink("uploads/" . $old_picture); // Delete the old file
        }

        // Upload the new profile picture
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($profile_picture);
        if (move_uploaded_file($profile_picture_tmp, $target_file)) {
            echo "Profile picture uploaded successfully.";
        } else {
            echo "Error uploading profile picture.";
        }

        // Update contestant with the new profile picture
        $update_query = "UPDATE contestants SET name = '$name', about_contestant = '$about', profile_picture = '$profile_picture' WHERE id = $contestant_id";
    } else {
        // Update contestant without changing the profile picture
        $update_query = "UPDATE contestants SET name = '$name', about_contestant = '$about' WHERE id = $contestant_id";
    }

    // Execute the update query
    if (mysqli_query($conn, $update_query)) {
        echo "<script>alert('Contestant updated successfully!'); window.location.href = 'admin.php';</script>";
    } else {
        echo "<script>alert('Error updating contestant: " . mysqli_error($conn) . "');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        /* Add some styles to make the page responsive and modern */
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .container {
            width: 90%;
            margin: 0 auto;
            max-width: 1200px;
        }
        h1, h2 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        img {
            width: 50px;
            height: 50px;
            object-fit: cover;
        }
        input[type="text"], input[type="file"], input[type="number"], textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
        }
        button {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        .danger{
            padding: 10px 20px;
            background-color: red;
            color: white;
            border: none;
            cursor: pointer; 
        }

        @media (max-width: 768px) {
            .container {
                width: 100%;
            }
            th, td {
                font-size: 14px;
            }
            button {
                width: 100%;
                margin-top: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Panel</h1>

        <!-- Add Contestant Form -->
        <h2>Add New Contestant</h2>
        <form method="POST" enctype="multipart/form-data">
            <label>Contestant Name:</label>
            <input type="text" name="contestant_name" required>
            
            <label>About Contestant:</label>
            <textarea name="about_contestant" required></textarea>
            
            <label>Profile Picture:</label>
            <input type="file" name="profile_picture" accept="image/*" required>
            
            <button type="submit" name="add_contestant">Add Contestant</button>
        </form>
        
        <!-- Check if $contestant_data is available and display the edit form -->
<?php if (isset($contestant_data)) { ?>
    <h2>Edit Contestant</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="contestant_id" value="<?php echo $contestant_id; ?>">
        
        <label>Contestant Name:</label>
        <input type="text" name="contestant_name" value="<?php echo $contestant_name; ?>" required>
        
        <label>About Contestant:</label>
        <textarea name="about_contestant" required><?php echo $about_contestant; ?></textarea>
        
        <label>Profile Picture:</label>
        <img src="uploads/<?php echo $profile_picture; ?>" alt="Profile Picture" width="100">
        <input type="file" name="profile_picture" accept="image/*">
        
        <button type="submit" name="update_contestant">Update Contestant</button>
    </form>
<?php } ?>

      <!-- Contestants Table -->
<h2>Contestants</h2>
<table>
    <tr>
        <th>Profile Picture</th>
        <th>Name</th>
        <th>Votes</th>
        <th>About</th>
        <th>Actions</th> <!-- New column for action buttons -->
    </tr>
    <?php while ($row = mysqli_fetch_assoc($contestants)) { ?>
        <tr>
            <td><img src="uploads/<?php echo $row['profile_picture']; ?>" alt="<?php echo $row['name']; ?>"></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['votes']; ?></td>
            <td><?php echo $row['about_contestant']; ?></td>
            <td>
                  <!-- Edit button -->
                  <form method="POST" style="display:inline-block;">
                    <input type="hidden" name="contestant_id" value="<?php echo $row['id']; ?>">
                    <button type="submit" name="edit_contestant">Edit</button>
                </form>

                <!-- Delete button -->
                <form method="POST" onsubmit="return confirm('Are you sure you want to delete this contestant?');" style="display:inline-block;">
                    <input type="hidden" name="contestant_id" value="<?php echo $row['id']; ?>">
                    <button type="submit" name="delete_contestant" class="danger">Delete</button>
                </form>
              
            </td>
        </tr>
    <?php } ?>
</table>



        <h2>Pending Payments</h2>
<table>
    <tr>
        <th>Contestant</th>
        <th>Amount</th>
        <th>Confirm</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($payments)) { ?>
        <tr>
            <td><?php echo $row['name']; ?></td>
            <td>
                <!-- The amount input field is now inside the form -->
                <form method="POST">
                    <input type="number" name="amount" required placeholder="Enter amount">
            </td>
            <td>
                    <input type="hidden" name="payment_id" value="<?php echo $row['id']; ?>">
                    <button type="submit" name="confirm_payment">Confirm Payment</button>
                </form>
            </td>
        </tr>
    <?php } ?>
</table>


<h2>Pending Ticket Purchases</h2>
<table>
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Amount Paid</th>
        <th>Confirm</th>
    </tr>
    <?php 
    // Fetch pending ticket purchases
    $pending_tickets = mysqli_query($conn, "SELECT * FROM tickets WHERE status = 'pending'");
    while ($ticket = mysqli_fetch_assoc($pending_tickets)) { ?>
        <tr>
            <td><?php echo $ticket['name']; ?></td>
            <td><?php echo $ticket['email']; ?></td>
            <td><?php echo $ticket['phone']; ?></td>
            <td>
                <!-- Input for amount -->
                <form method="POST">
                    <input type="hidden" name="ticket_id" value="<?php echo $ticket['id']; ?>">
                    <input type="text" name="amount_paid" placeholder="Enter amount">
            </td>
            <td>
                    <button type="submit" name="confirm_ticket">Confirm Ticket</button>
                </form>
            </td>
        </tr>
    <?php } ?>
</table>


<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';

// Handle ticket confirmation
if (isset($_POST['confirm_ticket'])) {
    $ticket_id = $_POST['ticket_id'];
    $amount_paid = $_POST['amount_paid'];

    // Sanitize input and convert to a numerical value (remove 'k' if entered)
    $amount_paid = str_replace('k', '000', strtolower($amount_paid)); 
    $amount_paid = (int) $amount_paid;

    // Determine policy based on amount
    if ($amount_paid >= 100000) {
        $policy = 'Odogwu';
    } elseif ($amount_paid >= 50000) {
        $policy = 'Golden';
    } elseif ($amount_paid >= 20000) {
        $policy = 'Silver';
    } elseif ($amount_paid >= 2000) {
        $policy = 'Regular';
    } else {
        $policy = 'Unknown'; // Or handle invalid inputs
    }

    // Generate a unique ticket code
    $ticket_code = strtoupper(uniqid('TICKET'));

    // Update the ticket status, amount_paid, policy, and ticket code
    $update_query = "UPDATE tickets SET status = 'confirmed', ticket_code = '$ticket_code', amount_paid = $amount_paid, policy = '$policy' WHERE id = $ticket_id";

    if (mysqli_query($conn, $update_query)) {
        // Fetch the user's email and name
        $ticket_data_query = "SELECT * FROM tickets WHERE id = $ticket_id";
        $ticket_data = mysqli_fetch_assoc(mysqli_query($conn, $ticket_data_query));
        $email = $ticket_data['email'];
        $name = $ticket_data['name'];

        // Send the ticket code via email
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'mail.zamsignatures.com.ng'; 
            $mail->SMTPAuth = true;
            $mail->Username = 'contact@zamsignatures.com.ng'; 
            $mail->Password = '{*v1DAM@dF=1'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; 
            $mail->Port = 465; 

            // Recipients
            $mail->setFrom('contact@zamsignatures.com.ng', 'Zam Signatures');
            $mail->addAddress($email); 

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Your Ticket for the Event';
            $mail->Body = "
                <html>
                <head>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            color: #333;
                        }
                        h2 {
                            color: #0044cc;
                        }
                        .content {
                            background-color: #f9f9f9;
                            padding: 20px;
                            border-radius: 5px;
                        }
                        .footer {
                            margin-top: 20px;
                            font-size: 12px;
                            color: #777;
                        }
                    </style>
                </head>
                <body>
                    <div class='content'>
                        <img src='https://zamsignatures.com.ng/images/logo.jpg' alt='Logo'>
                        <h2>Hello $name,</h2>
                        <p>
                            Thank you for purchasing a ticket for the event. Your ticket ID is <strong>$ticket_code</strong>.<br>
                            You have been assigned to the <strong>$policy</strong> table.
                        </p>
                        <p>We look forward to seeing you at the event!</p>
                    </div>
                    <div class='footer'>
                        <p>This is an automated message, please do not reply to this email. If you have any questions, contact us at support@pageantry.com.</p>
                    </div>
                </body>
                </html>";

            $mail->send();
            echo "<script>alert('Ticket confirmed, and email sent!'); window.location.href = 'admin.php';</script>";
        } catch (Exception $e) {
            echo "<script>alert('Ticket confirmed, but email could not be sent. Error: {$mail->ErrorInfo}'); window.location.href = 'admin.php';</script>";
        }
    } else {
        echo "<script>alert('Error confirming ticket: " . mysqli_error($conn) . "'); window.location.href = 'admin.php';</script>";
    }
}?>


    </div>
</body>
</html>


