<?php 
include("../config/connect_db.php");
$limit = $_POST["limit"];
$sql = "SELECT * FROM quizzes LIMIT $limit";
$result = mysqli_query($conn,$sql);

?>
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