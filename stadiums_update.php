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
	header("Location: stadiums.php");
}

else{
	//Coming from stadiums.php
	if($connection && isset($_POST['update'])){
		$stadium_id = $_POST['stadium_id'];
		$query = "SELECT stadium_id, stadium_name, team_name, occupancy, indoor FROM Stadium natural join Team WHERE stadium_id={$stadium_id}";
	    $select_query = mysqli_query($connection,$query);
	    if ($select_query){
	    	$row = mysqli_fetch_assoc($select_query);
	    	$stadium_name = $row['stadium_name'];
	    	$team_name = $row['team_name'];
	    	$occupancy = $row['occupancy'];
	        $indoor = $row['indoor'];
	    }
	    else{
	    	$_SESSION['failure'] = "Update failed. Most likely wrong stadium_id";
	    	header("Location: stadiums.php");
	    }
	}

	else if($connection && isset($_POST['update_info'])) {
        $stadium_id = $_POST['stadium_id'];
        $stadium_name = $_POST['stadium_name'];
        $team_name = $_POST['team_name'];
        $occupancy = $_POST['occupancy'];
        $indoor = $_POST['indoor'];

        $query = "SELECT team_id FROM Team WHERE team_name='{$team_name}'";
        $get_team_id_query = mysqli_query($connection, $query);
        if (!$get_team_id_query) {
            die('QUERY FAILED' . mysqli_error($connection));
            $_SESSION['failure'] = "Getting team_id Query Failed";
        }
        $row = mysqli_fetch_assoc($get_team_id_query);
        $team_id = $row['team_id'];
        $query = "UPDATE Stadium SET stadium_name = '{$stadium_name}', occupancy = {$occupancy}, indoor = '{$indoor}', team_id = {$team_id} WHERE stadium_id = {$stadium_id}";
        $update_query = mysqli_query($connection, $query);
        if (!$update_query) {
            die('QUERY FAILED' . mysqli_error($connection));
            $_SESSION['failure'] = "Query Failed";
            header('Location: stadiums.php');
        }
        else{
	        $_SESSION['success'] = 'Update successful!';
	        header('Location: stadiums.php');
        }	
    }
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
            </div>
        </div>
    </nav>
	<?php 
		if(isset($_SESSION['success'])) {
			echo "<div class='alert alert-success text-center m-5 px-5'>";
			echo $_SESSION['success']; 
			echo "</div>";
			unset($_SESSION['success']);
		} 
		if(isset($_SESSION['failure'])) {
			echo "<div class='alert alert-danger text-center m-5 px-5'>";
			echo $_SESSION['failure']; 
			echo "</div>";
			unset($_SESSION['failure']);
		} 
	?>

	<form action="stadiums_update.php" method="POST" class="m-5 mx-auto p-2 border  rounded w-50">
		<div class="form-group">
            <label for="stadium_id">stadium_id: </label>
            <input type = "number" class="form-control"  name = "stadium_id" required value="<?php echo $stadium_id; ?>" readonly><br>
        </div>
		<div class="form-group">
            <label for="stadium_name">Stadium Name: </label>
            <input type = "text" class="form-control"  name = "stadium_name" required value="<?php echo $stadium_name; ?>"><br>
        </div>
        <div class="form-group">
            <label for="occupancy">Occupancy: </label><br>
            <input type = "number" class="form-control" name = "occupancy" value="<?php echo $occupancy; ?>" required><br>
        </div>

        <div class="form-group">
            <label for="indoor">Indoor: </label><br>
            <?php
            if ($indoor==1){
            	echo '
        		<input class="mx-1" type="radio" name="indoor" value="1" checked>Yes
        		<input class="mx-1" type="radio" name="indoor" value="0">No
            	';
            }
            else{
            	echo '
        		<input class="mx-1" type="radio" name="indoor" value="1">Yes
        		<input class="mx-1" type="radio" name="indoor" value="0" checked>No
            	';
            }
            ?>
        </div>
        
        <div class="form-group">
            <label for="team_name">Team Name: </label>
            <select class = "w-100 mx-1 form-control" name="team_name">
                <?php
                if($connection){
                    $query = "SELECT team_name FROM Team";
                    $select_team_names_query = mysqli_query($connection,$query);
                    while($row = mysqli_fetch_assoc($select_team_names_query)) {
                    	if ($row['team_name'] == $team_name){
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
    	<button class="btn btn-success w-100" name="update_info">Update entry</button>
	</form>
	
</body>
</html>