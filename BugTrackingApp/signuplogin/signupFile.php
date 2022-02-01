<?php

if(isset($_POST["submit"]) == true){

    //getting user data from login form
    session_start();
    $_SESSION["LoggedUserid"] = $_POST["Userid"];
    $Userid = $_POST["Userid"];
    $Userpwd = $_POST["Userpwd"];
    $Userpwdrepeat = $_POST["Userpwdrepeat"];
    $Useremail = $_POST["Useremail"];

    //instantiate signupcontr class
    include "../mainfiles/maindatabase.php";
    include "../mainfiles/signup.php";
    include "../mainfiles/signupController.php";

    $newSignUp = new signupcontroller($Userid, $Userpwd, $Userpwdrepeat, $Useremail);

    //running error handlers and user signup
    $newSignUp->signUpNewUser();
    $newIdValue = $newSignUp->getUserID();
    $_SESSION["LoggedidNum"] = $newIdValue;
    //going to the home page
    header("location: ../mainApp/setUp.php?error=successlogin");
}