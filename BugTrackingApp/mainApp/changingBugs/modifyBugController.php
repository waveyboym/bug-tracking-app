<?php

include "modifyBug.php";

class modifyBugController extends modifyBug {
    private $newBugDesc;
    private $newbugStatus;

    public function __construct($bugDesc, $bugStatus){
        $this->newBugDesc = $bugDesc;
        $this->newbugStatus= $bugStatus;
    }

    public function beginModifyingBug(){
        $this->modifyBug($this->newBugDesc, $this->newbugStatus);
    }
}