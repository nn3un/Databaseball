<?php

session_start();
include 'db.php';


$connection = mysqli_connect(DB_HOST, DB_USER,DB_PASS,DB_NAME);
if(!isset($_POST['update']) && !isset($_POST['update_info'])){
	header("Location: atbats.php");
}

else{
	if($connection && isset($_POST['update'])){
		$at_bat_id = $_POST['at_bat_id'];
		$query = "SELECT * FROM At_Bat WHERE at_bat_id=$at_bat_id";
	    $select_query = mysqli_query($connection,$query);
	    if ($select_query && mysqli_num_rows($select_query) > 0){
	    	$row = mysqli_fetch_assoc($select_query);
	    	$batter_id = $row['batter_id'];
		    $pitcher_id = $row['pitcher_id'];
		    $game_id = $row['game_id'];
		    $strikes = $row['strikes'];
		    $balls = $row['balls'];
		    $runs_scored = $row['runs_scored'];
		    $out_or_not = $row['out_or_not']; 
	    }
	    else{
	    	$_SESSION['failure'] = "Update failed. Most likely wrong at_bat_id";
	    	header("Location: atbats.php");
	    	exit();
	    }
	}

	else if($connection && isset($_POST['update_info'])) {
		//unset($_POST['update_info']);
		$at_bat_id = $_POST['at_bat_id'];
        $batter_id = $_POST['batter_id'];
	    $pitcher_id = $_POST['pitcher_id'];
	    $game_id = $_POST['game_id'];
	    $strikes = $_POST['strikes'];
	    $balls = $_POST['balls'];
	    $runs_scored = $_POST['runs_scored'];
	    $out_or_not = $_POST['out_or_not']; 

    	$query = "UPDATE At_Bat SET batter_id = {$batter_id}, pitcher_id = {$pitcher_id}, game_id = {$game_id}, strikes = {$strikes}, balls = {$balls}, runs_scored = {$runs_scored}, out_or_not = {$out_or_not} WHERE at_bat_id = {$at_bat_id};";
        $update_query = mysqli_query($connection, $query);
        if (!$update_query) {
            $error_msg =  mysqli_error($connection);
            $_SESSION['failure'] = "Update Failed: $query $error_msg";
            header("Location: atbats.php");
            exit();
        }
        else{
	        $_SESSION['success'] = 'Update successful!';
	        header('Location: atbats.php');
	        exit();
        }
	}
}
?>
<?php include 'header.php';?>

	<form action="atbats_update.php" method="POST" class="m-5 mx-auto p-2 border  rounded w-50">
		<div class="form-group">
            <label for="at_bat_id">at_bat_id: </label>
            <input type = "number" class="form-control"  name = "at_bat_id" required value="<?php echo $at_bat_id?>" readonly><br>
        </div> 
		<div class="form-group">
            <label for="batter_id">Batter ID: </label>
            <input type = "number" class="form-control"  name = "batter_id" required value="<?php echo $batter_id?>"><br>
        </div>                
        <div class="form-group">
            <label for="pitcher_id">Pitcher ID: </label>
            <input type = "number" class="form-control" name = "pitcher_id" required value="<?php echo $pitcher_id?>"><br>
        </div>
        <div class="form-group">
            <label for="game_id">Game ID: </label>
            <input type = "number" class="form-control"  name = "game_id" required value="<?php echo $game_id?>"><br>
        </div>
        <div class="form-group">
            <label for="strikes">Strikes: </label>
            <input type = "number" class="form-control"  name = "strikes" min="0" max = "3" required value="<?php echo $strikes ?>"><br>
        </div>
        <div class="form-group">
            <label for="balls">Balls: </label>
            <input type = "number" class="form-control"  name = "balls" min="0" max = "4" required value="<?php echo $balls?>"><br>
        </div>
        <div class="form-group">
            <label for="runs_scored">Runs Scored: </label>
            <input type = "number" class="form-control"  name = "runs_scored" min="0" max = "4" required value="<?php echo $runs_scored ?>"><br>
        </div>
        <div class="form-group">
            <label for="out_or_not">Out or Not: </label>
            <input type = "number" class="form-control"  name = "out_or_not" min="0" required value="<?php echo $out_or_not ?>"><br>
        </div>

        <button class="btn btn-success w-100" name="update_info">Update</button>
	</form>
	
</body>
</html>