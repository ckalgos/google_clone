<?php

if(isset($_GET["searchTerm"]))
{
    $term = $_GET["searchTerm"];
}

$type = isset($_GET["searchType"]) ? $_GET["searchType"] : 'sites';
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
        </div>
    </div>
</body>

</html>