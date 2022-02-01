<?php

class login extends maindatabase{

    protected function getUser($Userid, $Userpwd){
        $databaseAccess = $this->connect()->prepare('SELECT USERS_PWD FROM bugtrackusers WHERE USERS_UID = ? OR USERS_EMAIL = ?;');

        if(!($databaseAccess->execute(array($Userid, $Userpwd)))){
            $databaseAccess = null;
            header("location: ../index.php?error=databaseAccessfailed");
            exit();
        }

        if($databaseAccess->rowCount() == 0){
            $databaseAccess = null;
            header("location: ../index.php?error=usernotfound");
            exit();
        }

            
        $pwdHashed = $databaseAccess->fetchAll(PDO::FETCH_ASSOC);
        $checkPwd = false;
        foreach($pwdHashed as $passWordsToCrossCheck){
            if(password_verify($Userpwd, $passWordsToCrossCheck['USERS_PWD']) == true){
                $checkPwd = true;
                break;
            }
        }

        if($checkPwd != true){
            $databaseAccess = null;
            header("location: ../index.php?error=wrongpassword");
            exit();
        }
        else if($checkPwd == true){
            $databaseAccess = $this->connect()->prepare('SELECT USER_ID, USERS_UID FROM bugtrackusers;');
            $databaseAccess->execute();
        
            if($databaseAccess->rowCount() > 0){
                $result = $databaseAccess->fetchAll();
                foreach($result as $valuesToOutput){
                    if($valuesToOutput['USERS_UID'] == $Userid){
                        session_start();
                        $_SESSION["LoggedUserid"] = $Userid;
                        $_SESSION["LoggedidNum"] = $valuesToOutput['USER_ID'];
                        return;
                    }
                }
            }
            else{
                header("location: ../index.php?error=idCannotBeFound");
                exit();
            }

            $databaseAccess = null;
        }
    }
}