<?php

session_start();
include 'db.php';


$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS,DB_NAME);
if(isset($_POST['insert'])) {

    $home_team_name = $_POST['home_team_name'];
    $away_team_name = $_POST['away_team_name'];
    $home_team_runs = $_POST['home_team_runs'];
    $away_team_runs = $_POST['away_team_runs'];
    
    if ($home_team_name == $away_team_name){
        $error_msg = mysqli_error($connection);
        $_SESSION['failure'] = "Home team and Away team must be different";
        header('Location: games.php');
        exit();
    }
    $query = "SELECT team_id FROM Team WHERE team_name='{$home_team_name}'";
    $get_team_id_query = mysqli_query($connection, $query);
    if (!$get_team_id_query) {
        $error_msg = mysqli_error($connection);
        $_SESSION['failure'] = "Getting home_team_id Query Failed $error_msg";
        header('Location: games.php');
        exit();
    }
    $row = mysqli_fetch_assoc($get_team_id_query);
    $home_team_id = $row['team_id'];

    $query = "SELECT team_id FROM Team WHERE team_name='{$away_team_name}'";
    $get_team_id_query = mysqli_query($connection, $query);
    if (!$get_team_id_query) {
        $error_msg = mysqli_error($connection);
        $_SESSION['failure'] = "Getting away_team_id Query Failed $error_msg";
        header('Location: games.php');
        exit();
    }
    $row = mysqli_fetch_assoc($get_team_id_query);
    $away_team_id = $row['team_id'];

    $query = "INSERT INTO Game (home_team_id, away_team_id, home_team_runs, away_team_runs) VALUES ({$home_team_id}, {$away_team_id}, {$home_team_runs}, {$away_team_runs});";
    $insert_query = mysqli_query($connection, $query);
    if (!$insert_query) {
        $error_msg = mysqli_error($connection);
        $_SESSION['failure'] = "Insertion failed: $error_msg";
        header('Location: games.php');
        exit();
    }
    else{
        $_SESSION['success'] = "Insertion successful!";
    }
}

