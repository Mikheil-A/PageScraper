<?php

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $websiteURL = $_POST['websiteurl'];

    if (filter_var($websiteURL, FILTER_VALIDATE_URL) === false) {
        header('Location: index.php');
        die();
    }

//$websiteURL = 'https://nbawebsite.000webhostapp.com/'; //URL
//$websiteURL = 'http://www.gettyimagesgallery.com'; //URI
//$websiteURL = 'http://www.gettyimagesgallery.com/collections/default.aspx'; //URI
//$websiteURL = 'https://1stwebdesigner.com/jquery-gallery/'; //URL
//$websiteURL = 'https://www.w3schools.com/'; //URI
//$websiteURL = 'http://artimagesgallery.com.au/'; //URI
//$websiteURL = 'https://www.awwwards.com/awwwards/collections/image-gallery-and-slideshows'; //URI
//$websiteURL = 'http://www.scotland.org/image-galleries'; //URL

//$websitePortHost = URL - URI
    $websitePortHost = parse_url($websiteURL, PHP_URL_SCHEME) . '://' . parse_url($websiteURL, PHP_URL_HOST);


//Check whether the save directory exists, if not create one
    $saveLocation = dirname(__FILE__) . "/extractedInfo";
    if (!is_dir($saveLocation)) {
        mkdir($saveLocation, 0777, false);
    }

    $html = file_get_contents($websiteURL);


    /*********************Extracting images**************************/

    $regexp = '|<img.*?src=[\'"](.*?)[\'"].*?>|i';
    preg_match_all($regexp, $html, $matches);

    //$matches[0][rows]; //Array of images
    //$matches[1][rows]; //Array of images addresses

    $rows = count($matches[0]);

    set_time_limit(600);

    for($i = 0; $i < $rows; $i++){
        //Parsing URL to URI
        $imageURL1 = trim($websitePortHost . '/' . parse_url($matches[1][$i], PHP_URL_PATH));
        $imageURL2 = str_replace('\\', '/', $imageURL1);
        $imageURL = str_replace(' ', '%20', $imageURL2);

//Saving images to a folder
//Check whether the images directory exists within save location, if not create one
        $saveImagesLocation = $saveLocation . "/images/";
        if (!is_dir($saveImagesLocation)) {
            mkdir($saveImagesLocation, 0777, false);
        }

        $saveImagesDirectory = $saveImagesLocation . ($i + 1) . '. ' . basename($imageURL);
        file_put_contents($saveImagesDirectory, file_get_contents($imageURL));
    }


    /*********************Exracting links***********************/

    $regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";
    preg_match_all("/$regexp/siU", $html, $matches);

    $rows = count($matches[2]);

//$matches[2][rows] = array of link addresses
//$matches[3][rows] = array of link text

    $linksFile = fopen($saveLocation . "/links.txt", "w") or die("Unable to open file!");
    for($i = 0; $i < $rows; $i++){
        $txt = $matches[2][$i] . "\n\n";
        fwrite($linksFile, "$txt");
    }
    fclose($linksFile);


    //Redirecting to success page
    header('Location: views/success.php');
    die();
}
else{ die();}
