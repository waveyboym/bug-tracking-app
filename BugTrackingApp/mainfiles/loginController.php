<?php

class loginController extends login{
    private $newUID;
    private $newPWD;

    public function __construct($Userid, $Userpwd){
        $this->newUID = $Userid;
        $this->newPWD = $Userpwd;
    }

    public function loginUser(){
        if($this->emptyInput() == false){
            header("location: ../index.php?error=emptyinput");
            exit();
        }

        $this->getUser($this->newUID, $this->newPWD);
    }

    private function emptyInput(){
        if(empty($this->newUID) || empty($this->newPWD)){
            return false;
        }
        else{
            return true;
        }
    }
}