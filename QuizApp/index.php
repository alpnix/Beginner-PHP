<?php
// DASHBOARD   

include("config/connect_db.php");

$user_name = "Guest";
session_start();
if (isset($_SESSION["userName"])) {
    $user_name = $_SESSION["userName"];
    $user_name = ucfirst($user_name);
}
if (isset($_SESSION["email"])) {
  $user_email = $_SESSION["email"];
} else {$user_email = NULL;}

$scoresSQL = "SELECT * FROM user_scores WHERE user_email='$user_email' LIMIT 3";
if (!$scoresResult = mysqli_query($conn,$scoresSQL)) {
  echo $conn->error;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
    
    <?php include("templates/header.php"); ?>

    <div class="container-fluid">
      <div class="row">
        <nav class="col d-none d-md-block bg-dark sidebar">
          <div class="sidebar-sticky">
            <ul class="nav flex-column">
              <li class="nav-item">
                <a title="Dashboard" class="nav-link active" href="">
                  <span data-feather="home"></span>
                  Dashboard
                </a>
              </li>
              <li class="nav-item">
                <a title="Quizzes" class="nav-link" href="quizzes/">
                  <span data-feather="pie-chart"></span>
                  Quizzes
                </a>
              </li>
            </ul>
          </div>
        </nav>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            <h1 class="h2"><?php echo htmlspecialchars($user_name); ?>'s Dashboard</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
              <div class="btn-group mr-2">
                <button class="btn btn-sm btn-outline-secondary print">Share</button>
                <button class="btn btn-sm btn-outline-secondary print">Export</button>
              </div>
              <button class="btn btn-sm btn-outline-secondary dropdown-toggle">
                <span data-feather="calendar"></span>
                This week
              </button>
            </div>
          </div>
            <canvas class="my-4" id="myChart" width="900" height="380"></canvas>
 
          <h2>Recent Quizzes</h2>
          <div class="table-responsive">
            <table id="scoresTable" class="table table-striped table-sm">
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
                  <td class="quiz-names"><?php echo $quizName["title"] ?></td>
                  <td><?php echo $correctAnswers ?></td>
                  <td><?php echo $wrongAnswers ?></td>
                  <td class="percentages"><?php echo $user_percentage ?>%</td>
                </tr>
              </tbody>
              <?php 
                $counter++;
                endwhile;
                $scoresResult->free();
              ?>
            </table>
            <div class="d-flex justify-content-center">
                <button id="loadMore" class="btn btn-outline-primary mb-4">Load More</button>
            </div>
          </div>
        </main>
      </div>
    </div>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery-slim.min.js"><\/script>')</script>

    <!-- Icons -->
    <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
    <script>
      feather.replace()
    </script>

    <!-- Graphs -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
    <script>
      // var ctx = document.getElementById("myChart");
      // var myChart = new Chart(ctx, {
      //   type: 'line',
      //   data: {
      //     labels: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
      //     datasets: [{
      //       data: [15339, 21345, 18483, 24003, 23489, 24092, 12034],
      //       lineTension: 0,
      //       backgroundColor: 'transparent',
      //       borderColor: '#007bff',
      //       borderWidth: 4,
      //       pointBackgroundColor: '#ff7bff'
      //     }]
      //   },
      //   options: {
      //     scales: {
      //       yAxes: [{
      //         ticks: {
      //           beginAtZero: false
      //         }
      //       }]
      //     },
      //     legend: {
      //       display: true,
      //     }
      //   }
      // });
    
    </script>
    <script>
    
    var quizzes = document.querySelectorAll(".quiz-names");
      var scores = document.querySelectorAll(".percentages");
      var quizList = [];
      quizzes.forEach(function(item) {
        quizList.push(item.innerHTML);
      });
      var scoreList = [];
      scores.forEach(function(item) {
        let score = item.innerHTML.replace("%","");
        scoreList.push(Number(score));
      });

    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: quizList,
            datasets: [{
                label: 'Quiz Scores',
                data: scoreList,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
    </script>
  <script
    src="https://code.jquery.com/jquery-3.5.1.min.js"
    integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
    crossorigin="anonymous"></script>

    <script>
      var limit = 3;
      $("#loadMore").click(function() {
        limit = limit + 3;
        $("#scoresTable").load("config/load_scores.php",{
          limit: limit,
        })
      })
      $(".print").click(function(){
        print();
      });

      

    </script>

    <?php include("templates/footer.php"); ?>
    
</html>