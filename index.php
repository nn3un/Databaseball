<?php
session_start();
if (!isset($_SESSION['admin'])){
    header('Location: login.php');
    exit();
}
?>
<html>
    <head>
        <title>
        </title>
        <!-- Google fonts -->
        <link href="https://fonts.googleapis.com/css?family=Cairo:400,700" rel="stylesheet">
        <!--Bootstrap -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <!-- jQuery library -->
        <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
        <link href="https://fonts.googleapis.com/css?family=Rajdhani:700" rel="stylesheet">
        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <!-- CSS stylesheet -->
        <link rel="stylesheet" href="/stylesheets/style.css">
        <style>
            .landing-btn{
            }

            #welcome{
                background-image: url(https://digital.bu.edu/wp-content/uploads/2017/05/Sabermetrics-1024x576.jpg);
                height: 100vh;
                text-align: center;
                position: relative;
                background-repeat: no-repeat;
                background-size: cover;
            }

            #welcome-text{
                position: absolute;
                bottom: 40vh;
                right: 0px;
                left: 0px;
                font-size: 80px;
            }
        </style>
    </head>
    <body>
        <!-- The welcoming text -->
        <div id="welcome">
            <div id="welcome-text">
                <p style="font-family: 'Rajdhani', sans-serif; color: white" class="mb-0">Databaseball</p>
                <!-- Login and register links -->
                <a href = "teams.php" class="btn btn-primary landing-btn">Team</a>
                <a href = "stadiums.php" class="btn btn-primary landing-btn">Stadium</a>
                <a href = "coaches.php" class="btn btn-primary landing-btn">Coach</a>
                <a href = "games.php" class="btn btn-primary landing-btn">Game</a>
                <a href = "atbats.php" class="btn btn-primary landing-btn">At Bat</a>
                <a href = "pitchers.php" class="btn btn-primary landing-btn">Pitcher</a>
                <a href = "batters.php" class="btn btn-primary landing-btn">Batter</a><br>
                <a href = "views.php" class="btn btn-info landing-btn">Saved Queries</a>
            </div>
        </div>
    </body>
</html>