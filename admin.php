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
            echo "<script>alert('Contestant added successfully!');</script>";
        } else {
            echo "<script>alert('Error adding contestant: " . mysqli_error($conn) . "');</script>";
        }
    } else {
        echo "<script>alert('Please enter a contestant name!');</script>";
    }
}

// Fetch all contestants
$contestants = mysqli_query($conn, "SELECT * FROM contestants");

// Fetch pending payments
$payments = mysqli_query($conn, "SELECT payments.*, contestants.name FROM payments 
                                 JOIN contestants ON payments.contestant_id = contestants.id 
                                 WHERE payments.status = 'pending'");

// Handle payment confirmation
if (isset($_POST['confirm_payment'])) {
    $payment_id = $_POST['payment_id'];
    $amount = $_POST['amount'];

    // Ensure the payment hasn't already been confirmed
    $payment_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM payments WHERE id = $payment_id"));
    
    if ($payment_data['status'] == 'pending') {
        // Update payment status to confirmed
        mysqli_query($conn, "UPDATE payments SET status = 'confirmed' WHERE id = $payment_id");

        // Calculate votes to be added
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

        <!-- Contestants Table -->
        <h2>Contestants</h2>
        <table>
            <tr>
                <th>Profile Picture</th>
                <th>Name</th>
                <th>Votes</th>
                <th>About</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($contestants)) { ?>
                <tr>
                    <td><img src="uploads/<?php echo $row['profile_picture']; ?>" alt="<?php echo $row['name']; ?>"></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['votes']; ?></td>
                    <td><?php echo $row['about_contestant']; ?></td>
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
        <th>Confirm</th>
    </tr>
    <?php 
    $pending_tickets = mysqli_query($conn, "SELECT * FROM tickets WHERE status = 'pending'");
    while ($ticket = mysqli_fetch_assoc($pending_tickets)) { ?>
        <tr>
            <td><?php echo $ticket['name']; ?></td>
            <td><?php echo $ticket['email']; ?></td>
            <td><?php echo $ticket['phone']; ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="ticket_id" value="<?php echo $ticket['id']; ?>">
                    <button type="submit" name="confirm_ticket">Confirm Ticket</button>
                </form>
            </td>
        </tr>
    <?php } ?>
</table>

<?php
// Handle ticket confirmation
if (isset($_POST['confirm_ticket'])) {
    $ticket_id = $_POST['ticket_id'];

    // Generate a unique ticket ID
    $ticket_code = strtoupper(uniqid('TICKET'));

    // Update the ticket status and add the ticket code
    mysqli_query($conn, "UPDATE tickets SET status = 'confirmed', ticket_code = '$ticket_code' WHERE id = $ticket_id");

    // Get the user's email
    $ticket_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tickets WHERE id = $ticket_id"));
    $email = $ticket_data['email'];

    // Send the ticket ID to the user's email
    $subject = "Your Ticket for the Event";
    $message = "Thank you for purchasing a ticket! Your ticket ID is: $ticket_code";
    $headers = "From: noreply@pageantry.com";

    mail($email, $subject, $message, $headers);

    echo "<script>alert('Ticket confirmed, and email sent!'); window.location.href = 'admin.php';</script>";
}
?>


    </div>
</body>
</html>
