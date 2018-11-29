<?php
session_start();
include 'db.php';

$connection = mysqli_connect(DB_HOST, DB_USER,DB_PASS,DB_NAME);
if(!isset($_POST['update']) && !isset($_POST['update_info'])){
	header("Location: batters.php");
}
else{
	//Coming from batteres.php
	if($connection && isset($_POST['update'])){
		$player_id = $_POST['player_id'];
		$query = "SELECT player_id, first_name, last_name, team_name, age, salary, contract_length, OBP, home_runs, batting_avg FROM Batter natural join Team WHERE player_id={$player_id}";
	    $select_query = mysqli_query($connection,$query);
	    if ($select_query && mysqli_num_rows($select_query) > 0){
	    	$row = mysqli_fetch_assoc($select_query);
	    	$first_name = $row['first_name'];
            $last_name = $row['last_name'];
	    	$age = $row['age'];
	    	$salary = $row['salary'];
	        $contract_length = $row['contract_length'];
            $OBP = $row['OBP'];
            $home_runs = $row['home_runs'];
            $batting_avg = $row['batting_avg'];
            $team_name = $row['team_name'];
	    }
	    else{
	    	$_SESSION['failure'] = "Update failed. Most likely wrong player_id";
	    	header("Location: batters.php");
            exit();
	    }
	}
	else if($connection && isset($_POST['update_info'])) {
        $player_id = $_POST['player_id'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $batting_avg = $_POST['batting_avg'];
        $OBP = $_POST['OBP'];
        $salary = $_POST['salary'];
        $age = $_POST['age'];
        $home_runs = $_POST['home_runs'];
        $contract_length = $_POST['contract_length'];
        $team_name = $_POST['team_name'];
        $query = "SELECT team_id FROM Team WHERE team_name='{$team_name}'";
        $get_team_id_query = mysqli_query($connection, $query);
        if (!$get_team_id_query) {
            $error_msg =  mysqli_error($connection);
            $_SESSION['failure'] = "Failed to get team_id: $error_msg";
            header("Location: batters.php");
            exit();
        }
        $row = mysqli_fetch_assoc($get_team_id_query);
        $team_id = $row['team_id'];
        $query = "UPDATE Batter SET first_name = '{$first_name}', last_name = '{$last_name}', age = {$age}, salary = {$salary}, contract_length = {$contract_length}, batting_avg = {$batting_avg}, OBP = {$OBP}, home_runs={$home_runs}, team_id = {$team_id} WHERE player_id = {$player_id}";
        $update_query = mysqli_query($connection, $query);
        if (!$update_query) {
            $error_msg =  mysqli_error($connection);
            $_SESSION['failure'] = "Update Failed: $error_msg";
            header("Location: batters.php");
            exit();
        }
        else{
	        $_SESSION['success'] = 'Update successful!';
	        header('Location: batters.php');
            exit();
        }	
    }
}
?>
<?php include 'header.php';?>

	<form action="batters_update.php" method="POST" class="m-5 mx-auto p-2 border  rounded w-50">
		<div class="form-group">
            <label for="player_id">player_id: </label>
            <input type = "number" class="form-control"  name = "player_id" required value="<?php echo $player_id; ?>" readonly><br>
        </div>
		<div class="form-group">
            <label for="first_name">First Name: </label>
            <input type = "text" class="form-control"  name = "first_name" required value="<?php echo $first_name; ?>"><br>
        </div>
        <div class="form-group">
            <label for="last_name">Last Name: </label>
            <input type = "text" class="form-control"  name = "last_name" required value="<?php echo $last_name; ?>"><br>
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
		<div class="form-group">
            <label for="age">Age: </label>
            <input type = "number" class="form-control" min="18" name = "age" required value="<?php echo $age; ?>"><br>
        </div>
		<div class="form-group">
            <label for="salary">Salary: </label>
            <input type = "number" class="form-control"  min="0" name = "salary" required value="<?php echo $salary; ?>"><br>
        </div>
		<div class="form-group">
            <label for="contract_length">Contract Length: </label>
            <input type = "number" class="form-control"  min="0" name = "contract_length" required value="<?php echo $contract_length; ?>"><br>
        </div>
        <div class="form-group">
            <label for="age">OBP: </label>
            <input type = "number" step="0.001" class="form-control" min="0" max="1" name = "OBP" required value="<?php echo $OBP; ?>"><br>
        </div>
        <div class="form-group">
            <label for="salary">Batting Average: </label>
            <input type = "number" step="0.001" class="form-control"  min="0" max="1" name = "batting_avg" required value="<?php echo $batting_avg; ?>"><br>
        </div>
        <div class="form-group">
            <label for="home_runs">Home Runs: </label>
            <input type = "number" class="form-control"  min="0" name = "home_runs" required value="<?php echo $home_runs; ?>"><br>
        </div>
        
    	<button class="btn btn-success w-100" name="update_info">Update entry</button>
	</form>
	
</body>
</html>