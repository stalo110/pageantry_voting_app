<?php
include 'db.php';

// Check if contestant_id is passed in the URL
if (isset($_GET['contestant_id'])) {
    $contestant_id = $_GET['contestant_id'];

    // Fetch contestant details
    $query = "SELECT * FROM contestants WHERE id = $contestant_id";
    $result = mysqli_query($conn, $query);
    $contestant = mysqli_fetch_assoc($result);
}


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
    <title>Vote for <?php echo $contestant['name']; ?></title>
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
            height: auto;
            width: 100%;
        }
        .contestant img {
            width: 300px;
            border-radius: 20%;
            height: auto;
            object-fit: cover;
        }
        .contestant p {
            margin-top: 10px;
        }
        .vote-section {
            margin-top: 30px;
        }
        .bank-details {
            margin-top: 30px;
            font-size: 18px;
        }
         h4{
            color: green;
            font-weight:600;
        }
        button {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }

        @media screen and (max-width: 768px) {
            .container{
                width: 100%;
                height: auto;
            } 
            .contestant{
                width: 100%;
            }
            }
    </style>
</head>
<body>
    <div class="container">
        <h1>Vote for <?php echo $contestant['name']; ?></h1>

        <div class="contestant">
            <img src="uploads/<?php echo $contestant['profile_picture']; ?>" alt="<?php echo $contestant['name']; ?>">
            <p><?php echo $contestant['about_contestant']; ?></p>
        </div>

        <!-- Voting Section -->
        <div class="vote-section">
            <form method="POST" action="vote_payment.php">
                <input type="hidden" name="contestant_id" value="<?php echo $contestant['id']; ?>">
                <!-- <button type="submit" name="vote">Vote</button> -->
            </form>
        </div>
    </div>

    <div class="container">
        <h1>Payment for <?php echo $contestant['name']; ?></h1>
        
        <!-- Bank Details -->
        <div class="bank-details">

            <h2>To vote via bank transfer</h2>
            <h4>Step 1:</h4>
            <p>Transfer the amount (N100/Vote) to</p>
            <p><strong>Bank:</strong> Moniepoint Bank</p>
            <p><strong>Account Name:</strong> Uhiara Stephen CHINAZA</p>
            <p><strong>Account Number:</strong> 8138635693</p>
        </div>
        <h4>Step 2:</h4>
        <p>Your votes wil be allocated automatically to the contestant immediately after confirming your payment.</p>

        <!-- Final Vote Button -->
        <form method="POST" action="process_vote.php">
            <input type="hidden" name="contestant_id" value="<?php echo $contestant['id']; ?>">
            <button type="submit" name="vote_now">Vote Now</button>
        </form>
    </div>

</body>
</html>
