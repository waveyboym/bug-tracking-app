<?php
session_start();
include "../mainApp/databaseClass/modifydatabase.php";

class modifyBug extends modifydatabase{

    protected function modifyBug($newbugDesc, $newbugStatus){
        $bugReportArray =  json_decode($_SESSION["BugReport"]);
        $numOfArrayElements = count($bugReportArray);
        $USERiD = $_SESSION["LoggedidNum"];
        for($i = 0; $i < $numOfArrayElements; ++$i){
            if(($bugReportArray[$i]->Bugdesc == $newbugDesc) && ($bugReportArray[$i]->BugStatus == $newbugStatus)){
                $ID_Of_bug = $bugReportArray[$i]->BugId;

                if($newbugStatus == "unresolved"){
                    //update status to resolved and modify array
                    $setBugStatus = "resolved";
                    $databaseAccess = $this->connect()->prepare('UPDATE problems SET resolved_not_resolved = ? WHERE PROBLEM_ID = ? AND problem_description = ? AND PROBLEMS_ID = ?;');
                    $outcome = $databaseAccess->execute(array($setBugStatus, $ID_Of_bug, $newbugDesc , $USERiD));
        
                    if($outcome == true){
                        $bugReportArray[$i]->BugStatus = "resolved";
                        break;
                    }
                    else if($outcome != true){
                        $databaseAccess = null;
                        header("location: ../mainApp/home.php?error=couldnotmodifybug");
                        exit();
                    }
                }
                else if($newbugStatus == "resolved"){
                    //delete from database and modify array
                    $databaseAccess = $this->connect()->prepare('DELETE FROM problems WHERE PROBLEM_ID = ? AND problem_description = ? AND resolved_not_resolved = ? AND PROBLEMS_ID = ?;');
                    $outcome = $databaseAccess->execute(array($ID_Of_bug, $newbugDesc , $newbugStatus, $USERiD));

                    if($outcome == true){
                        unset($bugReportArray[$i]);
                        break;
                    }
                    else if($outcome != true){
                        $databaseAccess = null;
                        header("location: ../mainApp/home.php?error=couldnotdeletebug");
                        exit();
                    }
                }
            }
        }
        
        $bugCount = count($bugReportArray);
        $resolvedCount = 0;
        for($i = 0; $i < count($bugReportArray); ++$i){
            if($bugReportArray[$i]->BugStatus == "resolved"){
                ++$resolvedCount;
            }
        }

        if($bugCount == 0){
            $percentageOfResolved = 0;
        }
        else if($bugCount != 0){
            $percentageOfResolved = ($resolvedCount/ $bugCount)*100;
        }

        $_SESSION["NumOfResolvedBugs"] = $percentageOfResolved;
        $_SESSION["BugCount"] = $bugCount;
        $_SESSION["BugReport"] = json_encode($bugReportArray);
    }
}