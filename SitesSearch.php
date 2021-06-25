<?php 
include("databaseconfig.php");
class SitesSearch {

    public function getResults($page,$pageSize,$searchTerm){

        global $connection;

        $query = $connection->prepare("SELECT COUNT(1) AS TotalCount FROM `sites` WHERE 
        url LIKE :searchTerm OR 
        title LIKE :searchTerm OR 
        keywords LIKE :searchTerm OR 
        description LIKE :searchTerm");

        $searchTerm = "%".$searchTerm."%";

        $query->bindParam("searchTerm",$searchTerm);
        $query->execute();

        $data = $query->fetch(PDO::FETCH_ASSOC);

        $count = $data["TotalCount"];

        $results = "<p class='searchCount'>About $count results</p>";

        $startingRecord = ($page-1)*$pageSize;

        $query1 = $connection->prepare("SELECT * FROM `sites` WHERE 
        url LIKE :searchTerm OR 
        title LIKE :searchTerm OR 
        keywords LIKE :searchTerm OR 
        description LIKE :searchTerm
        ORDER BY timesvisited DESC
        LIMIT :startingRecord, :pageSize
        ");        

        $query1->bindParam("searchTerm",$searchTerm);
        $query1->bindParam("startingRecord",$startingRecord,PDO::PARAM_INT);
        $query1->bindParam("pageSize",$pageSize,PDO::PARAM_INT);
        $query1->execute();

        $records ="<div class='site-results'>";

        while($row = $query1->fetch(PDO::FETCH_ASSOC)){
            $url = $row["url"];
            $title = $row["title"];
            $description = $row["description"];
            $records .= "<div class='site-result-container'>
            <span class='url'>$url</span>
            <h3 class='title'>
            <a class = 'title-url' href=$url>
            $title
            </a>
            </h3>
            <span class='description'>$description</span>            
            </div>";
        }
        $records .= "</div>";

        $results .=  $records;

        return array( $count,$results);
    }
}
?>