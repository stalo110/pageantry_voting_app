<?php
include 'db.php';

// Fetch all contestants and their vote counts
$contestants = mysqli_query($conn, "SELECT * FROM contestants");

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
            width: 100%;
            box-sizing: border-box;
        }
        .container {
            width: 80%;
            margin: 0 auto;
        }
        .container1{
            width: 100%;
            margin: 0 auto;  
            display: flex;
            justify-content: center;
            align-items: center
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
        .hero p {
            color: black
        }

        /* Buy Tickets Button */
        .buy-tickets {
            display: inline-block;
            margin: 20px 0;
            padding: 15px 30px;
            background-color: #007bff;
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
            background-color: #0056b3;
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

        
        .row{
            display: flex;
            margin-Left: 2.5%;
            width: 100%;
            background-color: black;
        }
        .column1{
            height: fit-content;
            width: 90%;
        }
        .column2{
            height: 500px;
            width: 90%;
            padding-left: 5%;
            height: fit-content;

        }
        .column2 p{
            color:white;
        }
        .column2 h2, .column2 h4{
          color: #007bff
        }
        .img{
            width: 90%;
            margin: 5%;
        }

        .ticket-date{
            width: 100%;
            padding: 10px;
            padding-top: 50px;
            display: flex
        }
        .footer{
            height: 200px;
            width: 100%;
            background: black;
            /* display: flex; */

        }
        /* Responsive Design */
        @media screen and (max-width: 768px) {
            .contestant {
                width: 45%;
            }
            .row{
                flex-direction: column;
            }
            .ticket-date{
                flex-direction: column;
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
            .row{
                flex-direction: column;
            }
            .ticket-date{
                flex-direction: column;
            }
        }

    </style>
</head>
<body>

    <!-- Hero Section -->
    <div class="hero">
        <img src="images/logo.jpg" alt="Pageantry Logo" width="200%" height="150px"> <!-- Replace with your logo -->
        <h1>Welcome to Our Pageantry Contest</h1>
        <p>Vote for your favorite contestants and support their journey to victory!</p>
    </div>

    <div class="row">

    
    <div class="column1">
    <div class="img">
    <img src="images/pics.jpg" alt="Pageantry Logo" width = 100%>
    </div>

</div>
        <div class="column2">
          <h2>ABOUT US</h2>
          <p>ZAM MODELS AND SIGNATURES was brought to imagination since, December 2020 but due to logistics reason it was kept pending till capable hands starting breathing life into it and imagination is turning to reality</p>
          <p>ZAM MODELS AND SIGNATURES is official a registered brand through the federal government on the 1st of April</p>
          <p>Thank to all Crew involved currently for push me to take this bold step. ...
          And as a fact as the CEO I hope we would work with October or November this year by God's grace to host it's first edition</p>

        <h4>OUR VISION</h4>
        <p>To develop at least 1000 youths with talents in the next 5 years and to prepare them for International and global challenges. </p>
        <h4>OUR MISSION</h4>
        <p>To be a transparent brand by developing the remotest of all talents and bringing out the best in them. 
        </p>

        </div>

    </div>


    <div class="ticket-date">
        <div class="address">
        <p style="color: #0056b3; font-size: 20px; font-wight: 800">Zam signatures Present </p>
        <p>Miss Multi-Cultural Nigeria and Multi-Cultural achievers award 2024 (maiden edition)</p>
        <p>Theme:- Nigerian culture in diversity</p>
        <p>
            <ul>
                <li> Date:- 23rd November 2024</li>
                <li>Ankara carpet:- 6pm</li>
                <li>Main event:-8pm</li>
                <li>Venue:- ubatel hotel and suites besides tempsite, unizik junction Awka ANAMBRA STATE.</li>
            </ul>
        </p>
        </div>
        <div class="policy">
        <p style="color: #0056b3; font-size: 20px; font-wight: 800">Tickets policy</p>
        <p>
            <ul>
                <li>100k</li>
                <li>50k</li>
                <li>20k</li>
                <li>1k</li>
            </ul>
        </p>
        </div>
    </div>

        <!-- Buy Tickets Button -->
        <div class="container1">
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
<!-- 
    <div class="footer">
        <div style="width: 10%; height: 20px;"><img src="images/logo.jpg" alt="" style="width: 100%"></div> 
        <div>
        <p style="color: white">Zam Signatures @2024</p>
        </div>
    

    </div> -->
</body>
</html>
