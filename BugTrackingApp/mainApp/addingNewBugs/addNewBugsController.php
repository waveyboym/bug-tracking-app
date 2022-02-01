<?php

include "addNewBugs.php";

class addNewBugsController extends addNewBugs {
    private $newBugDesc;
    private $newbugStatus;
    private $newdateLogged;
    private $newuserId;

    public function __construct($bugDesc, $bugStatus, $dateLogged, $userId){
        $this->newBugDesc = $bugDesc;
        $this->newbugStatus= $bugStatus;
        $this->newdateLogged = $dateLogged;
        $this->newuserId = $userId;
    }

    public function beginCreatingNewBug(){
        $this->addData($this->newBugDesc, $this->newbugStatus, $this->newdateLogged, $this->newuserId);
    }
}