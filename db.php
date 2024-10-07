<?php
// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "voting";

$servername = "localhost";
$username = "zamsigna_user";
$password = "&AqPu64D,F]W";
$dbname = "zamsigna_database";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// if($conn){
//     echo"connected";
// }

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
