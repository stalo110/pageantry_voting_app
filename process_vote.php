<?php
include 'db.php';

if (isset($_POST['vote_now'])) {
    $contestant_id = $_POST['contestant_id'];

    // Create a pending payment record (admin confirms later)
    $query = "INSERT INTO payments (contestant_id, amount) VALUES ('$contestant_id', 0)"; // Amount is 0 initially until admin confirms
    mysqli_query($conn, $query);

    echo "<script>alert('Please make the payment and wait for admin to confirm your vote!');</script>";
    echo "<script>window.location.href = 'index.php';</script>";
}
?>
