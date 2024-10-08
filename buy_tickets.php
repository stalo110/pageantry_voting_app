<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);

    // Save ticket purchase data into the database with pending status
    $query = "INSERT INTO tickets (name, email, phone, status) VALUES ('$name', '$email', '$phone', 'pending')";
    
    if (mysqli_query($conn, $query)) {
        // No email is sent here. The user will be notified when admin confirms the payment.
        echo "<script>alert('Ticket purchase request sent! Please wait for admin confirmation.'); window.location.href = 'index.php';</script>";
    } else {
        echo "<script>alert('Error inserting into database: " . mysqli_error($conn) . "');</script>";
    }
}
?>




<!DOCTYPE html>
<html>
<head>
    <title>Buy Tickets</title>
    <style>
        /* Add responsive and clean styles */
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            width: 80%;
            margin: 0 auto;
        }
        input, textarea, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
        }
        button {
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Buy Tickets</h1> 
        <form method="POST" action="buy_tickets.php">
            <label>Name:</label>
            <input type="text" name="name" required>
            
            <label>Email:</label>
            <input type="email" name="email" required>
            
            <label>Phone Number:</label>
            <input type="text" name="phone" required>

            <h4>Note: 2k means Regular ticket, 20k means Silver ticket, 50k means Golden ticket and 100k+ means Odogwu ticket.</h4>
            <h4>Make payments to the account details below and click on the Buy Tickts Now button, Once Admin confirms yout payment, an email will be sent to you.</h4>

            <h3>Admin Bank Details</h3>
            <p><strong>Bank:</strong> Moniepoint Bank</p>
            <p><strong>Account Name:</strong> Uhiara Stephen CHINAZA</p>
            <p><strong>Account Number:</strong> 8138635693</p>

            <button type="submit">Buy Ticket Now</button>
        </form>
    </div>
</body>
</html>
