<?php

include("SitesSearch.php");

if(isset($_GET["searchTerm"]))
{
    $term = $_GET["searchTerm"];
}

$type = isset($_GET["searchType"]) ? $_GET["searchType"] : 'sites';
$page = isset($_GET["page"]) ? $_GET["page"] : 1;
?>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="assets/styles/style.css" />
    <title>Results</title>
</head>

<body>
    <div class="wrapper">
        <div class="headerSection">
            <div class="headercontent">
                <div class="image_container">
                    <a href="index.php">
                        <img src="assets/images/google_logo.png" />
                    </a>
                </div>
                <div class="textbox_container">
                    <form action='results.php' method='GET'>
                        <div class="search_box">
                            <input value="<?php echo $type; ?>" type="hidden" name="searchType" />
                            <input value="<?php echo $term; ?>" type="text" class="search_textbox" name="searchTerm" />
                            <button class="search_textbtn">
                                <img src="assets/images/searchicon.png" />
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="headerlist">
                <ul class="tablist">
                    <li class="<?php echo $type == 'sites' ? 'active' : ''?>">
                        <a href="<?php echo "results.php?searchTerm=$term&searchType=sites"?>">Sites</a>
                    </li>
                    <li class="<?php echo $type == 'images' ? 'active' : ''?>">
                        <a href="<?php echo "results.php?searchTerm=$term&searchType=images"?>">Images</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="resultsSection">
        <?php 
        $resultInstance = new SitesSearch();
        $results = $resultInstance->getResults($page,10,$term);

        echo $results[1];
        ?>
        </div>
        <div class="pageSection">
            <div class="pageButtons">
                <div class="pageImageContainer">
                    <img src="assets/images/page_start.png">
                </div>
                <?php                 
                $maxPages = 10;
                $pagesRequired = ceil($results[0]/10);
                $pagesToShow = min($pagesRequired,$maxPages);
                $currentPage = $page - ($maxPages/2);
                if($currentPage <=0){
                    $currentPage = 1;
                }
                if($currentPage + $pagesToShow >= $pagesRequired){
                    $currentPage =  $pagesRequired - $pagesToShow + 1;
                }
                while($pagesToShow !=0){
                    
                    if($currentPage == $page){
                        echo "<div class='pageImageContainer'>
                                <img src='assets/images/page_selected.png'>
                                <span class='pageNumber'>$currentPage</span>
                            </div>";
                    }
                    else{
                    echo "<div class='pageImageContainer'>
                            <a href='results.php?searchType=$type&searchTerm=$term&page=$currentPage'>
                                <img src='assets/images/page_other.png'>
                                <span class='pageNumber'>$currentPage</span>
                            </a>
                        </div>";
                    }

                    $pagesToShow--;
                    $currentPage++;
                }
                ?>
                <div class="pageImageContainer">
                    <img src="assets/images/page_end.png">
                </div>
            </div>
        </div>
    </div>
</body>

</html>