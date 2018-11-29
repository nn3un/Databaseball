<?php
session_start();
include 'db.php';

$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS,DB_NAME);
if(isset($_POST['insert'])) {
    $coach_name = $_POST['coach_name'];
    $coach_age = $_POST['coach_age'];
    $coach_salary = $_POST['coach_salary'];
    $coach_contract_length = $_POST['coach_contract_length'];
    $team_name = $_POST['team_name'];
    $query = "SELECT team_id FROM Team WHERE team_name='{$team_name}'";
    $get_team_id_query = mysqli_query($connection, $query);
    if (!$get_team_id_query) {
        $error_msg = mysqli_error($connection);
        $_SESSION['failure'] = "Getting team_id Query Failed $error_msg";
        header('Location: coaches.php');
        exit();
    }
    else{
        $row = mysqli_fetch_assoc($get_team_id_query);
        $team_id = $row['team_id'];
        $query = "INSERT INTO Coach (coach_name, coach_age, coach_salary, coach_contract_length, team_id) VALUES ('{$coach_name}', '{$coach_age}', '{$coach_salary}', '{$coach_contract_length}', '{$team_id}');";
        $insert_query = mysqli_query($connection, $query);
        if (!$insert_query) {
            $error_msg =  mysqli_error($connection);
            $_SESSION['failure'] = "Insertion Failed: $error_msg";
            header("Location: coaches.php");
            exit();
        }
        else{
            $_SESSION['success'] = "Insertion success!";
        }
    }
}
else if(isset($_POST['delete'])) {
    $coach_id = $_POST['coach_id'];
    if (!empty($coach_id)) {
        $query = "DELETE FROM Coach WHERE coach_id=$coach_id;";
        $delete_query = mysqli_query($connection, $query);
        if (mysqli_affected_rows($connection) > 0) {
            $_SESSION['success'] = "Succcessfully deleted";
        }
        else{
            $error_msg = mysqli_error($connection);
            $_SESSION['failure'] = "coach_id $coach_id does not exist";
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
            <form action="coaches.php" method="POST" class="m-5 p-2 border rounded">
                <div class="form-group">
                    <label for="coach_name">Coach Name: </label>
                    <input type = "text" class="form-control"  name = "coach_name" required placeholder="John Doe"><br>
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
                    <label for="coach_age">Coach Age: </label>
                    <input type = "number" class="form-control" min="18" name = "coach_age" required placeholder="43"><br>
                </div>
                <div class="form-group">
                    <label for="coach_salary">Coach Salary: </label>
                    <input type = "number" class="form-control"  name = "coach_salary" required placeholder="200000" min="0"><br>
                </div>
                <div class="form-group">
                    <label for="coach_contract_length">Coach Contract Length: </label>
                    <input type = "number" class="form-control"  name = "coach_contract_length" min="0" required placeholder="4"><br>
                </div>

                <button class="btn btn-success w-100" name="insert">Insert into table</button>
            </form>
        </div>
        <div class="col">
            <form method="POST" class="m-5 p-2 border rounded" action="coaches_update.php">
                <div class="form-group">
                    <label for="coach_id">Enter Coach Id: </label>
                    <input type = "text" class="form-control" name = "coach_id" required><br>
                </div>
                <button class="btn btn-primary m-0 w-100" name = "update">Update</button>
            </form>
            <form method="POST" class="m-5 mt-4 p-2 border rounded" action="coaches.php">
                <div class="form-group">
                    <label for="coach_id">Enter Coach Id: </label>
                    <input type = "text" class="form-control" name = "coach_id" required><br>
                </div>
                <button class="btn btn-danger m-0 w-100" name ="delete">Delete</button>
            </form>
        </div>
    </div>
</div>

<div class="container">
    <div class="border rounded my-3 px-2 py-0">
        <form class="m-1 p-2" name="search" method="POST" action="coaches.php">
            <p class="h2 pb-2">Search in Coaches</p>
            <hr>
            <div class="form-group">
                <label for="coach_name">By Coach Name: </label>
                <input type = "text" class="form-control"  name = "coach_name" placeholder = "John_%"><br>
            </div>
            <div class="form-group">
                <label for="team_name">By Team Name: </label>
                <input type = "text" class="form-control"  name = "team_name" placeholder = "%Sox"><br>
            </div>
            <div class="form-group">
                <label for="salary">By Salary: </label><br>Minimum: <input type = "number" class="form-control d-inline w-25 mr-5" name = "min_salary" min="0" placeholder = "0">Maximum: <input type = "number" class="form-control w-25 d-inline"  name = "max_salary" min="1" placeholder = "100" display="inline">
            </div>
            <div class="form-group">
                <label for="salary">By Age: </label><br>Minimum: <input type = "number" class="form-control d-inline w-25 mr-5" name = "min_age" min="0" placeholder = "0">Maximum: <input type = "number" class="form-control w-25 d-inline"  name = "max_age" min="1" placeholder = "100" display="inline">
            </div>
            <hr class="pt-2">
            <div class=form-group>
                <label for="sort[]" class='h2 pb-2'>Sort Results By: </label><br>
                <input type="checkbox" class="ml-0 mr-2 d-inline" name="sort[]" value="coach_name">Coach Name
                <input type="checkbox" class="ml-5 mr-2 d-inline" name="sort[]" value="coach_salary">Salary
                <input type="checkbox" class="ml-5 mr-2 d-inline" name="sort[]" value="coach_age">Age
                <input type="checkbox" class="ml-5 mr-2 d-inline" name="sort[]" value="coach_contract_length">Contract Length<br>
            </div>
            <button class="btn btn-secondary w-100" name ="search">Search</button>
        </form>
    </div>
<table class=" table-bordered table table-striped">
    <thead>
        <tr>
            <th>coach_id</th> 
            <th>Coach Name</th>
            <th>Team Name</th> 
            <th>Age</th>
            <th>Salary</th>
            <th>Contract Length</th>
        </tr>
    </thead>
    <tbody>

    <?php
        if($connection){
            $query = "SELECT coach_id, coach_name, team_name, coach_age, coach_salary, coach_contract_length FROM Coach natural join Team";
            if (isset($_POST['search'])){
                $params = [];
                if (strlen($_POST['coach_name']) > 0){
                    $params['coach_name'] = " LIKE '{$_POST['coach_name']}'";
                }
                if (strlen($_POST['team_name']) > 0){
                    $params['team_name'] = " LIKE '{$_POST['team_name']}'";
                }
                if (strlen($_POST['min_salary']) > 0){
                    $params['coach_salary'] = " >= {$_POST['min_salary']}";
                }
                //IMPORTANT Don't get rid of the space before 'coach_salary'
                if (strlen($_POST['max_salary']) > 0){
                    $params[' coach_salary'] = " <= {$_POST['max_salary']}";
                }
                if (strlen($_POST['min_age']) > 0){
                    $params['coach_age'] = " >= {$_POST['min_age']}";
                }
                //IMPORTANT Don't get rid of the space before 'coach_age'
                if (strlen($_POST['max_age']) > 0){
                    $params[' coach_age'] = " <= {$_POST['max_age']}";
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
                    
                    if(in_array('coach_salary', $_POST['sort'])){
                        $query .= " coach_salary ASC,";
                    }
                    if(in_array('coach_age', $_POST['sort'])){
                        $query .= " coach_age ASC,";
                    }
                    if(in_array('coach_contract_length', $_POST['sort'])){
                        $query .= " coach_contract_length ASC,";
                    }
                    if(in_array('coach_name', $_POST['sort'])){
                        $query .= " coach_name ASC,";
                    }
                    //Getting rid of last Comma
                    $query = substr($query, 0, -1);
                }
            }
            $query .= ";";
            $select_all_coaches_query = mysqli_query($connection,$query);
            if (!$select_all_coaches_query){
                $error_msg = mysqli_error($connection);
                $_SESSION['failure'] = "Couldn't load query: $query $error_msg";
                exit();
            }
            while($row = mysqli_fetch_assoc($select_all_coaches_query)) {
            echo "
            <tr>
                <td>".$row['coach_id']."</td>
                <td>".$row['coach_name']."</td>
                <td>".$row['team_name']."</td>
                <td>".$row['coach_age']."</td>
                <td>".$row['coach_salary']."</td>
                <td>".$row['coach_contract_length']."</td>
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