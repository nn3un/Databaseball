<?php
session_start();
include 'db.php';

$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS,DB_NAME);
if(isset($_POST['insert'])) {
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
        $error_msg = mysqli_error($connection);
        $_SESSION['failure'] = "Getting team_id Query Failed $error_msg";
        header('Location: batters.php');
        exit();
    }
    else{
        $row = mysqli_fetch_assoc($get_team_id_query);
        $team_id = $row['team_id'];
        $query = "INSERT INTO Batter (first_name, last_name, age, salary, contract_length, team_id, batting_avg, OBP, home_runs) VALUES ('{$first_name}', '{$last_name}', {$age}, {$salary}, {$contract_length}, {$team_id}, {$batting_avg}, {$OBP}, {$home_runs});";
        $insert_query = mysqli_query($connection, $query);
        if (!$insert_query) {
            $error_msg =  mysqli_error($connection);
            $_SESSION['failure'] = "Insertion Failed: $error_msg";
            header("Location: batters.php");
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
        $query = "DELETE FROM Batter WHERE player_id=$player_id;";
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
            <form action="batters.php" method="POST" class="m-5 p-2 border rounded">
                <div class="form-group">
                    <label for="first_name">First Name: </label>
                    <input type = "text" class="form-control"  name = "first_name" required placeholder="John"><br>
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name: </label>
                    <input type = "text" class="form-control"  name = "last_name" required placeholder="Doe"><br>
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
                <div class="form-group">
                    <label for="age">Age: </label>
                    <input type = "number" class="form-control" min="18" name = "age" required placeholder="43"><br>
                </div>
                <div class="form-group">
                    <label for="salary">Salary: </label>
                    <input type = "number" class="form-control"  name = "salary" required placeholder="200000" min="0"><br>
                </div>
                <div class="form-group">
                    <label for="contract_length">Contract Length: </label>
                    <input type = "number" class="form-control"  name = "contract_length" min="0" required placeholder="4"><br>
                </div>
                <div class="form-group">
                    <label for="contract_length">OBP: </label>
                    <input type = "number" step="0.01" class="form-control"  name = "OBP" min="0" max="1" required placeholder="0.3"><br>
                </div>
                <div class="form-group">
                    <label for="contract_length">Batting Average: </label>
                    <input type = "number" step="0.001" class="form-control"  name = "batting_avg" min="0" max="1" required placeholder="0.4"><br>
                </div>
                <div class="form-group">
                    <label for="contract_length">Home Runs: </label>
                    <input type = "number" class="form-control"  name = "home_runs" min="0" required placeholder="35"><br>
                </div>

                <button class="btn btn-success w-100" name="insert">Insert into table</button>
            </form>
        </div>
        <div class="col">
            <form method="POST" class="m-5 p-2 border rounded" action="batters_update.php">
                <div class="form-group">
                    <label for="player_id">Enter Batter Id: </label>
                    <input type = "text" class="form-control" name = "player_id" required><br>
                </div>
                <button class="btn btn-primary m-0 w-100" name = "update">Update</button>
            </form>
            <form method="POST" class="m-5 mt-4 p-2 border rounded" action="batters.php">
                <div class="form-group">
                    <label for="player_id">Enter batter Id: </label>
                    <input type = "text" class="form-control" name = "player_id" required><br>
                </div>
                <button class="btn btn-danger m-0 w-100" name ="delete">Delete</button>
            </form>
        </div>
    </div>
</div>

<div class="container">
    <div class="border rounded my-3 px-2 py-0">
        <form class="m-1 p-2" name="search" method="POST" action="batters.php">
            <p class="h2 pb-2">Search in batters</p>
            <hr>
            <div class="form-group">
                <label for="first_name">By First Name: </label>
                <input type = "text" class="form-control"  name = "first_name" placeholder = "John_%"><br>
            </div>
            <div class="form-group">
                <label for="last_name">By Last Name: </label>
                <input type = "text" class="form-control"  name = "last_name" placeholder = "John_%"><br>
            </div>
            <div class="form-group">
                <label for="team_name">By Team Name: </label>
                <input type = "text" class="form-control"  name = "team_name" placeholder = "%Sox"><br>
            </div>
            <div class="form-group">
                <label for="salary">By Salary: </label><br>Minimum: <input type = "number" class="form-control d-inline w-25 mr-5" name = "min_salary" min="0" placeholder = "0">Maximum: <input type = "number" class="form-control w-25 d-inline"  name = "max_salary" min="1" placeholder = "100" display="inline">
            </div>
            <div class="form-group">
                <label for="salary">By Age: </label><br>Minimum: <input type = "number" class="form-control d-inline w-25 mr-5" name = "min_age" min="0" placeholder = "0">Maximum: <input type = "number" class="form-control w-25 d-inline"  name = "max_age" min="1" placeholder = "50" display="inline">
            </div>
            <div class="form-group">
                <label for="salary">By OBP: </label><br>Minimum: <input type = "number" step="0.001" class="form-control d-inline w-25 mr-5" name = "min_OBP" min="0" max="1" placeholder = "0.2">Maximum: <input type = "number" step="0.001" class="form-control w-25 d-inline"  name = "max_OBP" min="0" max="1" placeholder = "0.5" display="inline">
            </div>
            <div class="form-group">
                <label for="salary">By Batting Average: </label><br>Minimum: <input type = "number" step="0.001" class="form-control d-inline w-25 mr-5" name = "min_avg" min="0" max="1" placeholder = "0.2">Maximum: <input type = "number" step="0.001" class="form-control w-25 d-inline"  name = "max_avg" min="0" max="1" placeholder = "0.6" display="inline">
            </div>
            <div class="form-group">
                <label for="salary">By Home Runs: </label><br>Minimum: <input type = "number" class="form-control d-inline w-25 mr-5" name = "min_runs" min="0" placeholder = "2">Maximum: <input type = "number" class="form-control w-25 d-inline"  name = "max_runs" min="0" placeholder = "50" display="inline">
            </div>
            <hr class="pt-2">
            <div class=form-group>
                <label for="sort[]" class='h2 pb-2'>Sort Results By: </label><br>
                <input type="checkbox" class="ml-0 mr-2 d-inline" name="sort[]" value="first_name">First Name
                <input type="checkbox" class="ml-4 mr-2 d-inline" name="sort[]" value="last_name">Last Name
                <input type="checkbox" class="ml-4 mr-2 d-inline" name="sort[]" value="salary">Salary
                <input type="checkbox" class="ml-4 mr-2 d-inline" name="sort[]" value="age">Age
                <input type="checkbox" class="ml-4 mr-2 d-inline" name="sort[]" value="contract_length">Contract Length
                <input type="checkbox" class="ml-4 mr-2 d-inline" name="sort[]" value="batting_avg">Batting Average
                <input type="checkbox" class="ml-4 mr-2 d-inline" name="sort[]" value="OBP">OBP
                <input type="checkbox" class="ml-4 mr-2 d-inline" name="sort[]" value="home_runs">Home Runs<br>
            </div>
            <button class="btn btn-secondary w-100" name ="search">Search</button>
        </form>
    </div>
<table class=" table-bordered table table-striped">
    <thead>
        <tr>
            <th>player_id</th> 
            <th>First Name</th>
            <th>Last Name</th>
            <th>Team Name</th> 
            <th>Age</th>
            <th>Salary</th>
            <th>Contract Length</th>
            <th>Batting Average</th>
            <th>OBP</th>
            <th>Home Runs</th>
        </tr>
    </thead>
    <tbody>

    <?php
        if($connection){
            $query = "SELECT player_id, first_name, last_name, team_name, age, salary, contract_length, batting_avg, OBP, home_runs FROM Batter natural join Team";
            if (isset($_POST['search'])){
                $params = [];
                if (strlen($_POST['first_name']) > 0){
                    $params['first_name'] = " LIKE '{$_POST['first_name']}'";
                }
                if (strlen($_POST['last_name']) > 0){
                    $params['last_name'] = " LIKE '{$_POST['last_name']}'";
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
                if (strlen($_POST['min_runs']) > 0){
                    $params['home_runs'] = " >= {$_POST['min_runs']}";
                }
                //IMPORTANT Don't get rid of the space before 'coach_age'
                if (strlen($_POST['max_runs']) > 0){
                    $params[' home_runs'] = " <= {$_POST['max_runs']}";
                }
                if (strlen($_POST['min_OBP']) > 0){
                    $params['OBP'] = " >= {$_POST['min_OBP']}";
                }
                //IMPORTANT Don't get rid of the space before 'coach_age'
                if (strlen($_POST['max_OBP']) > 0){
                    $params[' OBP'] = " <= {$_POST['max_OBP']}";
                }
                if (strlen($_POST['min_avg']) > 0){
                    $params['batting_avg'] = " >= {$_POST['min_avg']}";
                }
                //IMPORTANT Don't get rid of the space before 'coach_age'
                if (strlen($_POST['max_avg']) > 0){
                    $params[' batting_avg'] = " <= {$_POST['max_avg']}";
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
                    if(in_array('batting_avg', $_POST['sort'])){
                        $query .= " batting_avg ASC,";
                    }
                    if(in_array('OBP', $_POST['sort'])){
                        $query .= " OBP ASC,";
                    }
                    if(in_array('home_runs', $_POST['sort'])){
                        $query .= " home_runs ASC,";
                    }
                    if(in_array('salary', $_POST['sort'])){
                        $query .= " salary ASC,";
                    }
                    if(in_array('age', $_POST['sort'])){
                        $query .= " age ASC,";
                    }
                    if(in_array('contract_length', $_POST['sort'])){
                        $query .= " contract_length ASC,";
                    }
                    if(in_array('last_name', $_POST['sort'])){
                        $query .= " last_name ASC,";
                    }
                    if(in_array('first_name', $_POST['sort'])){
                        $query .= " first_name ASC,";
                    }
                    //Getting rid of last Comma
                    $query = substr($query, 0, -1);
                }
            }
            $query .= ";";
            $select_all_batters_query = mysqli_query($connection,$query);
            if (!$select_all_batters_query){
                $error_msg = mysqli_error($connection);
                $_SESSION['failure'] = "Couldn't load query: $query $error_msg";
                exit();
            }
            while($row = mysqli_fetch_assoc($select_all_batters_query)) {
            echo "
            <tr>
                <td>".$row['player_id']."</td>
                <td>".$row['first_name']."</td>
                <td>".$row['last_name']."</td>
                <td>".$row['team_name']."</td>
                <td>".$row['age']."</td>
                <td>".$row['salary']."</td>
                <td>".$row['contract_length']."</td>
                <td>".$row['batting_avg']."</td>
                <td>".$row['OBP']."</td>
                <td>".$row['home_runs']."</td>
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