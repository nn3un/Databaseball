<?php
session_start();
$db['db_host'] = "mysql.cs.virginia.edu";
$db['db_user'] = "nn3un";
$db['db_pass'] = "Moose2030";
$db['db_name'] = "nn3un_Databaseball_2";

foreach($db as $key => $value){
    define(strtoupper($key), $value);
}
$Err = "";
$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS,DB_NAME);
if($connection){
    if (isset($_POST['login'])){
        $query_res = mysqli_query($connection, 'SELECT password FROM adminInfo');
        if(!$query_res){
            $_SESSION['failure'] = "Failed to fetch info";
        }
        if(mysqli_num_rows($query_res) > 0){
            if(password_verify($_POST["password"], mysqli_fetch_assoc($query_res)['password'])){
                echo "success";
                $_SESSION['admin'] = 'admin';
                header('Location: index.php');
                exit();
            }
            else{
                $Err = 'Wrong Password';
            }
        }
    }
}
else{
    $_SESSION['failure'] = "Database connection failed";
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
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
<style type="text/css">
    label{
        font-weight: bold;
    }
</style>
<body>
    <div class="container w-25">
        <div id='login-box' class="mx-auto pt-5">
            <h1 class="text-center">Login</h1>
            <form action = "login.php", method = "POST">
                <!-- password field -->
                <div class="form-group">
                    <label for="password">Password: </label><input class="form-control" type = "password" name = "password" required>
                    <span class="error" style="color: red"><?php echo $Err;?></span>
                </div>
                </div>
                
                <!-- Login button -->
                <div class="form-group">
                    <button class="btn btn-lg btn-block btn-secondary" name="login">Login</button>
            </form>
        </div>
    </div>
</body>
</html>