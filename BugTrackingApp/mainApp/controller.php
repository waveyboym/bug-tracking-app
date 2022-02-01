<?php

session_start();
if(isset($_POST["desc"]) && isset($_POST["status"]) && isset($_POST["datelogged"])){
    $bugDesc = $_POST["desc"];
    $bugStatus = $_POST["status"];
    $dateLogged = $_POST["datelogged"];
    $userId = $_SESSION["LoggedidNum"];

    include "../mainApp/addingNewBugs/addNewBugsController.php";
    $newBug = new addNewBugsController($bugDesc, $bugStatus, $dateLogged, $userId);
    $newBug->beginCreatingNewBug();
}
else if(isset($_POST["desc"]) && isset($_POST["status"]) && !(isset($_POST["datelogged"]))){
    $bugDesc = $_POST["desc"];
    $bugStatus = $_POST["status"];

    include "../mainApp/changingBugs/modifyBugController.php";
    $newBug = new modifyBugController($bugDesc, $bugStatus);
    $newBug->beginModifyingBug();
    echo "worked";
}