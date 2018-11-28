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
	header("Location: coaches.php");
}
else{
	//Coming from coaches.php
	if($connection && isset($_POST['update'])){
		$coach_id = $_POST['coach_id'];
		$query = "SELECT coach_id, coach_name, team_name, coach_age, coach_salary, coach_contract_length FROM Coach natural join Team WHERE coach_id={$coach_id}";
	    $select_query = mysqli_query($connection,$query);
	    if ($select_query && mysqli_num_rows($select_query) > 0){
	    	$row = mysqli_fetch_assoc($select_query);
	    	$coach_name = $row['coach_name'];
	    	$coach_age = $row['coach_age'];
	    	$coach_salary = $row['coach_salary'];
	        $coach_contract_length = $row['coach_contract_length'];
            $team_name = $row['team_name'];
	    }
	    else{
	    	$_SESSION['failure'] = "Update failed. Most likely wrong coach_id";
	    	header("Location: coaches.php");
            exit();
	    }
	}
	else if($connection && isset($_POST['update_info'])) {
        $coach_id = $_POST['coach_id'];
        $coach_name = $_POST['coach_name'];
        $coach_age = $_POST['coach_age'];
        $coach_salary = $_POST['coach_salary'];
        $coach_contract_length = $_POST['coach_contract_length'];
        $team_name = $_POST['team_name'];
        $query = "SELECT team_id FROM Team WHERE team_name='{$team_name}'";
        $get_team_id_query = mysqli_query($connection, $query);
        if (!$get_team_id_query) {
            $error_msg =  mysqli_error($connection);
            $_SESSION['failure'] = "Failed to get team_id: $error_msg";
            header("Location: coaches.php");
            exit();
        }
        $row = mysqli_fetch_assoc($get_team_id_query);
        $team_id = $row['team_id'];
        $query = "UPDATE Coach SET coach_name = '{$coach_name}', coach_age = {$coach_age}, coach_salary = {$coach_salary}, coach_contract_length = '{$coach_contract_length}', team_id = {$team_id} WHERE coach_id = {$coach_id}";
        $update_query = mysqli_query($connection, $query);
        if (!$update_query) {
            $error_msg =  mysqli_error($connection);
            $_SESSION['failure'] = "Update Failed: $error_msg";
            header("Location: coaches.php");
            exit();
        }
        else{
	        $_SESSION['success'] = 'Update successful!';
	        header('Location: coaches.php');
            exit();
        }	
    }
}
?>
<?php include 'header.php';?>

	<form action="coaches_update.php" method="POST" class="m-5 mx-auto p-2 border  rounded w-50">
		<div class="form-group">
            <label for="coach_id">coach_id: </label>
            <input type = "number" class="form-control"  name = "coach_id" required value="<?php echo $coach_id; ?>" readonly><br>
        </div>
		<div class="form-group">
            <label for="coach_name">Coach Name: </label>
            <input type = "text" class="form-control"  name = "coach_name" required value="<?php echo $coach_name; ?>"><br>
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
            <label for="coach_age">Coach Age: </label>
            <input type = "number" class="form-control" min="18" name = "coach_age" required value="<?php echo $coach_age; ?>"><br>
        </div>
		<div class="form-group">
            <label for="coach_salary">Coach Salary: </label>
            <input type = "number" class="form-control"  min="0" name = "coach_salary" required value="<?php echo $coach_salary; ?>"><br>
        </div>
		<div class="form-group">
            <label for="coach_contract_length">Coach Contract Length: </label>
            <input type = "number" class="form-control"  min="0" name = "coach_contract_length" required value="<?php echo $coach_contract_length; ?>"><br>
        </div>
        
        
    	<button class="btn btn-success w-100" name="update_info">Update entry</button>
	</form>
	
</body>
</html>