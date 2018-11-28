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
                $error_msg =  mysqli_error($connection);
                $_SESSION['failure'] = "Insertion Failed: $error_msg";
                header("Location: teams.php");
                exit();
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
        if (!$delete_query || mysqli_affected_rows($connection) <= 0) {
            $error_msg =  mysqli_error($connection);
            $_SESSION['failure'] = "Deletion Failed: $error_msg";
            header("Location: teams.php");
            exit();
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

<?php include 'header.php';?>
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
            	        <input type = "number" class="form-control" name = "years_existed" placeholder = "100" min="0" required><br>
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
        <div class="border rounded my-3 px-2 py-0">
            <form class="m-1 p-2" name="search" method="POST" action="teams.php">
                <p class="h2 pb-2">Search in Team</p>
                <hr>
                <div class="form-group">
                    <label for="team_name">By Team Name: </label>
                    <input type = "text" class="form-control"  name = "team_name" placeholder = "%Sox"><br>
                </div>
                <div class="form-group">
                    <label for="location">By Location: </label>
                    <input type = "text" class="form-control"  name = "location" placeholder = "Cincin%ati"><br>
                </div>
                <div class="form-group">
                    <label for="years_existed">By Years Existed: </label><br>Minimum: <input type = "number" class="form-control d-inline w-25 mr-5" name = "min_years_existed" min="0" placeholder = "0">Maximum: <input type = "number" class="form-control w-25 d-inline"  name = "max_years_existed" min="1" placeholder = "100" display="inline">
                </div>
                <hr class="pt-2">
                <div class=form-group>
                    <label for="sort[]" class='h2 pb-2'>Sort Results By: </label><br>
                    <input type="checkbox" class="ml-0 mr-2 d-inline" name="sort[]" value="team_name">Team Name
                    <input type="checkbox" class="ml-5 mr-2 d-inline" name="sort[]" value="years_existed">Years Existed<br>
                </div>
                <button class="btn btn-secondary w-100" name ="search">Search</button>
            </form>
        </div>
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
                    if (isset($_POST['search'])){
                        $params = [];
                        if (strlen($_POST['team_name']) > 0){
                            $params['team_name'] = " LIKE '{$_POST['team_name']}'";
                        }
                        if (strlen($_POST['location']) > 0){
                            $params['location'] = " LIKE '{$_POST['location']}'";
                        }
                        if (strlen($_POST['min_years_existed']) > 0){
                            $params['years_existed'] = " >= {$_POST['min_years_existed']}";
                        }

                        if (strlen($_POST['max_years_existed']) > 0){
                            //IMPORTANT Don't get rid of the space before 'years_existed'
                            $params[' years_existed'] = " <= {$_POST['max_years_existed']}";
                        }
                        if(count($params) > 0){
                            $query .= " WHERE ";
                            foreach($params as $key => $value){
                                $query .= " $key $value AND";
                            }
                            //Getting rid of last AND
                            $query = substr($query, 0, -3);
                        }
                        if (isset($_POST['sort'])){
                            $query .= " ORDER BY ";

                            if(in_array('years_existed', $_POST['sort'])){
                                $query .= " years_existed ASC,";
                            }
                            if(in_array('team_name', $_POST['sort'])){
                                $query .= " team_name ASC,";
                            }
                            //Getting rid of last Comma
                            $query = substr($query, 0, -1);
                        }
                    }
                    $query .= ";";
    			    $select_all_posts_query = mysqli_query($connection,$query);
                    if (!$select_all_posts_query){
                        $error_msg = mysqli_error($connection);
                        $_SESSION['failure'] = "Couldn't load query: $query $error_msg";
                        exit();
                    }
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
                else{
                    $_SESSION['failure'] = "Couldn't load query";
                    header("Location: index.php");
                    exit();
                }
    	?>
            </tbody>
        </table>
	</div>
</body>
</html>