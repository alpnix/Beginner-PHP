<?php
    include("connect_db.php");
    echo "<p style='text-align:center'>Book names will load as you press the button</p>";
    $commentNewCount = $_POST["commentNewCount"];

    $sql = "SELECT * FROM books LIMIT $commentNewCount";
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
