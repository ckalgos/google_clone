<?php

try{

    $connection = new PDO("mysql:dbname=searchengine;host=localhost","root","");

}
catch(PDOException $ex){
echo "Error occured : " . $ex->getMessage();
}

?>