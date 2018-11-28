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
        $error_msg = mysqli_error($connection);
        $_SESSION['failure'] = "Getting team_id Query Failed $error_msg";
        header('Location: games.php');
    }
    else{
        $row = mysqli_fetch_assoc($get_team_id_query);
        $team_id = $row['team_id'];
        $query = "INSERT INTO Stadium (stadium_name, occupancy, indoor, team_id) VALUES ('{$stadium_name}', '{$occupancy}', '{$indoor}', '{$team_id}');";
        $insert_query = mysqli_query($connection, $query);
        if (!$insert_query) {
            $error_msg =  mysqli_error($connection);
            $_SESSION['failure'] = "Insertion Failed: $error_msg";
            header("Location: stadiums.php");
            exit();
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
        if (mysqli_affected_rows($connection) > 0) {
            $_SESSION['success'] = "Succcessfully deleted";
        }
        else{
            $error_msg = mysqli_error($connection);
            $_SESSION['failure'] = "stadium_id $stadium_id does not exist";
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
            <form action="stadiums.php" method="POST" class="m-5 p-2 border rounded">
                <div class="form-group">
                    <label for="stadium_name">Stadium Name: </label>
                    <input type = "text" class="form-control"  name = "stadium_name" placeholder = "Rangers Park" required><br>
                </div>
                <div class="form-group">
                    <label for="occupancy">Occupancy: </label><br>
                    <input type = "number" min="0" class="form-control" name = "occupancy" placeholder = "100000" required><br>
                </div>

                <div class="form-group">
                    <label for="indoor">Indoor: </label><br>
                    <input class="mr-2" type="radio" name="indoor" value="1">Yes
                    <input class="ml-5 mr-2" type="radio" name="indoor" value="0" checked>No<br>
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
                    </select><br>
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
                    <label for="stadium_id">Enter Stadium Id: </label>
                    <input type = "text" class="form-control" name = "stadium_id" required><br>
                </div>
                <button class="btn btn-danger m-0 w-100" name ="delete">Delete</button>
            </form>
        </div>
    </div>
</div>

<div class="container">
    <div class="border rounded my-3 px-2 py-0">
        <form class="m-1 p-2" name="search" method="POST" action="stadiums.php">
            <p class="h2 pb-2">Search in Stadiums</p>
            <hr>
            <div class="form-group">
                <label for="stadium_name">By Stadium Name: </label>
                <input type = "text" class="form-control"  name = "stadium_name" placeholder = "B%"><br>
            </div>
            <div class="form-group">
                <label for="team_name">By Team Name: </label>
                <input type = "text" class="form-control"  name = "team_name" placeholder = "%Sox"><br>
            </div>
            <div class="form-group">
                <label for="years_existed">By Occupancy: </label><br>Minimum: <input type = "number" class="form-control d-inline w-25 mr-5" name = "min_occupancy" min="0" placeholder = "0">Maximum: <input type = "number" class="form-control w-25 d-inline"  name = "max_occupancy" min="1" placeholder = "10000" display="inline"><br>
            </div>
            <div class="form-group">
                <label for="indoor">By Indoor Status: </label><br>
                <input class="ml-0 mr-2" type="radio" name="indoor" value="1">Yes
                <input class="ml-5 mr-2" type="radio" name="indoor" value="0">No
                <input class="ml-5 mr-2" type="radio" name="indoor" value="-1" checked>None<br>
            </div>
            <hr class="pt-2">
            <div class=form-group>
                <label for="sort[]" class='h2 pb-2'>Sort Results By: </label><br>
                <input type="checkbox" class="ml-0 mr-2 d-inline" name="sort[]" value="stadium_name">Stadium Name
                <input type="checkbox" class="ml-5 mr-2 d-inline" name="sort[]" value="occupancy">Occupancy<br>
            </div>
            <button class="btn btn-secondary w-100" name ="search">Search</button>
        </form>
    </div>
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
            if (isset($_POST['search'])){
                $params = [];
                if (strlen($_POST['stadium_name']) > 0){
                    $params['stadium_name'] = " LIKE '{$_POST['stadium_name']}'";
                }
                if (strlen($_POST['team_name']) > 0){
                    $params['team_name'] = " LIKE '{$_POST['team_name']}'";
                }
                if (strlen($_POST['min_occupancy']) > 0){
                    $params['occupancy'] = " >= {$_POST['min_occupancy']}";
                }
                if (strlen($_POST['max_occupancy']) > 0){
                    //IMPORTANT Don't get rid of the space before 'occupancy'
                    $params[' occupancy'] = " <= {$_POST['max_occupancy']}";
                }
                if ($_POST['indoor'] > -1){
                    $params['indoor'] = " = {$_POST['indoor']}";
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
                    
                    if(in_array('occupancy', $_POST['sort'])){
                        $query .= " occupancy ASC,";
                    }
                    if(in_array('stadium_name', $_POST['sort'])){
                        $query .= " stadium_name ASC,";
                    }
                    //Getting rid of last Comma
                    $query = substr($query, 0, -1);
                }
            }
            $query .= ";";
            $select_all_stadiums_query = mysqli_query($connection,$query);
            if (!$select_all_stadiums_query){
                $error_msg = mysqli_error($connection);
                $_SESSION['failure'] = "Couldn't load query: $query $error_msg";
                exit();
            }
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
        else{
            $_SESSION['failure'] = "Couldn't load query";
            exit();
        }
    ?>
</tbody>
</table>
</div>
</body>
</html>