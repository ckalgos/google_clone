<?php 
include("databaseconfig.php");
class SitesSearch {

    public function getResults($searchTerm){

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

        $query1 = $connection->prepare("SELECT * FROM `sites` WHERE 
        url LIKE :searchTerm OR 
        title LIKE :searchTerm OR 
        keywords LIKE :searchTerm OR 
        description LIKE :searchTerm
        ORDER BY timesvisited DESC
        ");        

        $query1->bindParam("searchTerm",$searchTerm);
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

        return $results;
    }
}
?>