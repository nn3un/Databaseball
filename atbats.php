<?php
session_start();
include 'db.php';

$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS,DB_NAME);
if(isset($_POST['insert'])) {    
    $batter_id = $_POST['batter_id'];
    $pitcher_id = $_POST['pitcher_id'];
    if ($batter_id == $pitcher_id){
        $_SESSION['failure'] = "Pitcher and Batters must be different";
        header("Location: atbats.php");
        exit();
    }
    $game_id = $_POST['game_id'];
    $strikes = $_POST['strikes'];
    $balls = $_POST['balls'];
    $runs_scored = $_POST['runs_scored'];
    $out_or_not = $_POST['out_or_not'];                    
    $query = "INSERT INTO At_Bat (batter_id, pitcher_id, game_id, strikes, balls, runs_scored, out_or_not) VALUES ('{$batter_id}', '{$pitcher_id}', '{$game_id}', '{$strikes}', '{$balls}', '{$runs_scored}', '{$out_or_not}');";
    $insert_query = mysqli_query($connection, $query);
    if (!$insert_query) {
        $error_msg =  mysqli_error($connection);
        $_SESSION['failure'] = "Insertion Failed: $error_msg";
        header("Location: atbats.php");
        exit();
    }
    else{
        $_SESSION['success'] = "Insertion success!";
    }
}
else if(isset($_POST['delete'])) {
    $at_bat_id = $_POST['at_bat_id'];
    if (!empty($at_bat_id)) {
        $query = "DELETE FROM At_Bat WHERE at_bat_id=$at_bat_id;";
        $delete_query = mysqli_query($connection, $query);
        if (mysqli_affected_rows($connection) > 0) {
            $_SESSION['success'] = "Succcessfully deleted";
        }
        else{
            $error_msg = mysqli_error($connection);
            $_SESSION['failure'] = "at_bat_id $at_bat_id does not exist";
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
            <form action="atbats.php" method="POST" class="m-5 p-2 border rounded">
                <div class="form-group">
                    <label for="batter_id">Batter ID: </label>
                    <input type = "number" class="form-control"  name = "batter_id" required placeholder="12"><br>
                </div>                
                <div class="form-group">
                    <label for="pitcher_id">Pitcher ID: </label>
                    <input type = "number" class="form-control" name = "pitcher_id" required placeholder="43"><br>
                </div>
                <div class="form-group">
                    <label for="game_id">Game ID: </label>
                    <input type = "number" class="form-control"  name = "game_id" required placeholder="24"><br>
                </div>
                <div class="form-group">
                    <label for="strikes">Strikes: </label>
                    <input type = "number" class="form-control"  name = "strikes" min="0" max = "3" required placeholder="1"><br>
                </div>
                <div class="form-group">
                    <label for="balls">Balls: </label>
                    <input type = "number" class="form-control"  name = "balls" min="0" max = "4" required placeholder="1"><br>
                </div>
                <div class="form-group">
                    <label for="runs_scored">Runs Scored: </label>
                    <input type = "number" class="form-control"  name = "runs_scored" min="0" max = "4" required placeholder="1"><br>
                </div>
                <div class="form-group">
                    <label for="out_or_not">Out or Not: </label>
                    <input type = "number" class="form-control"  name = "out_or_not" min="0" max="1" required placeholder="1"><br>
                </div>

                <button class="btn btn-success w-100" name="insert">Insert into table</button>
            </form>
        </div>
        <div class="col">
            <form method="POST" class="m-5 p-2 border rounded" action="atbats_update.php">
                <div class="form-group">
                    <label for="at_bat_id">Enter At Bat Id: </label>
                    <input type = "number" class="form-control" name = "at_bat_id" required><br>
                </div>
                <button class="btn btn-primary m-0 w-100" name = "update">Update</button>
            </form>
            <form method="POST" class="m-5 mt-4 p-2 border rounded" action="atbats.php">
                <div class="form-group">
                    <label for="at_bat_id">Enter At Bat ID: </label>
                    <input type = "number" class="form-control" name = "at_bat_id" required><br>
                </div>
                <button class="btn btn-danger m-0 w-100" name ="delete">Delete</button>
            </form>
        </div>
    </div>
</div>

<div class="container">
    <div class="container">
    <div class="border rounded my-3 px-2 py-0">
        <form class="m-1 p-2" name="search" method="POST" action="atbats.php">
            <p class="h2 pb-2">Search in At-Bats</p>
            <hr>
            <div class="form-group">
                <label for="coach_name">By Batter Name: </label>
                <input type = "text" class="form-control"  name = "bname" placeholder = "Smith"><br>
            </div>
            <div class="form-group">
                <label for="team_name">By Pitcher Name: </label>
                <input type = "text" class="form-control"  name = "pname" placeholder = "Brown"><br>
            </div>
            <div class="form-group">
                <label for="salary">By Strikes: </label><br>Minimum: <input type = "number" class="form-control d-inline w-25 mr-5" name = "min_strikes" min="0" placeholder = "0">Maximum: <input type = "number" class="form-control w-25 d-inline"  name = "max_strikes" min="1" placeholder = "100" display="inline">
            </div>
            <div class="form-group">
                <label for="salary">By Balls: </label><br>Minimum: <input type = "number" class="form-control d-inline w-25 mr-5" name = "min_balls" min="0" placeholder = "0">Maximum: <input type = "number" class="form-control w-25 d-inline"  name = "max_balls" min="1" placeholder = "100" display="inline">
            </div>
            <div class="form-group">
                <label for="indoor">By whether the batter was out: </label><br>
                <input class="ml-0 mr-2" type="radio" name="out_or_not" value="1">Yes
                <input class="ml-5 mr-2" type="radio" name="out_or_not" value="0">No
                <input class="ml-5 mr-2" type="radio" name="out_or_not" value="-1" checked>Either<br>
            </div>
            <hr class="pt-2">
            <div class=form-group>
                <label for="sort[]" class='h2 pb-2'>Sort Results By: </label><br>
                <input type="checkbox" class="ml-0 mr-2 d-inline" name="sort[]" value="bname">Batter Name
                <input type="checkbox" class="ml-5 mr-2 d-inline" name="sort[]" value="pname">Pitcher Name
                <input type="checkbox" class="ml-5 mr-2 d-inline" name="sort[]" value="balls">Balls
                <input type="checkbox" class="ml-5 mr-2 d-inline" name="sort[]" value="strikes">Strikes
                <input type="checkbox" class="ml-5 mr-2 d-inline" name="sort[]" value="runs_scored">Runs Scored<br>
            </div>
            <button class="btn btn-secondary w-100" name ="search">Search</button>
        </form>
    </div>
<table class=" table-bordered table table-striped">
    <thead>
        <tr>
            <th>at_bat_id</th>
            <th>game_id</th> 
            <th>batter_id</th>
            <th>Batter Name</th>
            <th>pitcher_id</th>
            <th>Pitcher Name</th>
            <th>Strikes</th>
            <th>Balls</th> 
            <th>Runs Scored</th>
            <th>Out?</th>            
        </tr>
    </thead>
    <tbody>

    <?php
        if($connection){
            $query = "SELECT at_bat_id, game_id, batter_id, B1.last_name as bname, pitcher_id, B2.last_name as pname, strikes, balls, runs_scored, out_or_not FROM At_Bat, Batter as B1, Batter as B2 WHERE At_Bat.batter_id = B1.player_id AND At_Bat.pitcher_id = B2.player_id ";
            if (isset($_POST['search'])){
                $params = [];
                if (strlen($_POST['bname']) > 0){
                    $params['B1.last_name'] = " LIKE '{$_POST['bname']}'";
                }
                if (strlen($_POST['pname']) > 0){
                    $params['B2.last_name'] = " LIKE '{$_POST['pname']}'";
                }
                if (strlen($_POST['min_strikes']) > 0){
                    $params['strikes'] = " >= {$_POST['min_strikes']}";
                }
                if (strlen($_POST['max_strikes']) > 0){
                    //IMPORTANT Don't get rid of the space before 'occupancy'
                    $params[' strikes'] = " <= {$_POST['max_strikes']}";
                }
                if (strlen($_POST['min_balls']) > 0){
                    $params['balls'] = " >= {$_POST['min_balls']}";
                }
                if (strlen($_POST['max_balls']) > 0){
                    //IMPORTANT Don't get rid of the space before 'occupancy'
                    $params[' balls'] = " <= {$_POST['max_balls']}";
                }
                if ($_POST['out_or_not'] > -1){
                    $params['out_or_not'] = " = {$_POST['out_or_not']}";
                }
                if(count($params) > 0){
                    $query .= " AND ";
                    foreach($params as $key => $value){
                        $query .= " $key $value AND";
                    }
                    //Getting rid of last AND
                    $query = substr($query, 0, -3);
                }
                $query .= " ORDER BY ";
                if (isset($_POST['sort'])){                    
                    if(in_array('bname', $_POST['sort'])){
                        $query .= " B1.last_name ASC,";
                    }
                    if(in_array('pname', $_POST['sort'])){
                        $query .= " B2.last_name ASC,";
                    }
                    if(in_array('balls', $_POST['sort'])){
                        $query .= " balls ASC,";
                    }
                    if(in_array('strikes', $_POST['sort'])){
                        $query .= " strikes ASC,";
                    }
                    if(in_array('runs_scored', $_POST['sort'])){
                        $query .= " runs_scored ASC,";
                    }
                }
                $query .= " at_bat_id ASC";
            }
            else{
                $query .= " ORDER BY at_bat_id ASC";
            }
            $query .= " ;";
            $select_all_at_bats_query = mysqli_query($connection,$query);
            if (!$select_all_at_bats_query){
                $error_msg = mysqli_error($connection);
                $_SESSION['failure'] = "Couldn't load query: $query $error_msg";
                exit();
            }
            while($row = mysqli_fetch_assoc($select_all_at_bats_query)) {
            echo "
            <tr>
                <td>".$row['at_bat_id']."</td>
                <td>".$row['game_id']."</td>
                <td>".$row['batter_id']."</td>
                <td>".$row['bname']."</td>
                <td>".$row['pitcher_id']."</td>
                <td>".$row['pname']."</td>
                <td>".$row['strikes']."</td>
                <td>".$row['balls']."</td>
                <td>".$row['runs_scored']."</td>
                <td>".$row['out_or_not']."</td>                
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
</div>
</body>
</html>