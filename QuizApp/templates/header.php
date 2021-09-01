<?php

if (isset($_POST["signout"])) {
    session_destroy();
    $base = $_SERVER['DOCUMENT_ROOT'];
    header("Location: ../../../quizapp/quizapp/signup/signin.php");
}




?>
<html>
    <head>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <style>
            * {
                margin: 0;
                padding: 0;
            }
            body {
                width: 100vw;
                height: 100vh;
            }
            footer {
                background: #2a2a2a;
                color: #eaeaea;
            }
            footer div {
                width: 100vw;
            }
            footer .center {
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }
        </style>
    </head>
<body>
    <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
      <a class="navbar-brand col-sm-3 col-md-2 mr-2" href="../../../quizapp/quizapp/"><img class="" src="https://images.freeimg.net/rsynced_images/quiz-2058888_1280.png" alt="" width="30" height="30">
        QuizApp</a>
      <input class="form-control form-control-dark w-60" type="text" placeholder="Search" aria-label="Search">
      <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                <button name="signout" style="background:#343a40;color:white;border:none">
                <?php 
                    if (isset($_SESSION["userName"])) {echo "Sign out";}
                    else {echo "Sign in";}
                ?>
                </button>
            </form>
        </li>
      </ul>
    </nav>