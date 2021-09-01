<?php

include("connect_db.php");
$limit = $_POST["limit"];
session_start();
$user_email = $_SESSION["email"];
$scoresSQL = "SELECT * FROM user_scores WHERE user_email='$user_email' LIMIT $limit";
$scoresResult = mysqli_query($conn,$scoresSQL);

?>

<thead>
  <tr>
    <th>#</th>
    <th>Quiz Name</th>
    <th>Correct Answers</th>
    <th>False Answers</th>
    <th>Percentage</th>
  </tr>
</thead>
<?php 
$counter = 1;
while ($scoresAssoc = mysqli_fetch_assoc($scoresResult)):
$user_percentage = $scoresAssoc["percentage"] * 100;
$quiz_id = $scoresAssoc["quiz_id"];
$quizname = "SELECT title FROM quizzes WHERE id=$quiz_id";
$quizName = mysqli_fetch_assoc(mysqli_query($conn,$quizname));

$correctAnswers = $scoresAssoc["percentage"] * 2;
$wrongAnswers = 2 - $correctAnswers;
?>
<tbody>
  <tr>
    <td><?php echo $counter ?></td>
    <td><?php echo $quizName["title"] ?></td>
    <td><?php echo $correctAnswers ?></td>
    <td><?php echo $wrongAnswers ?></td>
    <td><?php echo $user_percentage ?>%</td>
  </tr>
</tbody>
<?php 
  $counter++;
  endwhile;
  $scoresResult->free();
?>