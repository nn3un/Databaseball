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
	header("Location: teams.php");
}

else{
	if($connection && isset($_POST['update'])){
		$team_id = $_POST['team_id'];
		$query = "SELECT * FROM Team WHERE team_id=$team_id";
	    $select_query = mysqli_query($connection,$query);
	    if ($select_query){
	    	$row = mysqli_fetch_assoc($select_query);
	    	$team_name = $row['team_name'];
	        $location = $row['location'];
	        $years_existed = $row['years_existed'];
	    }
	    else{
	    	$_SESSION['failure'] = "Update failed. Most likely wrong team_id";
	    	//header("Location: teams.php");
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
                die('QUERY FAILED' . mysqli_error($connection));
                $_SESSION['failure'] = "Query Failed";
                header('Location: teams.php');
            }
            else{
		        $_SESSION['success'] = 'Update successful!';
		        header('Location: teams.php');
            }
        }
        else{
        	$_SESSION['failure'] = "Please enter all information";
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
        
<body class="container">
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

	<form action="teams_update.php" method="POST" class="m-5 px-5">
		<div class="form-group">
	        <label for="team_id">team_id: </label>
	        <input type = "number" class="form-control" name ="team_id" value="<?php echo $team_id; ?>" required><br>
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
	        <input type = "text" class="form-control" name = "years_existed" value="<?php echo $years_existed; ?>" required><br>
    	</div>
    	<button class="btn btn-success w-100" name="update_info">Update entry</button>
	</form>
	
</body>
</html>