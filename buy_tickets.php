<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Save ticket purchase data into the database
    $query = "INSERT INTO tickets (name, email, phone, status) VALUES ('$name', '$email', '$phone', 'pending')";
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Ticket purchase request sent! Please wait for admin confirmation.'); window.location.href = 'index.php';</script>";
    } else {
        echo "<script>alert('Error processing your request: " . mysqli_error($conn) . "');</script>";
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

            <h3>Admin Bank Details</h3>
            <p>Bank: XYZ Bank</p>
            <p>Account Name: Admin Name</p>
            <p>Account Number: 1234567890</p>

            <button type="submit">Buy Ticket Now</button>
        </form>
    </div>
</body>
</html>
