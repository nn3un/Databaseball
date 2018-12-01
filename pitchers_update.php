<?php
session_start();
include 'db.php';

$connection = mysqli_connect(DB_HOST, DB_USER,DB_PASS,DB_NAME);
if(!isset($_POST['update']) && !isset($_POST['update_info'])){
	header("Location: pitchers.php");
}
else{
	//Coming from coaches.php
	if($connection && isset($_POST['update'])){
		$player_id = $_POST['player_id'];
		$query = "SELECT * FROM Pitcher WHERE player_id = $player_id";
	    $select_query = mysqli_query($connection,$query);
	    if ($select_query && mysqli_num_rows($select_query) > 0){
	    	$row = mysqli_fetch_assoc($select_query);
            $player_id = $row['player_id'];
            $wins = $row['wins'];
            $losses = $row['losses'];
            $ERA = $row['ERA'];
            $innings_pitched = $row['innings_pitched'];
	    }
	    else{
	    	$_SESSION['failure'] = "Update failed. Most likely wrong coach_id";
	    	header("Location: pitchers.php");
            exit();
	    }
	}
	else if($connection && isset($_POST['update_info'])) {
        $player_id = $_POST['player_id'];
        $wins = $_POST['wins'];
        $losses = $_POST['losses'];
        $ERA = $_POST['ERA'];
        $innings_pitched = $_POST['innings_pitched'];
        $query = "UPDATE Pitcher SET player_id = '{$player_id}', wins = {$wins}, losses = {$losses}, ERA = {$ERA}, innings_pitched = {$innings_pitched} WHERE player_id = {$player_id}";
        $update_query = mysqli_query($connection, $query);
        if (!$update_query) {
            $error_msg =  mysqli_error($connection);
            $_SESSION['failure'] = "Update Failed: $error_msg";
            header("Location: pitchers.php");
            exit();
        }
        else{
	        $_SESSION['success'] = 'Update successful!';
	        header('Location: pitchers.php');
            exit();
        }	
    }
}
?>
<?php include 'header.php';?>

	<form action="pitchers_update.php" method="POST" class="m-5 mx-auto p-2 border  rounded w-50">
		<div class="form-group">
            <label for="player_id">player_id: </label>
            <input type = "number"  class="form-control"  name = "player_id" required value="<?php echo $player_id; ?>" readonly><br>
        </div>
		<div class="form-group">
            <label for="coach_name">Wins: </label>
            <input type = "number" class="form-control" min="0" name = "wins" required value="<?php echo $wins; ?>"><br>
        </div>
		<div class="form-group">
            <label for="losses">Losses: </label>
            <input type = "number" class="form-control" min="0" name = "losses" required value="<?php echo $losses; ?>"><br>
        </div>
		<div class="form-group">
            <label for="ERA">ERA: </label>
            <input type = "number" step = "0.001" class="form-control"  min="0" name = "ERA" required value="<?php echo $ERA; ?>"><br>
        </div>
		<div class="form-group">
            <label for="innings_pitched">Innings Pitched: </label>
            <input type = "number" class="form-control"  min="0" name = "innings_pitched" required value="<?php echo $innings_pitched; ?>"><br>
        </div>
        
        
    	<button class="btn btn-success w-100" name="update_info">Update entry</button>
	</form>
	
</body>
</html>