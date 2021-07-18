<?php 
include("databaseconfig.php");
class ImagesSearch {

    public function getResults($page,$pageSize,$searchTerm){

        global $connection;

        $query = $connection->prepare("SELECT COUNT(1) AS TotalCount FROM `images` WHERE 
        imageurl LIKE :searchTerm OR 
        title LIKE :searchTerm OR 
        alt LIKE :searchTerm");

        $searchTerm = "%".$searchTerm."%";

        $query->bindParam("searchTerm",$searchTerm);
        $query->execute();

        $data = $query->fetch(PDO::FETCH_ASSOC);

        $count = $data["TotalCount"];

        $results = "<p class='searchCount'>About $count results</p>";

        $startingRecord = ($page-1)*$pageSize;

        $query1 = $connection->prepare("SELECT * FROM `images` WHERE 
        imageurl LIKE :searchTerm OR 
        title LIKE :searchTerm OR 
        alt LIKE :searchTerm
        ORDER BY timesvisited DESC
        LIMIT :startingRecord, :pageSize
        ");        

        $query1->bindParam("searchTerm",$searchTerm);
        $query1->bindParam("startingRecord",$startingRecord,PDO::PARAM_INT);
        $query1->bindParam("pageSize",$pageSize,PDO::PARAM_INT);
        $query1->execute();

        $records ="<div class='images-results'>";

        while($row = $query1->fetch(PDO::FETCH_ASSOC)){
            $imageurl = $row["imageurl"];
            $title = $row["title"];
            $id = $row["Id"];
            if(!$title){
                $title = $row["alt"];
            }
            $records .= "<div class='image-result-container'>
            <a href='$imageurl' data-fancybox='image' data-caption='$title' data-id=$id>
            <img src='$imageurl'>
            </a>          
            </div>";
        }
        $records .= "</div>";

        $results .=  $records;

        return array( $count,$results);
    }
}
?>