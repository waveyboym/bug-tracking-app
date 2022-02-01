<?php
session_start();
include "../mainApp/databaseClass/addtodatabase.php";

class addNewBugs extends addtodatabase{

    protected function addData($bugDesc, $bugStatus, $dateLogged , $userId){
        $databaseAccess = $this->connect()->prepare('INSERT INTO problems (problem_description, resolved_not_resolved, date_logged, PROBLEMS_ID) VALUES (?, ?, ?, ?);');
        $outcome = $databaseAccess->execute(array($bugDesc, $bugStatus, $dateLogged , $userId));
        
        if($outcome == true){
            $databaseAccess = $this->connect()->prepare('SELECT PROBLEM_ID, problem_description, date_logged FROM problems;');
            $databaseAccess->execute();
            if($databaseAccess->rowCount() > 0){
                $result = $databaseAccess->fetchAll();
                foreach($result as $valuesToOutput){
                    if(($valuesToOutput['problem_description'] == $bugDesc) && ($valuesToOutput['date_logged'] == $dateLogged)){
                        $BugObject = new stdClass();
                        $BugObject->BugId = $valuesToOutput['PROBLEM_ID'];
                        $BugObject->Bugdesc = $bugDesc;
                        $BugObject->BugStatus = $bugStatus;
                        $BugObject->BugDateLogged = $dateLogged;
                        $bugReportArray =  json_decode($_SESSION["BugReport"]);
                        array_push($bugReportArray, $BugObject);

                        $resolvedCount = 0;
                        $bugCount = count($bugReportArray);

                        for($i = 0; $i < count($bugReportArray); ++$i){
                            if($bugReportArray[$i]->BugStatus == "resolved"){
                                ++$resolvedCount;
                            }
                        }

                        $percentageOfResolved = ($resolvedCount/ $bugCount)*100;

                        $_SESSION["NumOfResolvedBugs"] = $percentageOfResolved;
                        $_SESSION["BugCount"] = $bugCount;
                        $_SESSION["BugReport"] = json_encode($bugReportArray);
                        break;
                    }
                }
            }
        }
        else if($outcome != true){
            $databaseAccess = null;
            header("location: ../mainApp/home.php?error=couldnotaddnewbug");
            exit();
        }      
    }
}