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
    <nav class="navbar navbar-expand-lg navbar-light bg-info">
        <div class="container-fluid">
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item"><a class="nav-link text-white" href="index.php">Databaseball</a></li>
                </ul>
                <a class="nav-link text-white" href="teams.php">Teams</a>
                <a class="nav-link text-white" href="stadiums.php">Stadiums</a>
                <a class="nav-link text-white" href="games.php">Games</a>
                <a class="nav-link text-white" href="coaches.php">Coaches</a><br> 
                <a class="nav-link text-white" href="atbats.php">At Bats</a><br> 
                <a class="nav-link text-white" href="views.php">Saved Queries</a>
            </div>
        </div>
    </nav>

    

    <div class="container">
        <!--Error and success messages -->
        <?php 
            if(isset($_SESSION['success'])) {
                echo "<div class='alert alert-success text-center mx-5 my-2 px-5'>";
                echo $_SESSION['success']; 
                echo "</div>";
                unset($_SESSION['success']);
            } 
            if(isset($_SESSION['failure'])) {
                echo "<div class='alert alert-danger text-center mx-5 my-2 px-5'>";
                echo $_SESSION['failure']; 
                echo "</div>";
                unset($_SESSION['failure']);
            } 
        ?>