<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Scraper</title>
    <link rel="stylesheet" type="text/css" href="style/index.css">
</head>
<body>

<h1>Welcome to my page scraper website</h1>

<hr>

<form method="post" action="source.php">
    <label>Paste a website URL:</label><br>
    <input type="text" name="websiteurl">
    <input type="submit" value="Scrape data">
</form>

<?php include 'views/footer.php';?>

</body>
</html>
