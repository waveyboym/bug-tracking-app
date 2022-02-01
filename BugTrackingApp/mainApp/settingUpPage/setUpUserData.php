<?php
session_start();
include "../mainApp/databaseClass/accessmaindatabase.php";

class setUpUserData extends accessmaindatabase{

    protected function getDataAndInitilise(){
        $databaseAccess = $this->connect()->prepare('SELECT PROBLEM_ID, problem_description, resolved_not_resolved, date_logged, PROBLEMS_ID FROM problems;');
        $databaseAccess->execute();
        
        $BugCounter = 0;
        $NumOfResolved = 0;
        $objectArray = array();
        if($databaseAccess->rowCount() > 0){
            $result = $databaseAccess->fetchAll();
            foreach($result as $valuesToOutput){
                if($valuesToOutput['PROBLEMS_ID'] == $_SESSION["LoggedidNum"]){
                    $BugObject = new stdClass();

                    $BugObject->BugId = $valuesToOutput['PROBLEM_ID'];
                    $BugObject->Bugdesc = $valuesToOutput['problem_description'];
                    $BugObject->BugStatus = $valuesToOutput['resolved_not_resolved'];
                    $BugObject->BugDateLogged = $valuesToOutput['date_logged'];
                    
                    if($BugObject->BugStatus == "resolved"){
                        ++$NumOfResolved;
                    }
                    array_push($objectArray, $BugObject);
                    ++$BugCounter;
                }
             }
        }

        if($BugCounter == 0){
            $_SESSION["NumOfResolvedBugs"] = 0;
        }
        else if($BugCounter != 0){
            $_SESSION["NumOfResolvedBugs"] = (($NumOfResolved / $BugCounter) * 100);
        }
        
        $_SESSION["BugCount"] = $BugCounter;
        $_SESSION["BugReport"] = json_encode($objectArray);
    }
}