<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Dongle:wght@700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/d271141ba3.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
    <title>Bug Tracking App</title>
</head>
<body>
    <div class="the-squares">
        <div class="square" style="--i:0;"></div>
        <div class="square" style="--i:1;"></div>
        <div class="square" style="--i:2;"></div>
        <div class="square" style="--i:3;"></div>
        <div class="square" style="--i:4;"></div>
    </div>
    <div class="main-content-login">
        <div class="Apptitle">
            <h2>Bug Tracking App</h2>
            <i class="fas fa-bug"></i>
        </div>
        <div class="wrapper-signup">
            <h1>Sign up</h1>
            <p>Don't have an account yet? Sign up here!</p>
            <form action="signuplogin/signupFile.php" method="post">
                <input type="text" name="Userid" placeholder="Username">
                <br>
                <input type="password" name="Userpwd" placeholder="Password">
                <br>
                <input type="password" name="Userpwdrepeat" placeholder="Repeat Password">
                <br>
                <input type="text" name="Useremail" placeholder="E-mail">
                <br>
                <button type="submit" name="submit">Sign Up</button>
            </form>
        </div>
        <div class="bar">
            <div class="bar-line">
            </div>
        </div>
        <div class="wrapper-login">
            <h1>Login</h1>
            <p>Have an account? Login here!</p>
            <form action="signuplogin/loginFile.php" method="post">
                <input type="text" name="Userid" placeholder="Username">
                <br>
                <input type="password" name="Userpwd" placeholder="Password">
                <br>
                <button type="submit" name="submit">Login</button>
            </form>
        </div>
    </div>
</body>
</html>