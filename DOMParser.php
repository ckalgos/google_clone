<?php
class DOMParser{

    private $document;

    public function __construct($url){

    $request_options = array(
        "http" => array(
            "method" => "GET",
            "header" => "User-Agent: webcrawlerBot/0.1\n"
        )
        );

    $context = stream_context_create($request_options);

     $this->document = new DomDocument();

     @$this->document->loadHTML(file_get_contents($url,false,$context));
    }

    public function getLinks(){
        return $this->document->getElementsByTagName("a");
    }

    public function getTitleTags(){
        return $this->document->getElementsByTagName("title");
    }

    public function getMetaTags(){
        return $this->document->getElementsByTagName("meta");
    }

    public function getImageTags(){
        return $this->document->getElementsByTagName("img");
    }
}

?>