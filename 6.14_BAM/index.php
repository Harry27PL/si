<?php session_start(); ?>

<!DOCTYPE html>
<html>
    <head>
        <title>SI BAM</title>
        <link href="css/main.css" rel="stylesheet">
        <script type="text/javascript" src="//code.jquery.com/jquery-1.11.3.min.js"></script>
        <script type="text/javascript" src="js/main.js"></script>
    </head>
    <body>

<?php

include 'vendor/autoload.php';
include 'src/functions.php';

set_time_limit(60);

new Main();

?>

    </body>
</html>