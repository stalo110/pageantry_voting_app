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
        }
        .contestant img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
        }
        .contestant p {
            margin-top: 10px;
        }
        .vote-section {
            margin-top: 30px;
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
        <h1>Vote for <?php echo $contestant['name']; ?></h1>

        <div class="contestant">
            <img src="uploads/<?php echo $contestant['profile_picture']; ?>" alt="<?php echo $contestant['name']; ?>">
            <p><?php echo $contestant['about_contestant']; ?></p>
        </div>

        <!-- Voting Section -->
        <div class="vote-section">
            <form method="POST" action="vote_payment.php">
                <input type="hidden" name="contestant_id" value="<?php echo $contestant['id']; ?>">
                <button type="submit" name="vote">Vote</button>
            </form>
        </div>
    </div>
</body>
</html>
