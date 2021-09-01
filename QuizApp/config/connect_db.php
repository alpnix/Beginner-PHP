<?php 
// mysqli or PDO

// connect to database
$conn = mysqli_connect("localhost","root","","quizapp");

// check connection success
if (!$conn) {
    die ("There has been a problem connecting to the database: " . mysqli_error($conn));
}
