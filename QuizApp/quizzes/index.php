<?php 

include("../config/connect_db.php");

$sql = "SELECT * FROM quizzes LIMIT 3";

$result = mysqli_query($conn,$sql);
session_start();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quizzes</title>
    <style type="text/css">
    * {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        padding: 0;
        margin: 0;
    }
    body {
        width: 50vw;
        background: blue;
    }
    #card-body {
        box-sizing: border-box;
        border-bottom: rgba(200,200,200,0.8) 2px solid;
        border-top: rgba(200,200,200,0.8) 2px solid;
    }
    </style>

    <script
        src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
        crossorigin="anonymous"></script>

    <script>
        // loading the questions with ajax
        $(document).ready(function() {
            var limit = 3;
            $("#loadMore").click(function() {
                limit = limit + 3;
                $("#quizzes").load("load_quizzes.php", {
                    limit: limit
                });
            });
        });        
    </script>


</head>
<body>  
    
    <?php include("../templates/header.php"); ?>

    <div class="d-flex flex-row-reverse m-2 p-4">
        <form action="add_quiz.php" method="post">
            <button class="btn btn-outline-success">Add New</button>
        </form>
    </div>
    <h2 class="text-center">Recent Quizzes</h2>

    <div style="color:white;" id="quizzes" class="container p-4">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>

            <div class="card bg-dark text-center mb-1 mt-4">
                <div class="card-header">
                    Created By: <?php echo $row["creator"]; ?>
                </div>
                <div id="card-body" class="card-body">
                    <h5 class="card-title"><?php echo $row["title"]; ?></h5>
                    <p class="card-text"><?php echo $row["short_desc"] ?></p>
                    <a href="solve_quiz.php?id=<?php echo $row["id"] ?>" class="btn btn-warning">Go To Quiz</a>
                </div>
                <div class="card-footer text-muted">
                    Published In: <?php echo $row["created_at"]; ?>
                </div>
            </div>
            <?php endwhile ?>   
        <?php else: ?>
            <div class="text-center">
                <p><em>No quiz available...</em>🙁</p>
            </div>
        <?php endif ?>
    </div>
    <br><br>
    <div class="d-flex justify-content-center m-4">
        <button id="loadMore" class="btn btn-primary">Load More</button>
    </div>

    <?php include("../templates/footer.php"); ?>


</body> 
</html> 