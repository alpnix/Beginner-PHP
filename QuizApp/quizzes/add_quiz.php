<?php 
session_start();

include("../config/connect_db.php");
$empty_error = FALSE;

if (isset($_POST["submit"])) {


    if (isset($_SESSION["userName"])) {
        $creator = $_SESSION["userName"];
    } else {$creator = "Guest";}

    $creator = ucfirst($creator);

    $title = mysqli_real_escape_string($conn,trim($_POST["title"]));
    $desc = mysqli_real_escape_string($conn,trim($_POST["desc"]));
    $firstA = $_POST["a1"];
    $firstB = $_POST["b1"];
    $firstC = $_POST["c1"];
    $firstD = $_POST["d1"];
    $secondA = $_POST["a2"];
    $secondB = $_POST["b2"];
    $secondC = $_POST["c2"];
    $secondD = $_POST["d2"];
    $prompt1 = $_POST["prompt1"];
    $prompt2 = $_POST["prompt2"];
    $correct1 = $_POST["correct1"];
    $correct2 = $_POST["correct2"];

    if (empty($title) || empty($desc) || empty($firstA) || empty($firstB) || empty($firstC) ||
    empty($firstD) || empty($secondA) || empty($secondB) || empty($secondC) || empty($secondD) ||
    empty($prompt1) || empty($prompt2)) {
        $empy_error = TRUE;
        header("Location: add_quiz.php");
    }

    $quizSQL = "INSERT INTO quizzes (title,short_desc,creator) VALUES ('$title','$desc','$creator')";
    $q1SQL = "INSERT INTO questions (prompt,A,B,C,D,Answer) VALUES ('$prompt1','$firstA','$firstB','$firstC','$firstD','$correct1')";
    $q2SQL = "INSERT INTO questions (prompt,A,B,C,D,Answer) VALUES ('$prompt2','$secondA','$secondB','$secondC','$secondD','$correct2')";
    $quizIDSQL = "SELECT id FROM quizzes WHERE title='$title' AND creator='$creator' AND short_desc='$desc'";
    $q1IDSQL = "SELECT id FROM questions WHERE prompt='$prompt1' AND A='$firstA'";
    $q2IDSQL = "SELECT id FROM questions WHERE prompt='$prompt2' AND A='$secondA'";

    if (!mysqli_query($conn,$quizSQL)) {
        echo $conn->error;
    } 
    
    if (!mysqli_query($conn,$q1SQL)) {
        echo $conn->error;
    }

    if (!$q1ID = mysqli_query($conn,$q1IDSQL)) {
        echo mysqli_error($conn);
    }
    $q1result = mysqli_fetch_assoc($q1ID);
    $q1id = $q1result["id"];

    if (!mysqli_query($conn,$q2SQL)) {
        echo $conn->error;
    }

    $q2ID = mysqli_query($conn,$q2IDSQL);
    $q2result = mysqli_fetch_assoc($q2ID);
    $q2id = $q2result["id"];

    // getting the quiz id
    $quizID = mysqli_query($conn,$quizIDSQL);
    $quizresult = mysqli_fetch_assoc($quizID);
    $quizid = $quizresult["id"];

    $quizSQL1 = "INSERT INTO quiz_questions (quiz_id,question_id) VALUES ('$quizid','$q1id')";
    $quizSQL2 = "INSERT INTO quiz_questions (quiz_id,question_id) VALUES ('$quizid','$q2id')";

    mysqli_query($conn,$quizSQL1);
    mysqli_query($conn,$quizSQL2);

    header("Location: index.php");
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Quiz</title>
    <style>
        .question {
            margin-top: 15px;
        }
    </style>
</head>
<body>
    
    <?php include("../templates/header.php"); ?>

    <div class="container p-4">

        <?php if($empty_error): ?>
            <div class="alert-danger container">
                Please fill out the form completely
            </div>
        <?php endif ?>
        <form action="add_quiz.php" method="post">
        
            <div class="form-area">
                <label for="">Enter the title of your quiz </label>
                <input type="text" name="title" placeholder="Title" maxlength="120" autofocus>
            </div>

            
            <div class="form-area">
                <label for="">Enter a short description of your quiz</label>
                <textarea class="p-2" style="resize:vertical;max-height:70px;min-height:40px;clear:both;" 
                placeholder="This quiz is about..." maxlength="255"
                name="desc" cols="50" rows="1" required></textarea>
            </div>

            <div class="question">
                <h2>1st Question</h2>

                <div class="form-area">
                    <label for="">Prompt:</label>
                    <input type="text" name="prompt1" style="width: 50%;">
                </div>

                <div class="form-area">
                    <label for="a">A:</label>
                    <input name="a1" type="text">
                </div>
                
                <div class="form-area">
                    <label for="b">B:</label>
                    <input name="b1" type="text">
                </div>
                
                <div class="form-area">
                    <label for="c">C:</label>
                    <input name="c1" type="text">
                </div>
                
                <div class="form-area">
                    <label for="d">D:</label>
                    <input name="d1" type="text">
                </div>
                <div class="form-area d-flex justify-space-around">
                    <p>Select the correct option: </p>
                    <select name="correct1">
                        <option value="a">A</option>
                        <option value="b">B</option>
                        <option value="c">C</option>
                        <option value="d">D</option>
                    </select>
                </div>
            </div>

            <div class="question">
                <h2>2nd Question</h2>
                <div class="form-area">
                    <label for="">Prompt:</label>
                    <input type="text" name="prompt2" style="width: 50%;">
                </div>
                <div class="form-area">
                    <label for="a">A:</label>
                    <input name="a2" type="text">
                </div>
                
                <div class="form-area">
                    <label for="b">B:</label>
                    <input name="b2" type="text">
                </div>
                
                <div class="form-area">
                    <label for="c">C:</label>
                    <input name="c2" type="text">
                </div>
                
                <div class="form-area">
                    <label for="d">D:</label>
                    <input name="d2" type="text">
                </div>
                <div class="form-area d-flex justify-space-around">
                    <p>Select the correct option</p>
                    <select name="correct2">
                        <option value="a">A</option>
                        <option value="b">B</option>
                        <option value="c">C</option>
                        <option value="d">D</option>
                    </select>
                </div>
            </div>

        
            <div class="d-flex flex-row-reverse">
                <button name="submit" class="btn btn-outline-success">Submit Quiz</button>
            </div>
        </form>   
    </div>

    <?php include("../templates/footer.php"); ?>

</body>
</html>