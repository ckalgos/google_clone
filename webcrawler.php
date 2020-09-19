<?php

include("DOMParser.php");
include("databaseconfig.php");

$crawled_links = array();
$crawled_images = array();

function insertDetailToDB($url,$title,$description,$keywords){

    global $connection;

    $query = $connection->prepare("INSERT INTO SITES(url,title,description,keywords)
                                 VALUES(:url,:title,:description,:keywords)");


   $query->bindParam(":url",$url);  
   $query->bindParam(":title",$title);   
   $query->bindParam(":description",$description);   
   $query->bindParam(":keywords",$keywords);                      

   return $query->execute();
}

function insertImageToDB($siteurl,$imageurl,$alt,$title){

    global $connection;

    $query = $connection->prepare("INSERT INTO IMAGES(siteurl,imageurl,alt,title)
                                 VALUES(:siteurl,:imageurl,:alt,:title)");


   $query->bindParam(":siteurl",$siteurl);  
   $query->bindParam(":imageurl",$imageurl);   
   $query->bindParam(":alt",$alt);   
   $query->bindParam(":title",$title);                      

   return $query->execute();
}

function formLinks($href,$url){
    $scheme = parse_url($url)['scheme'];
    $host = parse_url($url)['host'];

    if(substr($href,0,2)== "//"){
        $href = $scheme.":".$href;
    }else if(substr($href,0,1)== "/"){
        $href = $scheme."://".$host.$href;
    }

    return $href;
}

function extractDetails($url){

    global $crawled_images;
    
    $parser = new DOMParser($url);

    $titleTags = $parser -> getTitleTags();

    if(sizeof($titleTags) == 0 || $titleTags->item(0) == NULL){
        return;
    }

    $title = $titleTags->item(0)->nodeValue; 
    $title =str_replace("\n","", $title);

    if( $title == ""){
        return;
    }

    $description = "";
    $keywords = "";

    $metaTags = $parser -> getMetaTags();

    foreach($metaTags as $metaTag){
        if($metaTag->getAttribute("name") == "description"){
            $description = $metaTag->getAttribute("content"); 
        } else if($metaTag->getAttribute("name") == "keywords"){
            $keywords = $metaTag->getAttribute("content");
        }
    }

    insertDetailToDB($url,$title,$description,$keywords);

    $imageTags = $parser->getImageTags();

    foreach($imageTags as $imageTag){

        $src= $imageTag->getAttribute("src");
        $alt= $imageTag->getAttribute("alt");
        $title= $imageTag->getAttribute("title");

        if(!$alt && !$title){
            continue;
        }

        $src=formLinks($src,$url);

       if(!in_array($src,$crawled_images)){
        $crawled_images[] = $src;
        insertImageToDB($url,$src,$alt,$title);
       }
    }

}

function crawlLinks($url){

    global $crawled_links;

    $parser = new DOMParser($url);

    $links = $parser->getLinks();

    foreach($links as $link){

        $href = $link->getAttribute("href");

        if(strpos( $href,"#") !== false || substr($href,0,11) == "javascript:"){
            continue;
        }

        $href = formLinks($href,$url);
        
        if(!in_array($href,$crawled_links))
        {
            $crawled_links[]=$href;
            echo "$href <br>";
            extractDetails($href);
            crawlLinks($href);
        }
    }
}

crawlLinks("https://www.microsoft.com/en-in/");
?>