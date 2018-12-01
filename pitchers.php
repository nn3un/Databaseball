<?php
session_start();
include 'db.php';

$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS,DB_NAME);
if(isset($_POST['insert'])) {
    $player_id = $_POST['player_id'];
    $wins = $_POST['wins'];
    $losses = $_POST['losses'];
    $ERA = $_POST['ERA'];
    $innings_pitched = $_POST['innings_pitched'];
    $query = "SELECT player_id, first_name, last_name, team_id, team_name, salary, age, contract_length, wins, losses, ERA, innings_pitched FROM Pitcher natural join Batter natural join Team";
    $get_team_id_query = mysqli_query($connection, $query);
    if (!$get_team_id_query) {
        $error_msg = mysqli_error($connection);
        $_SESSION['failure'] = "Getting team_id Query Failed $error_msg";
        header('Location: pitchers.php');
        exit();
    }
    else{
        $row = mysqli_fetch_assoc($get_team_id_query);
        $team_id = $row['team_id'];
        $query = "INSERT INTO Pitcher (player_id, wins, losses, ERA, innings_pitched) VALUES ('{$player_id}', '{$wins}', '{$losses}', '{$ERA}', '{$innings_pitched}');";
        $insert_query = mysqli_query($connection, $query);
        if (!$insert_query) {
            $error_msg =  mysqli_error($connection);
            $_SESSION['failure'] = "Insertion Failed: $error_msg";
            header("Location: pitchers.php");
            exit();
        }
        else{
            $_SESSION['success'] = "Insertion success!";
        }
    }
}
else if(isset($_POST['delete'])) {
    $player_id = $_POST['player_id'];
    if (!empty($player_id)) {
        $query = "DELETE FROM Pitcher WHERE player_id=$player_id;";
        $delete_query = mysqli_query($connection, $query);
        if (mysqli_affected_rows($connection) > 0) {
            $_SESSION['success'] = "Succcessfully deleted";
        }
        else{
            $error_msg = mysqli_error($connection);
            $_SESSION['failure'] = "player_id $player_id does not exist";
        }
    }
    else{
    	$_SESSION['failure'] = "Please enter all information";
    }
}
?>
<?php include 'header.php';?>
    <div class="row">
        <div class="col">
            <form action="pitchers.php" method="POST" class="m-5 p-2 border rounded">
                <div class="form-group">
                    <label for="pitcher_name">player_id: </label>
                    <input type = "text" class="form-control"  name = "player_id" required placeholder=""><br>
                </div>
                <div class="form-group">
                    <label for="pitcher_age">Wins: </label>
                    <input type = "number" class="form-control" min="0" name = "wins" required placeholder="0"><br>
                </div>
                <div class="form-group">
                    <label for="pitcher_age">Losses: </label>
                    <input type = "number" class="form-control" min="0" name = "losses" required placeholder="0"><br>
                </div>
                <div class="form-group">
                    <label for="pitcher_age">ERA: </label>
                    <input type = "number" step="0.001" class="form-control" min="0" name = "ERA" required placeholder="0"><br>
                </div>
                <div class="form-group">
                    <label for="pitcher_age">Innings Pitched: </label>
                    <input type = "number" class="form-control" min="0" name = "innings_pitched" required placeholder="0"><br>
                </div>
                <button class="btn btn-success w-100" name="insert">Insert into table</button>
            </form>
        </div>
        <div class="col">
            <form method="POST" class="m-5 p-2 border rounded" action="pitchers_update.php">
                <div class="form-group">
                    <label for="player_id">Enter Player Id: </label>
                    <input type = "text" class="form-control" name = "player_id" required><br>
                </div>
                <button class="btn btn-primary m-0 w-100" name = "update">Update</button>
            </form>
            <form method="POST" class="m-5 mt-4 p-2 border rounded" action="pitchers.php">
                <div class="form-group">
                    <label for="player_id">Enter Player Id: </label>
                    <input type = "text" class="form-control" name = "player_id" required><br>
                </div>
                <button class="btn btn-danger m-0 w-100" name ="delete">Delete</button>
            </form>
        </div>
    </div>
</div>

