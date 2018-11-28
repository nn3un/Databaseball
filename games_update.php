<?php

session_start();
$db['db_host'] = "mysql.cs.virginia.edu";
$db['db_user'] = "nn3un";
$db['db_pass'] = "gnmSZIcO";
$db['db_name'] = "nn3un_Databaseball";

foreach($db as $key => $value){
	define(strtoupper($key), $value);
}

$connection = mysqli_connect(DB_HOST, DB_USER,DB_PASS,DB_NAME);
if(!isset($_POST['update']) && !isset($_POST['update_info'])){
	header("Location: games.php");
}

else{
	//Coming from games.php
	if($connection && isset($_POST['update'])){
		$game_id = $_POST['game_id'];

		$query = "SELECT game_id, home_team_name,  away_team_name, home_team_runs, away_team_runs FROM Game natural join (select game_id, team_name as home_team_name from Game left outer join Team on home_team_id = team_id) as home_team_info natural join
        (select game_id, team_name as away_team_name from Game left outer join Team on away_team_id = team_id) as away_team_info WHERE game_id = {$game_id};";
        $select_query = mysqli_query($connection,$query);
        if ($select_query && mysqli_num_rows($select_query) > 0){
          $row = mysqli_fetch_assoc($select_query);
          $home_team_name = $row['home_team_name'];
          $away_team_name = $row['away_team_name'];
          $home_team_runs = $row['home_team_runs'];
          $away_team_runs = $row['away_team_runs'];
      }
      else{
          $_SESSION['failure'] = "Update failed. Most likely wrong game_id";
          header("Location: games.php");
          exit();
      }
  }

  else if($connection && isset($_POST['update_info'])) {
    $game_id = $_POST['game_id'];
    $home_team_name = $_POST['home_team_name'];
    $away_team_name = $_POST['away_team_name'];
    $home_team_runs = $_POST['home_team_runs'];
    $away_team_runs = $_POST['away_team_runs'];

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

    $query = "UPDATE Game SET home_team_id = {$home_team_id}, away_team_id = {$away_team_id}, home_team_runs = {$home_team_runs}, away_team_runs = {$away_team_runs} WHERE game_id = {$game_id}";
    $update_query = mysqli_query($connection, $query);
    if (!$update_query) {
        $error_msg = mysqli_error($connection);
        $_SESSION['failure'] = "Query Failed: $error_msg";
        header('Location: games.php');
        exit();
    }
    else{
       $_SESSION['success'] = 'Update successful!';
       header('Location: games.php');
   }	
}
}
?>
<?php include 'header.php';?>

    <form action="games_update.php" method="POST" class="m-5 mx-auto p-2 border  rounded w-50">
        <div class="form-group">
            <label for="game_id">game_id: </label>
            <input type = "number" class="form-control"  name = "game_id" required value="<?php echo $game_id; ?>" readonly><br>
        </div>
        <div class="form-group">
            <label for="home_team_name">Home Team: </label>
            <select class = "w-100 mx-1 form-control" name="home_team_name">
                <?php
                if($connection){
                    $query = "SELECT team_name FROM Team";
                    $select_team_names_query = mysqli_query($connection,$query);
                    while($row = mysqli_fetch_assoc($select_team_names_query)) {
                        if ($row['team_name'] == $home_team_name){
                            echo ("<option selected>".$row['team_name']."</option>");
                        }
                        else{
                            echo ("<option>".$row['team_name']."</option>");
                        }
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
                        if ($row['team_name'] == $away_team_name){
                            echo ("<option selected>".$row['team_name']."</option>");
                        }
                        else{
                            echo ("<option>".$row['team_name']."</option>");
                        }
                    }
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="home_team_runs">Home Team Runs: </label><br>
            <input type = "number" min="0" class="form-control" name = "home_team_runs" value="<?php echo $home_team_runs; ?>" required><br>
        </div>

        <div class="form-group">
            <label for="away_team_runs">Away Team Runs: </label><br>
            <input type = "number" min="0" class="form-control" name = "away_team_runs" value="<?php echo $home_team_runs; ?>" required><br>
        </div>
        <button class="btn btn-success w-100" name="update_info">Update entry</button>
        </form>
    </body>
</html>