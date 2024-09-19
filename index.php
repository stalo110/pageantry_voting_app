<?php
include 'db.php';

// Fetch all contestants and their vote counts
$contestants = mysqli_query($conn, "SELECT c.*, COALESCE(SUM(p.amount/100), 0) as votes FROM contestants c LEFT JOIN payments p ON c.id = p.contestant_id GROUP BY c.id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pageantry Voting App</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            margin: 0 auto;
        }
        h1, h2, h3 {
            color: #333;
        }

        /* Hero Section */
        .hero {
            background-image: url('hero-image.jpg'); /* Replace with your hero image */
            background-size: cover;
            background-position: center;
            padding: 100px 0;
            text-align: center;
            color: white;
        }
        .hero img {
            width: 150px;
        }
        .hero h1 {
            font-size: 48px;
            margin-top: 20px;
        }

        /* Buy Tickets Button */
        .buy-tickets {
            display: inline-block;
            margin: 20px 0;
            padding: 15px 30px;
            background-color: #ff9800;
            color: white;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: none;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s ease;
        }
        .buy-tickets:hover {
            background-color: #e68900;
        }

        /* Contestants Section */
        .contestants {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin-top: 50px;
        }
        .contestant {
            width: 30%;
            margin-bottom: 40px;
            border: 1px solid #ddd;
            padding: 20px;
            text-align: center;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .contestant:hover {
            transform: translateY(-5px);
        }
        .contestant img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin-bottom: 20px;
        }
        .contestant h3 {
            font-size: 22px;
            margin-bottom: 10px;
        }
        .contestant p {
            font-size: 16px;
            margin-bottom: 10px;
        }
        .votes {
            font-size: 18px;
            font-weight: bold;
            color: #28a745;
        }
        .contestant a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .contestant a:hover {
            background-color: #0056b3;
        }

        /* Responsive Design */
        @media screen and (max-width: 768px) {
            .contestant {
                width: 45%;
            }
        }
        @media screen and (max-width: 480px) {
            .contestant {
                width: 100%;
            }
            .buy-tickets {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>

    <!-- Hero Section -->
    <div class="hero">
        <img src="logo.png" alt="Pageantry Logo"> <!-- Replace with your logo -->
        <h1>Welcome to Our Pageantry Contest</h1>
        <p>Vote for your favorite contestants and support their journey to victory!</p>
    </div>

    <!-- Buy Tickets Button -->
    <div class="container">
        <a href="buy_tickets.php" class="buy-tickets">Buy Tickets</a>
    </div>

    <!-- Contestants Section -->
    <div class="container">
        <h2>Contestants</h2>

        <div class="contestants">
            <?php while ($row = mysqli_fetch_assoc($contestants)) { ?>
                <div class="contestant">
                    <a href="vote.php?contestant_id=<?php echo $row['id']; ?>">
                        <img src="uploads/<?php echo $row['profile_picture']; ?>" alt="<?php echo $row['name']; ?>">
                    </a>
                    <h3><?php echo $row['name']; ?></h3>
                    <p><?php echo substr($row['about_contestant'], 0, 100) . '...'; ?></p>
                    <p class="votes">Total Votes: <?php echo $row['votes']; ?></p>
                    <a href="vote.php?contestant_id=<?php echo $row['id']; ?>">Vote Now</a>
                </div>
            <?php } ?>
        </div>
    </div>
</body>
</html>
