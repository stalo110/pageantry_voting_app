<?php
include 'db.php';

if (isset($_POST['payment_id']) && isset($_POST['amount'])) {
    $payment_id = $_POST['payment_id'];
    $amount = $_POST['amount'];

    // Update the payment with the amount and mark it as confirmed
    $update_query = "UPDATE payments SET amount = $amount WHERE id = $payment_id";
    mysqli_query($conn, $update_query);

    echo "Payment confirmed";
}
?>
