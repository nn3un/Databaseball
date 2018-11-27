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
    $stadium_name = $_POST['stadium_name'];
    $occupancy = $_POST['occupancy'];
    $indoor = $_POST['indoor'];
    $team_name = $_POST['team_name'];
    $query = "SELECT team_id FROM Team WHERE team_name='{$team_name}'";
    $get_team_id_query = mysqli_query($connection, $query);
    if (!$get_team_id_query) {
        die('team_id QUERY FAILED' . mysqli_error($connection));
        $_SESSION['failure'] = "Getting team_id Query Failed";
    }
    else{
        $row = mysqli_fetch_assoc($get_team_id_query);
        $team_id = $row['team_id'];
        $query = "INSERT INTO Stadium (stadium_name, occupancy, indoor, team_id) VALUES ('{$stadium_name}', '{$occupancy}', '{$indoor}', '{$team_id}');";
        $insert_query = mysqli_query($connection, $query);
        if (!$insert_query) {
            die('INSERT QUERY FAILED' . mysqli_error($connection));
            $_SESSION['failure'] = "Query Failed";
        }
        else{
            $_SESSION['success'] = "Insertion success!";
        }
    }
}
else if(isset($_POST['delete'])) {
    $stadium_id = $_POST['stadium_id'];
    if (!empty($stadium_id)) {
        $query = "DELETE FROM Stadium WHERE stadium_id=$stadium_id;";
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
            <form action="stadiums.php" method="POST" class="m-5 p-2 border rounded">
                <div class="form-group">
                    <label for="stadium_name">Stadium Name: </label>
                    <input type = "text" class="form-control"  name = "stadium_name" placeholder = "Rangers Park" required><br>
                </div>
                <div class="form-group">
                    <label for="occupancy">Occupancy: </label><br>
                    <input type = "number" class="form-control" name = "occupancy" placeholder = "100000" required><br>
                </div>

                <div class="form-group">
                    <label for="indoor">Indoor: </label><br>
                    <input class="mx-1" type="radio" name="indoor" value="1">Yes
                    <input class="mx-1" type="radio" name="indoor" value="0" checked>No
                </div>
                
                <div class="form-group">
                    <label for="team_name">Team Name: </label>
                    <select class = "w-100 mx-1 form-control" name="team_name">
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

                <button class="btn btn-success w-100" name="insert">Insert into table</button>
            </form>
        </div>
        <div class="col">
            <form method="POST" class="m-5 p-2 border rounded" action="stadiums_update.php">
                <div class="form-group">
                    <label for="stadium_id">Enter Stadium Id: </label>
                    <input type = "text" class="form-control" name = "stadium_id" required><br>
                </div>
                <button class="btn btn-primary m-0 w-100" name = "update">Update</button>
            </form>
            <form method="POST" class="m-5 mt-4 p-2 border rounded" action="stadiums.php">
                <div class="form-group">
                    <label for="team_id">Enter Stadium Id: </label>
                    <input type = "text" class="form-control" name = "stadium_id" required><br>
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
            <th>stadium_id</th> 
            <th>Stadium Name</th>
            <th>Team Name</th> 
            <th>Occupancy</th>
            <th>Indoor</th>
        </tr>
    </thead>
    <tbody>
     <?php
     if($connection){
        $query = "SELECT stadium_id, stadium_name, team_name, location, occupancy, indoor FROM Stadium natural join Team";
        $select_all_stadiums_query = mysqli_query($connection,$query);

        while($row = mysqli_fetch_assoc($select_all_stadiums_query)) {
            echo "
            <tr>
            <td>".$row['stadium_id']."</td>
            <td>".$row['stadium_name']."</td>
            <td>".$row['team_name']."</td>
            <td>".$row['occupancy']."</td>
            <td>";
            if($row['indoor']==1){
                echo "Yes";
            }
            else{
                echo "No";
            }
            echo "</td>
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