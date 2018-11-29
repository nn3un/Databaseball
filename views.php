<?php

session_start();
include 'db.php';


$connection = mysqli_connect(DB_HOST, DB_USER,DB_PASS,DB_NAME);
if(!$connection){
    exit();
}
?>
<?php include 'header.php';?>
        <script>
            function showQuery(card_id){
                var x = document.getElementById(card_id);
                x.classList.toggle("show");
            }
        </script>
        <div class="accordion mt-5" id="accordionExample">
            <div class="card">
                <div class="card-header" id="headingOne">
                    <h5 class="mb-0">
                        <button class="btn btn-link" onclick="showQuery('collapseOne')" id="link_1" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Wins and Losses
                        </button>
                    </h5>
                </div>
            <div id="collapseOne" class="collapse <?php if (isset($_POST['refresh_1'])) {echo show;}?>" aria-labelledby="headingOne" data-parent="#accordionExample">
                <div class="card-body">
                    <form class="m-1 p-2" method="POST" action="views.php">
                        <div class="form-group">
                            <label for="team_name"> Search By Team Name: </label>
                            <input type = "text" class="form-control"  name = "team_name" placeholder = "%Sox"><br>
                        </div>
                        <div class=form-group>
                            <label for="sort[]" class='pb-2'>Sort Results By: </label><br>
                            <input type="radio" name='sort' class="ml-0 mr-2 d-inline" value="home_wins">Home Wins
                            <input type="radio" name='sort' class="ml-4 mr-2 d-inline" value="away_wins">Away Wins
                            <input type="radio" name='sort' class="ml-4 mr-2 d-inline" value="total_wins">Total Wins
                            <input type="radio" name='sort' class="ml-4 mr-2 d-inline" value="home_losses">Home Losses
                            <input type="radio" name='sort' class="ml-4 mr-2 d-inline" value="away_losses">Away Losses
                            <input type="radio" name='sort' class="ml-4 mr-2 d-inline" value="total_losses">Total Losses
                        </div>
                        <button class="btn btn-secondary w-100" name ="refresh_1">Refresh</button>
                    </form>
                    <table class=" table-bordered table table-striped">
                        <thead>
                            <tr> 
                                <th>Team Name</th> 
                                <th>Home Wins</th>
                                <th>Away Wins</th>
                                <th>Total Wins</th>
                                <th>Home Losses</th>
                                <th>Away Losses</th>
                                <th>Total Losses</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $query = "SELECT Team.team_name, home_team_wins.home_team_id, home_wins, away_wins, home_wins + away_wins AS total_wins, home_losses, away_losses, home_losses + away_losses AS total_losses FROM 
                                    (SELECT home_team_id, count(*) AS home_wins FROM Game WHERE home_team_runs > away_team_runs GROUP BY (home_team_id) UNION 
                                    (SELECT team_id, 0 AS home_wins FROM Team WHERE team_id NOT IN
                                    (SELECT home_team_id FROM Game WHERE home_team_runs > away_team_runs GROUP BY (home_team_id))
                                    ) ORDER BY home_team_id)
                                        AS home_team_wins 
                                        join 
                                        
                                        (SELECT away_team_id, count(*) AS away_wins FROM Game WHERE home_team_runs < away_team_runs GROUP BY (away_team_id) UNION 
                                    (SELECT team_id, 0 AS away_wins FROM Team WHERE team_id NOT IN
                                    (SELECT away_team_id FROM Game WHERE home_team_runs < away_team_runs GROUP BY (away_team_id))
                                    ) ORDER BY away_team_id)        
                                        AS away_team_wins 
                                        ON home_team_wins.home_team_id = away_team_wins.away_team_id

                                        join
                                        
                                    (SELECT home_team_id, count(*) AS home_losses FROM Game WHERE home_team_runs < away_team_runs GROUP BY (home_team_id) UNION 
                                    (SELECT team_id, 0 AS home_losses FROM Team WHERE team_id NOT IN
                                    (SELECT home_team_id FROM Game WHERE home_team_runs < away_team_runs GROUP BY (home_team_id))
                                    ) ORDER BY home_team_id)
                                        AS home_team_losses 
                                        ON home_team_wins.home_team_id = home_team_losses.home_team_id 
                                        
                                        join
                                        
                                    (SELECT away_team_id, count(*) AS away_losses FROM Game WHERE home_team_runs > away_team_runs GROUP BY (away_team_id) UNION 
                                    (SELECT team_id, 0 AS away_losses FROM Team WHERE team_id NOT IN
                                    (SELECT away_team_id FROM Game WHERE home_team_runs > away_team_runs GROUP BY (away_team_id))
                                    ) ORDER BY away_team_id)        
                                        AS away_team_losses 
                                        ON home_team_wins.home_team_id = away_team_losses.away_team_id
                                        
                                        join
                                        
                                        Team on home_team_wins.home_team_id = Team.team_id";
                                if (isset($_POST['refresh_1'])){
                                    if (strlen($_POST['team_name']) > 0){
                                        $query .= " WHERE team_name LIKE '{$_POST['team_name']}' ";
                                    }
                                    if(isset($_POST['sort'])){
                                        $query .= " ORDER BY {$_POST['sort']} ASC ";
                                    }
                                }
                                $query .= ";";
                                $selection_query = mysqli_query($connection,$query);
                                if (!$selection_query){
                                    $error_msg = mysqli_error($connection);
                                    $_SESSION['failure'] = "Couldn't load query: $query $error_msg";
                                    exit();
                                }
                                while($row = mysqli_fetch_assoc($selection_query)) {
                                    echo "
                                    <tr>
                                        <td>".$row['team_name']."</td>
                                        <td>".$row['home_wins']."</td>
                                        <td>".$row['away_wins']."</td>
                                        <td>".$row['total_wins']."</td>
                                        <td>".$row['home_losses']."</td>
                                        <td>".$row['away_losses']."</td>
                                        <td>".$row['total_losses']."</td>
                                    </tr>
                                    ";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
          </div>
          <div class="card">
            <div class="card-header" id="headingTwo">
                <h5 class="mb-0">
                    <button class="btn btn-link collapsed" type="button" onclick='showQuery("collapseTwo")' data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                      Team Salary Info
                    </button>
                </h5>
            </div>
            <div id="collapseTwo" class="collapse <?php if (isset($_POST['refresh_2'])) {echo show;}?>" aria-labelledby="headingTwo" data-parent="#accordionExample">
              <div class="card-body">
                <form class="m-1 p-2" method="POST" action="views.php">
                        <div class="form-group">
                            <label for="team_name"> Search By Team Name: </label>
                            <input type = "text" class="form-control"  name = "team_name" placeholder = "%Sox"><br>
                        </div>
                        <div class=form-group>
                            <label for="sort" class='pb-2'>Sort Results By: </label><br>
                            <input type="radio" name='sort' class="ml-0 mr-2 d-inline" value="sum_salary">Sum Salary
                            <input type="radio" name='sort' class="ml-4 mr-2 d-inline" value="avg_salary">Average Salary
                            <input type="radio" name='sort' class="ml-4 mr-2 d-inline" value="min_salary">Minimum Salary
                            <input type="radio" name='sort' class="ml-4 mr-2 d-inline" value="max_salary">Maximum Salary
                        </div>
                        <button class="btn btn-secondary w-100" name ="refresh_2">Refresh</button>
                    </form>
                    <table class=" table-bordered table table-striped">
                        <thead>
                            <tr> 
                                <th>Team Name</th> 
                                <th>Sum Salary</th>
                                <th>Avergage Salary</th>
                                <th>Maximum Salary</th>
                                <th>Minimum Salary</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $query = "SELECT team_name, sum(salary) as sum_salary, avg(salary) as avg_salary, max(salary) as max_salary, min(salary) as min_salary from Team natural join Batter group by team_name ";
                                if (isset($_POST['refresh_2'])){
                                    if (strlen($_POST['team_name']) > 0){
                                        $query = " SELECT team_name, sum(salary) as sum_salary, avg(salary) as avg_salary, max(salary) as max_salary, min(salary) as min_salary from Team natural join Batter  WHERE team_name LIKE '{$_POST['team_name']}'  group by team_name";
                                    }
                                    if(isset($_POST['sort'])){
                                        $query .= " ORDER BY {$_POST['sort']} ASC ";
                                    }
                                }
                                $query .= ";";
                                $selection_query = mysqli_query($connection,$query);
                                if (!$selection_query){
                                    $error_msg = mysqli_error($connection);
                                    $_SESSION['failure'] = "Couldn't load query: $query $error_msg";
                                    exit();
                                }
                                while($row = mysqli_fetch_assoc($selection_query)) {
                                    echo "
                                    <tr>
                                        <td>".$row['team_name']."</td>
                                        <td>".$row['sum_salary']."</td>
                                        <td>".$row['avg_salary']."</td>
                                        <td>".$row['max_salary']."</td>
                                        <td>".$row['min_salary']."</td>
                                    </tr>
                                    ";
                                }
                            ?>
                        </tbody>
                    </table>
              </div>
            </div>
          </div>

    </div>
</body>
</html>