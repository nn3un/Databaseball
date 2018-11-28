<?php

session_start();
$db['db_host'] = "mysql.cs.virginia.edu";
$db['db_user'] = "nn3un";
$db['db_pass'] = "gnmSZIcO";
$db['db_name'] = "nn3un_Databaseball";

foreach($db as $key => $value){
	define(strtoupper($key), $value);
}

$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS,DB_NAME);
if(isset($_POST['insert'])) {

    $home_team_name = $_POST['home_team_name'];
    $away_team_name = $_POST['away_team_name'];
    $home_team_runs = $_POST['home_team_runs'];
    $away_team_runs = $_POST['away_team_runs'];
    
    if ($home_team_name == $away_team_name){
        $error_msg = mysqli_error($connection);
        $_SESSION['failure'] = "Home team and Away team must be different";
        header('Location: games.php');
        exit();
    }
    $query = "SELECT team_id FROM Team WHERE team_name='{$home_team_name}'";
    $get_team_id_query = mysqli_query($connection, $query);
    if (!$get_team_id_query) {
        $error_msg = mysqli_error($connection);
        $_SESSION['failure'] = "Getting home_team_id Query Failed $error_msg";
        header('Location: games.php');
        exit();
    }
    $row = mysqli_fetch_assoc($get_team_id_query);
    $home_team_id = $row['team_id'];

    $query = "SELECT team_id FROM Team WHERE team_name='{$away_team_name}'";
    $get_team_id_query = mysqli_query($connection, $query);
    if (!$get_team_id_query) {
        $error_msg = mysqli_error($connection);
        $_SESSION['failure'] = "Getting away_team_id Query Failed $error_msg";
        header('Location: games.php');
        exit();
    }
    $row = mysqli_fetch_assoc($get_team_id_query);
    $away_team_id = $row['team_id'];

    $query = "INSERT INTO Game (home_team_id, away_team_id, home_team_runs, away_team_runs) VALUES ({$home_team_id}, {$away_team_id}, {$home_team_runs}, {$away_team_runs});";
    $insert_query = mysqli_query($connection, $query);
    if (!$insert_query) {
        $error_msg = mysqli_error($connection);
        $_SESSION['failure'] = "Insertion failed: $error_msg";
        header('Location: games.php');
        exit();
    }
    else{
        $_SESSION['success'] = "Insertion successful!";
    }
}

else if(isset($_POST['delete'])) {
    $game_id = $_POST['game_id'];
    if (!empty($game_id)) {
        $query = "DELETE FROM Game WHERE game_id=$game_id;";
        $delete_query = mysqli_query($connection, $query);
        if (!$delete_query || mysqli_affected_rows($connection) <= 0) {
            $error_msg = mysqli_error($connection);
            $_SESSION['failure'] = "Deletion failed, maybe wrong game id?";
            header('Location: games.php');
            exit();
        }
        else{
            $_SESSION['success'] = "Succcessfully deleted";
        }
    }
    else{
    	$_SESSION['failure'] = "Please enter all information";
    }
    unset($_POST['delete']);
}
?>
<!DOCTYPE HTML>
<html>

<title>
</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<!-- Jquery -->
<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script><script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-info">
        <div class="container-fluid">
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item"><a class="nav-link text-white" href="index.php">Databaseball</a></li>
                </ul>
                <a class="nav-link text-white" href="teams.php">Teams</a>
                <a class="nav-link text-white" href="stadiums.php">Stadiums</a>
                <a class="nav-link text-white" href="games.php">Games</a>
            </div>
        </div>
    </nav>


    <div class="container">
        <?php 
        if(isset($_SESSION['success'])) {
         echo "<div class='alert alert-success text-center mx-5 my-2 px-5'>";
         echo $_SESSION['success']; 
         echo "</div>";
         unset($_SESSION['success']);
     } 
     if(isset($_SESSION['failure'])) {
         echo "<div class='alert alert-danger text-center mx-5 my-2 px-5'>";
         echo $_SESSION['failure']; 
         echo "</div>";
         unset($_SESSION['failure']);
     } 
     ?>
     <div class="row">
        <div class="col">
            <form action="games.php" method="POST" class="m-5 p-2 border rounded">
                <div class="form-group">
                    <label for="home_team_name">Home Team: </label>
                    <select class = "w-100 mx-1 form-control" name="home_team_name">
                        <?php
                        if($connection){
                            $query = "SELECT team_name FROM Team";
                            $select_team_names_query = mysqli_query($connection,$query);
                            while($row = mysqli_fetch_assoc($select_team_names_query)) {
                                echo ("<option>".$row['team_name']."</option>");
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="away_team_name">Away Team: </label>
                    <select class = "w-100 mx-1 form-control" name="away_team_name">
                        <?php
                        if($connection){
                            $query = "SELECT team_name FROM Team";
                            $select_team_names_query = mysqli_query($connection,$query);
                            while($row = mysqli_fetch_assoc($select_team_names_query)) {
                                echo ("<option>".$row['team_name']."</option>");
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="home_team_runs">Home Team Runs: </label><br>
                    <input type = "number" min="0" class="form-control" name = "home_team_runs" placeholder = "5" required><br>
                </div>

                <div class="form-group">
                    <label for="away_team_runs">Away Team Runs: </label><br>
                    <input type = "number" min="0" class="form-control" name = "away_team_runs" placeholder = "5" required><br>
                </div>

                <button class="btn btn-success w-100" name="insert">Insert into table</button>
            </form>
        </div>
        <div class="col">
            <form method="POST" class="m-5 p-2 border rounded" action="games_update.php">
                <div class="form-group">
                    <label for="game_id">Enter Game Id: </label>
                    <input type = "number" class="form-control" name = "game_id" required><br>
                </div>
                <button class="btn btn-primary m-0 w-100" name = "update">Update</button>
            </form>
            <form method="POST" class="m-5 mt-4 p-2 border rounded" action="games.php">
                <div class="form-group">
                    <label for="game_id">Enter Game Id: </label>
                    <input type = "number" class="form-control" name = "game_id" required><br>
                </div>
                <button class="btn btn-danger m-0 w-100" name ="delete">Delete</button>
            </form>
        </div>
    </div>
</div>

<div class="container">
    <table class=" table-bordered table table-striped">
        <thead>
            <tr>
                <th>game_id</th> 
                <th>Home Team</th>
                <th>Away Team</th> 
                <th>Home Team Runs</th>
                <th>Away Team Runs</th>
            </tr>
        </thead>
        <tbody>
           <?php
           if($connection){
            $query = "SELECT game_id, home_team_name,  away_team_name, home_team_runs, away_team_runs FROM Game natural join (select game_id, team_name as home_team_name from Game left outer join Team on home_team_id = team_id) as home_team_info natural join
            (select game_id, team_name as away_team_name from Game left outer join Team on away_team_id = team_id) as away_team_info;";
            $select_all_games_query = mysqli_query($connection,$query);
            if(!$select_all_games_query){
                header('Location: index.php');
                exit();
            }
            while($row = mysqli_fetch_assoc($select_all_games_query)) {
                echo "
                <tr>
                <td>".$row['game_id']."</td>
                <td>".$row['home_team_name']."</td>
                <td>".$row['away_team_name']."</td>
                <td>".$row['home_team_runs']."</td>
                <td>".$row['away_team_runs']."</td>
                </tr>
                ";
            }
        }
        ?>
    </tbody>
</table>
</div>
</body>
</html>