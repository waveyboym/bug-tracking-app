<?php

include "settingUpPage/setUpController.php";

$newSetup = new setUpController();
$newSetup->beginSetUp();

header("location: home.php?error=noerror");