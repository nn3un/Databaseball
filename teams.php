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
if(isset($_POST['insert'])) {
        $team_name = $_POST['team_name'];
        $location = $_POST['location'];
        $years_existed = $_POST['years_existed'];
        if (!empty($team_name) && !empty($location) && !empty($years_existed)) {
            $query = "INSERT INTO Team (team_name, location, years_existed) VALUES ('{$team_name}', '{$location}', '{$years_existed}')";
            $insert_query = mysqli_query($connection, $query);
            if (!$insert_query) {
                die('QUERY FAILED' . mysqli_error($connection));
                $_SESSION['failure'] = "Query Failed";
            }
            else{
            	$_SESSION['success'] = "Insertion success!";
            }
        }
        else{
        	$_SESSION['failure'] = "Please enter all information";
        }
        unset($_POST['insert']);
}
else if(isset($_POST['delete'])) {
    $team_id = $_POST['team_id'];
    if (!empty($team_id)) {
        $query = "DELETE FROM Team WHERE team_id=$team_id;";
        $delete_query = mysqli_query($connection, $query);
        if (!$delete_query) {
            die('QUERY FAILED' . mysqli_error($connection));
            $_SESSION['failure'] = "Query Failed, maybe wrong id?";
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
            </div>
        </div>
    </nav>

    <!--Error and success messages -->
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

    <div class="container">
        <div class="row">
            <div class="col">
                <!-- Form to insert new team -->
            	<form action="teams.php" method="POST" class="m-5 p-2 border  rounded">
            		<div class="form-group">
            	        <label for="location">Location: </label>
            	        <input type = "text" class="form-control"  name = "location" placeholder = "Washington" required><br>
                	</div>
                	<div class="form-group">
            	        <label for="team_name">Team Name: </label>
            	        <input type = "text" class="form-control" name = "team_name" placeholder = "Nationals" required><br>
                	</div>
                	<div class="form-group">
            	        <label for="years">Years Existed: </label>
            	        <input type = "text" class="form-control" name = "years_existed" placeholder = "100" required><br>
                	</div>
                	<button class="btn btn-success w-100" name="insert">Insert into table</button>
            	</form>
            </div>
            <div class="col">
                <!-- Form to update team -->
            	<form method="POST" class="m-5 p-2 border  rounded" action="teams_update.php">
                    <div class="form-group">
                        <label for="team_id">Enter Team Id: </label>
                        <input type = "text" class="form-control" name = "team_id" placeholder = "100" required><br>
                    </div>
                    <button class="btn btn-primary m-0 w-100" name = "update">Update</button>
                </form>

                <!-- Form to delete team -->
            	<form method="POST" class="m-5 p-2 border rounded" action="teams.php">
            		<div class="form-group">
            	        <label for="team_id">Enter Team Id: </label>
            	        <input type = "text" class="form-control" name = "team_id" placeholder = "100" required><br>
                	</div>
                	<button class="btn btn-danger m-0 w-100" name ="delete">Delete</button>
            	</form>
            </div>
        </div>
    </div>

    <!-- Table to show team -->
    <div class='container'>
    	<table class=" table-bordered table table-striped">
            <thead>
                <tr>
                    <th>team_id</th> 
                    <th>Team Name</th> 
                    <th>Location</th>
                    <th>Years Existed</th>
                </tr>
            </thead>
            <tbody>
            	<?php
    			if($connection){
    				$query = "SELECT * FROM Team";
    			    $select_all_posts_query = mysqli_query($connection,$query);

    			    while($row = mysqli_fetch_assoc($select_all_posts_query)) {
    			    	echo "
    			    	<tr>
    			    		<td>".$row['team_id']."</td>
    			    		<td>".$row['team_name']."</td>
    			    		<td>".$row['location']."</td>
    			    		<td>".$row['years_existed']."</td>
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