<div class="container">
    <div class="border rounded my-3 px-2 py-0">
        <form class="m-1 p-2" name="search" method="POST" action="pitchers.php">
            <p class="h2 pb-2">Search in Pitchers</p>
            <hr>
            <div class="form-group">
                <label for="last_name">By Pitcher Last Name: </label>
                <input type = "text" class="form-control"  name = "last_name" placeholder = "John_%"><br>
            </div>
            <div class="form-group">
                <label for="first_name">By Pitcher First Name: </label>
                <input type = "text" class="form-control"  name = "first_name" placeholder = "John_%"><br>
            </div>
            <div class="form-group">
                <label for="team_name">By Team Name: </label>
                <input type = "text" class="form-control"  name = "team_name" placeholder = "%Sox"><br>
            </div>
            <div class="form-group">
                <label for="age">Age: </label><br>Minimum: <input type = "number" step="0.001" class="form-control d-inline w-25 mr-5" name = "min_age" min="0" placeholder = "0">Maximum: <input type = "number" step="0.001" class="form-control w-25 d-inline"  name = "max_age" min="0" placeholder = "0" display="inline">
            </div>
            <div class="form-group">
                <label for="salary">Salary: </label><br>Minimum: <input type = "number" step="0.001" class="form-control d-inline w-25 mr-5" name = "min_salary" min="0" placeholder = "0">Maximum: <input type = "number" step="0.001" class="form-control w-25 d-inline"  name = "max_salary" min="0" placeholder = "0" display="inline">
            </div>
            <div class="form-group">
                <label for="contract_length">By Contract Length: </label><br>Minimum: <input type = "number" step="0.001" class="form-control d-inline w-25 mr-5" name = "min_contract_length" min="0" placeholder = "0">Maximum: <input type = "number" step="0.001" class="form-control w-25 d-inline"  name = "max_contract_length" min="0" placeholder = "0" display="inline">
            </div>
            <div class="form-group">
                <label for="ERA">By ERA: </label><br>Minimum: <input type = "number" step = "0.001" class="form-control d-inline w-25 mr-5" name = "min_ERA" min="0" placeholder = "0">Maximum: <input type = "number" step = "0.001" class="form-control w-25 d-inline"  name = "max_ERA" min="0" placeholder = "0" display="inline">
            </div>
            <div class="form-group">
                <label for="innings_pitched">By Innings Pitched: </label><br>Minimum: <input type = "number" step="0.001" class="form-control d-inline w-25 mr-5" name = "min_innings_pitched" min="0" placeholder = "0">Maximum: <input type = "number" step="0.001" class="form-control w-25 d-inline"  name = "max_innings_pitched" min="0" placeholder = "0" display="inline">
            </div>
            <hr class="pt-2">
            <div class=form-group>
                <label for="sort[]" class='h2 pb-2'>Sort Results By: </label><br>
                <input type="checkbox" class="ml-0 mr-2 d-inline" name="sort[]" value="last_name">Last Name
                <input type="checkbox" class="ml-5 mr-2 d-inline" name="sort[]" value="first_name">First Name
                <input type="checkbox" class="ml-5 mr-2 d-inline" name="sort[]" value="team_name">Team Name
                <input type="checkbox" class="ml-5 mr-2 d-inline" name="sort[]" value="wins">Wins
                <input type="checkbox" class="ml-5 mr-2 d-inline" name="sort[]" value="losses">Losses
                <input type="checkbox" class="ml-5 mr-2 d-inline" name="sort[]" value="ERA">ERA
                <input type="checkbox" class="ml-5 mr-2 d-inline" name="sort[]" value="innings_pitched">Innings Pitched<br>
            </div>
            <button class="btn btn-secondary w-100" name ="search">Search</button>
        </form>
    </div>
