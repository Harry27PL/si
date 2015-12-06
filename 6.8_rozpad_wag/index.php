<?php session_start(); ?>

<!DOCTYPE html>
<html>
    <head>
        <title>Rozpad wag</title>
        <link href="css/main.css" rel="stylesheet">
        <link href="bower_components/c3/c3.css" rel="stylesheet">
        <script type="text/javascript" src="//code.jquery.com/jquery-1.11.3.min.js"></script>
        <script type="text/javascript" src="bower_components/vis/dist/vis.js"></script>
        <script type="text/javascript" src="bower_components/d3/d3.min.js"></script>
        <script type="text/javascript" src="bower_components/c3/c3.min.js"></script>
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