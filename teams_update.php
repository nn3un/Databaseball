<?php

session_start();
include 'db.php';

$connection = mysqli_connect(DB_HOST, DB_USER,DB_PASS,DB_NAME);
if(!isset($_POST['update']) && !isset($_POST['update_info'])){
	header("Location: teams.php");
}

else{
	if($connection && isset($_POST['update'])){
		$team_id = $_POST['team_id'];
		$query = "SELECT * FROM Team WHERE team_id=$team_id";
	    $select_query = mysqli_query($connection,$query);
	    if ($select_query && mysqli_num_rows($select_query) > 0){
	    	$row = mysqli_fetch_assoc($select_query);
	    	$team_name = $row['team_name'];
	        $location = $row['location'];
	        $years_existed = $row['years_existed'];
	    }
	    else{
	    	$_SESSION['failure'] = "Update failed. Most likely wrong team_id";
	    	header("Location: teams.php");
	    	exit();
	    }
	}

	else if($connection && isset($_POST['update_info'])) {
		//unset($_POST['update_info']);
        $team_id = $_POST['team_id'];
        $team_name = $_POST['team_name'];
        $location = $_POST['location'];
        $years_existed = $_POST['years_existed'];
        if (!empty($team_name) && !empty($location) && !empty($years_existed)) {
            $query = "UPDATE Team SET team_name = '{$team_name}', location = '{$location}', years_existed = {$years_existed} WHERE team_id = {$team_id}";
            echo $query;
            $update_query = mysqli_query($connection, $query);
            if (!$update_query) {
                $error_msg =  mysqli_error($connection);
	            $_SESSION['failure'] = "Update Failed: $error_msg";
	            header("Location: teams.php");
	            exit();
            }
            else{
		        $_SESSION['success'] = 'Update successful!';
		        header('Location: teams.php');
		        exit();
            }
        }
        else{
        	$_SESSION['failure'] = "Please enter all information";
        }
	}
}
?>
<?php include 'header.php';?>

	<form action="teams_update.php" method="POST" class="m-5 mx-auto p-2 border  rounded w-50">
		<div class="form-group">
	        <label for="team_id">team_id: </label>
	        <input type = "number" class="form-control" name ="team_id" readonly value="<?php echo $team_id; ?>" required><br>
    	</div>
		<div class="form-group">
	        <label for="location">Location: </label>
	        <input type = "text" class="form-control"  name = "location" value="<?php echo $location; ?>" required><br>
    	</div>
    	<div class="form-group">
	        <label for="team_name">Team Name: </label>
	        <input type = "text" class="form-control" name = "team_name" value="<?php echo $team_name; ?>" required><br>
    	</div>
    	<div class="form-group">
	        <label for="years">Years Existed: </label>
	        <input type = "number" class="form-control" min="0" name = "years_existed" value="<?php echo $years_existed; ?>" required><br>
    	</div>
    	<button class="btn btn-success w-100" name="update_info">Update entry</button>
	</form>
	
</body>
</html>