<table class=" table-bordered table table-striped">
    <thead>
        <tr>
            <th>player_id</th> 
            <th>Last Name</th>
            <th>First Name</th> 
            <th>team_id</th> 
            <th>Team Name</th>
            <th>Age</th>            
            <th>Salary</th>
            <th>Contract Length</th>
            <th>Wins</th> 
            <th>Losses</th> 
            <th>ERA</th>
            <th>Innings Pitched</th>
        </tr>
    </thead>
    <tbody>

    <?php
        if($connection){
            $query = "SELECT player_id, first_name, last_name, team_id, team_name, age, salary, contract_length, wins, losses, ERA, innings_pitched FROM Pitcher natural join Batter natural join Team ";
            if (isset($_POST['search'])){
                $params = [];
                if (strlen($_POST['last_name']) > 0){
                    $params['last_name'] = " LIKE '{$_POST['last_name']}'";
                }
                if (strlen($_POST['first_name']) > 0){
                    $params['first_name'] = " LIKE '{$_POST['first_name']}'";
                }
                if (strlen($_POST['team_name']) > 0){
                    $params['team_name'] = " LIKE '{$_POST['team_name']}'";
                }
                if (strlen($_POST['min_salary']) > 0){
                    $params['salary'] = " >= {$_POST['min_salary']}";
                }
                //IMPORTANT Don't get rid of the space before 'coach_salary'
                if (strlen($_POST['max_salary']) > 0){
                    $params[' salary'] = " <= {$_POST['max_salary']}";
                }
                if (strlen($_POST['min_age']) > 0){
                    $params['age'] = " >= {$_POST['min_age']}";
                }
                //IMPORTANT Don't get rid of the space before 'coach_age'
                if (strlen($_POST['max_age']) > 0){
                    $params[' age'] = " <= {$_POST['max_age']}";
                }
                if (strlen($_POST['min_contract_length']) > 0){
                    $params['contract_length'] = " >= {$_POST['min_contract_length']}";
                }
                //IMPORTANT Don't get rid of the space before 'coach_age'
                if (strlen($_POST['max_contract_length']) > 0){
                    $params[' contract_length'] = " <= {$_POST['max_contract_length']}";
                }        
                if (strlen($_POST['min_ERA']) > 0){
                    $params['ERA'] = " >= {$_POST['min_ERA']}";
                }
                //IMPORTANT Don't get rid of the space before 'coach_age'
                if (strlen($_POST['max_ERA']) > 0){
                    $params[' ERA'] = " <= {$_POST['max_ERA']}";
                }        
                if (strlen($_POST['min_innings_pitched']) > 0){
                    $params['innings_pitched'] = " >= {$_POST['min_innings_pitched']}";
                }
                //IMPORTANT Don't get rid of the space before 'coach_age'
                if (strlen($_POST['max_innings_pitched']) > 0){
                    $params[' innings_pitched'] = " <= {$_POST['max_innings_pitched']}";
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
                    
                    if(in_array('last_name', $_POST['sort'])){
                        $query .= " last_name ASC,";
                    }
                    if(in_array('first_name', $_POST['sort'])){
                        $query .= " first_name ASC,";
                    }
                    if(in_array('team_name', $_POST['sort'])){
                        $query .= " team_name ASC,";
                    }
                    if(in_array('wins', $_POST['sort'])){
                        $query .= " wins ASC,";
                    }
                    if(in_array('losses', $_POST['sort'])){
                        $query .= " losses ASC,";
                    }
                    if(in_array('ERA', $_POST['sort'])){
                        $query .= " ERA ASC,";
                    }
                    if(in_array('innings_pitched', $_POST['sort'])){
                        $query .= " innings_pitched ASC,";
                    }
                    //Getting rid of last Comma
                    $query = substr($query, 0, -1);
                }
            }
            $query .= " ;";
            $select_all_pitchers_query = mysqli_query($connection,$query);
            if (!$select_all_pitchers_query){
                $error_msg = mysqli_error($connection);
                $_SESSION['failure'] = "Couldn't load query: $query $error_msg";
                exit();
            }
            while($row = mysqli_fetch_assoc($select_all_pitchers_query)) {
            echo "
            <tr>
                <td>".$row['player_id']."</td>
                <td>".$row['last_name']."</td>
                <td>".$row['first_name']."</td>
                <td>".$row['team_id']."</td>
                <td>".$row['team_name']."</td>
                <td>".$row['age']."</td>
                <td>".$row['salary']."</td>
                <td>".$row['contract_length']."</td>
                <td>".$row['wins']."</td>
                <td>".$row['losses']."</td>
                <td>".$row['ERA']."</td>
                <td>".$row['innings_pitched']."</td>
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