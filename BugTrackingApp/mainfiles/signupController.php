<?php

class signupcontroller extends signup{
    private $newUID;
    private $newPWD;
    private $newPWDREPEAT;
    private $newEMAIL;

    public function __construct($Userid, $Userpwd, $Userpwdrepeat, $Useremail){
        $this->newUID = $Userid;
        $this->newPWD = $Userpwd;
        $this->newPWDREPEAT = $Userpwdrepeat;
        $this->newEMAIL = $Useremail;
    }

    public function signUpNewUser(){
        if($this->formFilledCheck() == false){
            header("location: ../index.php?error=emptyinput");
            exit();
        }
        if($this->invalidUid() == false){
            header("location: ../index.php?error=username");
            exit();
        }
        if($this->invalidEmail() == false){
            header("location: ../index.php?error=email");
            exit();
        }
        if($this->pwdMatch() == false){
            header("location: ../index.php?error=password");
            exit();
        }
        if($this->alreadyExists() == false){
            header("location: ../index.php?error=alreadyexists");
            exit();
        }

        $this->createNewUser($this->newUID, $this->newPWD, $this->newEMAIL);
    }

    public function getUserID(){
        $id_value = $this->retrieveUserID($this->newUID, $this->newEMAIL);
        return $id_value;
    }

    private function formFilledCheck(){
        if(empty($this->newUID) || empty($this->newPWD) || empty($this->newPWDREPEAT) || empty($this->newEMAIL)){
            return false;
        }
        else{
            return true;
        }
    }

    private function invalidUid(){
        if(!preg_match("/^[a-zA-Z0-9]*$/", $this->newUID)){
            return false;
        }
        else{
            return true;
        }
    }

    private function invalidEmail(){
        if(!(filter_var($this->newEMAIL, FILTER_VALIDATE_EMAIL))){
            return false;
        }
        else{
            return true;
        }
    }

    private function pwdMatch(){
        if($this->newPWD != $this->newPWDREPEAT){
            return false;
        }
        else{
            return true;
        }
    }

    private function alreadyExists(){
        if(!($this->checkifUserExists($this->newUID, $this->newEMAIL))){
            return false;
        }
        else{
            return true;
        }
    }
}