<?php
class addtodatabase{

    protected function connect(){
        try{
            $username = "waveyboym";
            $password = '$SM8D8MZDN-a!VW';

            $database = new PDO('mysql:host=db4free.net;dbname=waveyboym_db', $username, $password);
            return $database;
        }
        catch(PDOException $e){
            print "Error!: ". $e->getMessage() . "<br/>";
            die();
        }
    }
}