else if(isset($_POST['delete'])) {
    $game_id = $_POST['game_id'];
    if (!empty($game_id)) {
        $query = "DELETE FROM Game WHERE game_id=$game_id;";
        $delete_query = mysqli_query($connection, $query);
        if (!$delete_query || mysqli_affected_rows($connection) <= 0) {
            $error_msg = mysqli_error($connection);
            $_SESSION['failure'] = "Deletion failed, maybe wrong game id?";
            header('Location: games.php');
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
            <form action="games.php" method="POST" class="m-5 p-2 border rounded">
                <div class="form-group">
                    <label for="home_team_name">Home Team: </label>
                    <select class = "w-100 mx-1 form-control" name="home_team_name">
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

                <div class="form-group">
                    <label for="away_team_name">Away Team: </label>
                    <select class = "w-100 mx-1 form-control" name="away_team_name">
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

                <div class="form-group">
                    <label for="home_team_runs">Home Team Runs: </label><br>
                    <input type = "number" min="0" class="form-control" name = "home_team_runs" placeholder = "5" required><br>
                </div>

                <div class="form-group">
                    <label for="away_team_runs">Away Team Runs: </label><br>
                    <input type = "number" min="0" class="form-control" name = "away_team_runs" placeholder = "5" required><br>
                </div>

                <button class="btn btn-success w-100" name="insert">Insert into table</button>
            </form>
        </div>
        <div class="col">
            <form method="POST" class="m-5 p-2 border rounded" action="games_update.php">
                <div class="form-group">
                    <label for="game_id">Enter Game Id: </label>
                    <input type = "number" class="form-control" name = "game_id" required><br>
                </div>
                <button class="btn btn-primary m-0 w-100" name = "update">Update</button>
            </form>
            <form method="POST" class="m-5 mt-4 p-2 border rounded" action="games.php">
                <div class="form-group">
                    <label for="game_id">Enter Game Id: </label>
                    <input type = "number" class="form-control" name = "game_id" required><br>
                </div>
                <button class="btn btn-danger m-0 w-100" name ="delete">Delete</button>
            </form>
        </div>
    </div>
</div>

<div class="container">
    <div class="border rounded my-3 px-2 py-0">
    <form class="m-1 p-2" name="search" method="POST" action="games.php">
        <p class="h2 pb-2">Search in Games</p>
        <hr>
            <div class="form-group">
                <label for="home_team_name">By Home Team: </label>
                <input type = "text" class="form-control"  name = "home_team_name" placeholder = "%Sox"><br>
            </div>
            <div class="form-group">
                <label for="home_team_name">By Away Team: </label>
                <input type = "text" class="form-control"  name = "away_team_name" placeholder = "Diamond%"><br>
            </div>
            <div class="form-group">
                <label for="home_team_runs">By Home Team Runs: </label><br>Minimum: <input type = "number" class="form-control d-inline w-25 mr-5" name = "min_home_team_runs" min="0" placeholder = "0">Maximum: <input type = "number" class="form-control w-25 d-inline"  name = "max_home_team_runs" min="1" placeholder = "10000" display="inline"><br>
            </div>
            <div class="form-group">
                <label for="away_team_runs">By Away Team Runs: </label><br>Minimum: <input type = "number" class="form-control d-inline w-25 mr-5" name = "min_away_team_runs" min="0" placeholder = "0">Maximum: <input type = "number" class="form-control w-25 d-inline"  name = "max_away_team_runs" min="1" placeholder = "10000" display="inline"><br>
            </div>
            <div class="form-group">
                <label for="winner">By Winner: </label><br>
                <input class="ml-0 mr-2" type="radio" name="winner" value="1">Home Team
                <input class="ml-5 mr-2" type="radio" name="winner" value="0">Away Team
                <input class="ml-5 mr-2" type="radio" name="winner" value="-1" checked>None<br>
            </div>
            <hr class="pt-2">
            <div class=form-group>
                    <label for="sort[]" class='h2 pb-2'>Sort Results By: </label><br>
                    <input type="checkbox" class="ml-0 mr-2 d-inline" name="sort[]" value="home_team_name">Home Team
                    <input type="checkbox" class="ml-5 mr-2 d-inline" name="sort[]" value="away_team_name">Away Team
                    <input type="checkbox" class="ml-5 mr-2 d-inline" name="sort[]" value="home_team_runs">Home Team Runs
                    <input type="checkbox" class="ml-5 mr-2 d-inline" name="sort[]" value="away_team_runs">Away Team Runs<br>
            </div>
            <button class="btn btn-secondary w-100" name ="search">Search</button>
    </form>
    </div>
    <table class=" table-bordered table table-striped">
        <thead>
            <tr>
                <th>game_id</th> 
                <th>Home Team</th>
                <th>Away Team</th> 
                <th>Home Team Runs</th>
                <th>Away Team Runs</th>
            </tr>
        </thead>
        <tbody>
           <?php
           if($connection){
            $query = "SELECT game_id, home_team_name,  away_team_name, home_team_runs, away_team_runs FROM Game natural join (select game_id, team_name as home_team_name from Game left outer join Team on home_team_id = team_id) as home_team_info natural join
            (select game_id, team_name as away_team_name from Game left outer join Team on away_team_id = team_id) as away_team_info";
            if (isset($_POST['search'])){
                $params = [];
                if (strlen($_POST['home_team_name']) > 0){
                    $params['home_team_name'] = " LIKE '{$_POST['home_team_name']}'";
                }
                if (strlen($_POST['away_team_name']) > 0){
                    $params['away_team_name'] = " LIKE '{$_POST['away_team_name']}'";
                }
                if (strlen($_POST['min_home_team_runs']) > 0){
                    $params['home_team_runs'] = " >= {$_POST['min_home_team_runs']}";
                }
                //IMPORTANT Don't get rid of the space before 'home_team_runs'
                if (strlen($_POST['max_home_team_runs']) > 0){
                    $params[' home_team_runs'] = " <= {$_POST['max_home_team_runs']}";
                }
                if (strlen($_POST['min_away_team_runs']) > 0){
                    $params['away_team_runs'] = " >= {$_POST['min_away_team_runs']}";
                }
                //IMPORTANT Don't get rid of the space before 'away_team runs'
                if (strlen($_POST['max_away_team_runs']) > 0){
                    $params[' away_team_runs'] = " <= {$_POST['max_away_team_runs']}";
                }
                //IMPORTANT Don't get rid of the space after 'home_team_runs'
                if ($_POST['winner'] == 1){
                    $params['home_team_runs '] = " > away_team_runs";
                }
                //IMPORTANT Don't get rid of the space after 'away_team_runs'
                else if ($_POST['winner'] == 0){
                    $params['away_team_runs '] = " > home_team_runs";
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
                    
                    if(in_array('home_team_runs', $_POST['sort'])){
                        $query .= " home_team_runs ASC,";
                    }
                    if(in_array('away_team_runs', $_POST['sort'])){
                        $query .= " away_team_runs ASC,";
                    }
                    if(in_array('home_team_name', $_POST['sort'])){
                        $query .= " home_team_name ASC,";
                    }
                    if(in_array('away_team_name', $_POST['sort'])){
                        $query .= " away_team_name ASC,";
                    }
                    //Getting rid of last Comma
                    $query = substr($query, 0, -1);
                }
            }
            $query .= ";";
            $select_all_games_query = mysqli_query($connection,$query);
            if(!$select_all_games_query){
                $error_msg = mysqli_error($connection);
                $_SESSION['failure'] = "Couldn't load query: $query $error_msg";
                exit();
            }
            while($row = mysqli_fetch_assoc($select_all_games_query)) {
                echo "
                <tr>
                <td>".$row['game_id']."</td>
                <td>".$row['home_team_name']."</td>
                <td>".$row['away_team_name']."</td>
                <td>".$row['home_team_runs']."</td>
                <td>".$row['away_team_runs']."</td>
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