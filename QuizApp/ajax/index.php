<?php

include("connect_db.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AJAX Practice</title>

    <script
    src="https://code.jquery.com/jquery-3.5.1.min.js"
    integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
    crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {
            var commentCount = 0;
            $("#btn").click(function() {
                commentCount = commentCount + 2;
                $("#ajax").load("load-comments.php", {
                    commentNewCount: commentCount
                });
            });
        });
        $(document).ready(function() {
            $("#btn2").click(function() {
                $.get("data.txt", function(data, status) {
                    $("#ajax2").html(data);
                })
            });
        });
    </script>
    <style> 
        .comment {
            color: white;
            background: #040404;
            display: grid;
            place-items: center;
            height: 100px;
            border-radius: 20px;
            padding: 3px;
            margin: 5px;
        }
    </style>
</head>
<body>

    <div id="ajax">
        <h3 style="text-align:center">Books will load as you press the button</h3>
    
        <?php
            $sql = "SELECT * FROM books LIMIT 0";
            $result = mysqli_query($conn,$sql);
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $author = $row['Author'];
                    $book = $row['Name'];
        ?>            
            <div class="comment">
                <?php 
                echo "<p>$author</p>";
                echo "<p>$book</p>";
                ?>
            </div>
        <?php        }
            } else {echo "There are no comments!";}
        ?>
    </div>

    <button id="btn">Show More Books</button>

    <h3 style="text-align:center">GET and POST methods with AJAX</h3>

    <div id="ajax2"></div>

    <button id="btn2">GET and POST</button> 
</body>
</html>