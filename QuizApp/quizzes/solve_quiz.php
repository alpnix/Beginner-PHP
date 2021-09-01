<?php
include("../config/connect_db.php");
session_start();

if (isset($_GET["id"])) {
    $id = $_GET["id"];

    $sql = "SELECT * FROM quiz_questions WHERE quiz_id='$id'";

    if (!$result = mysqli_query($conn,$sql)) {
        echo $conn->error;
    }
} else {echo "No quiz selected🙁";}
$counter = 1;


if (isset($_POST["submit"])) {
    $score = 0;

    $firstAnswer = $_POST["answer1"];
    $secondAnswer = $_POST["answer2"];

    $questionsql = "SELECT * FROM questions WHERE id IN (SELECT question_id FROM quiz_questions WHERE quiz_id=$id)";
    if(!$answerResult = mysqli_query($conn,$questionsql)) {
        echo mysqli_error($conn);
    } else {
        $qcounter = 1;
        while ($answerAssoc = mysqli_fetch_assoc($answerResult)) {
            $user_answer = $_POST["answer".$qcounter];
            $actual_answer = $answerAssoc["Answer"];
            if ($user_answer == $actual_answer) {$score++;}
            $qcounter++;
        }
        $answerResult->free();
    }
    $checkSQL = "SELECT quiz_id FROM user_scores WHERE user_email='$user_email'";
    $checkResult = mysqli_query($conn,$checkSQL);
    $quizInDb = FALSE;
    while ($checkAssoc = mysqli_fetch_assoc($checkResult)) {
        if ($checkAssoc["quiz_id"] == $id) {$quizInDb = TRUE;} 
    }
    $checkResult->free();
    $user_email = $_SESSION["email"];
    $user_score = $score / 2;

    if ($quizInDb) {
        $mySQL = "UPDATE user_scores SET [percentage]=$user_score WHERE quiz_id=$id AND user_email='$user_email'";
    } else {
        $mySQL = "INSERT INTO user_scores (user_email,quiz_id,percentage) VALUES ('$user_email',$id,$user_score)";
    }
    if (!mysqli_query($conn,$mySQL)) {
        echo $conn->error;
    } else {header("Location: ../");}
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solve Quiz</title>
    <style type="text/css">
        body {
            height: 100vh;
        }
    </style>
</head>
<body>

    <?php include("../templates/header.php"); ?>
        
    <div class="container d-flex flex-direction-column m-auto">
        <form action="<?php echo $_SERVER["PHP_SELF"]."?id=".$id ?>" method="post">
        <div class="container m-4 p-4"> 
        <?php 
        while ($question = mysqli_fetch_assoc($result)) {

            $qid = $question["question_id"];
            $sql = "SELECT * FROM questions WHERE id='$qid'";
            if (!$qresult = mysqli_query($conn,$sql)) {
                echo $conn->error;
            }
            $qassoc = mysqli_fetch_assoc($qresult);

        ?>

            <div class="col-lg-6">

                <h3><?php echo $qassoc["prompt"] ?></h3>

                <div>
                    <select class="form-select btn-outline-primary" name="answer<?php echo $counter ?>">
                        <option name="<?php echo $counter ?>a" value="a"><?php echo $qassoc["A"]; ?></option>
                        <option name="<?php echo $counter ?>b" value="b"><?php echo $qassoc["B"]; ?></option>
                        <option name="<?php echo $counter ?>c" value="c"><?php echo $qassoc["C"]; ?></option>
                        <option name="<?php echo $counter ?>d" value="d"><?php echo $qassoc["D"]; ?></option>
                    </select>
                </div>
            </div>
            
        <?php 
        $counter++;
        };
        ?>
        </div>
            <div class="d-flex justify-content-center">
                <button class="btn btn-outline-success mb-4" name="submit" type="submit">Submit Answers</button>
            </div>
        </form>
    </div>

    <?php include("../templates/footer.php"); ?>

</body>
</html>