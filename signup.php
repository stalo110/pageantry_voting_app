<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Insert admin into the admin table
    $query = "INSERT INTO admin (email, password) VALUES ('$email', '$password')";
    if (mysqli_query($conn, $query)) {
        header('Location: admin_login.php');
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Signup</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .signup-container {
            width: 300px;
            margin: 100px auto;
            padding: 20px;
            background: #fff;
            border: 1px solid #ccc;
            text-align: center;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
        }
        button {
            padding: 10px;
            width: 100%;
            background-color: #007bff;
            border: none;
            color: white;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <h1>Admin Signup</h1>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Sign Up</button>
        </form>
    </div>
</body>
</html>
