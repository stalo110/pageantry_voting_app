<?php
include 'db.php';

if (isset($_POST['vote'])) {
    $contestant_id = $_POST['contestant_id'];

    // Fetch contestant details
    $query = "SELECT * FROM contestants WHERE id = $contestant_id";
    $result = mysqli_query($conn, $query);
    $contestant = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment for <?php echo $contestant['name']; ?></title>
    <style>
        /* Add your styles */
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            width: 50%;
            margin: 0 auto;
            text-align: center;
        }
        .contestant {
            margin-top: 20px;
        }
        .bank-details {
            margin-top: 30px;
            font-size: 18px;
        }
        button {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Payment for <?php echo $contestant['name']; ?></h1>
        
        <!-- Bank Details -->
        <div class="bank-details">
            <h2>Bank Details</h2>
            <p><strong>Bank:</strong> Wema Bank</p>
            <p><strong>Account Name:</strong> Twidax VTU-GEN</p>
            <p><strong>Account Number:</strong> 8244846848</p>
        </div>

        <!-- Final Vote Button -->
        <form method="POST" action="process_vote.php">
            <input type="hidden" name="contestant_id" value="<?php echo $contestant['id']; ?>">
            <button type="submit" name="vote_now">Vote Now</button>
        </form>
    </div>
</body>
</html>
