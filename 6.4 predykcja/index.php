<!DOCTYPE html>
<html>
    <head>
        <title>6.4 predykcja</title>
        <link href="bower_components/c3/c3.css" rel="stylesheet" type="text/css">
        <link href="bower_components/bootstrap/dist/css/bootstrap.css" rel="stylesheet" type="text/css">
        <script type="text/javascript" src="bower_components/jquery/dist/jquery.min.js"></script>
        <script type="text/javascript" src="bower_components/vis/dist/vis.js"></script>
        <script type="text/javascript" src="bower_components/d3/d3.js"></script>
        <script type="text/javascript" src="bower_components/c3/c3.js"></script>
        <script type="text/javascript" src="bower_components/synaptic/dist/synaptic.js"></script>
        <script type="text/javascript" src="bower_components/underscore/underscore-min.js"></script>
        <script type="text/javascript" src="bower_components/sketch.js-2/lib/sketch.js"></script>
        <link href="css/main.css" rel="stylesheet">
        <script type="text/javascript" src="js/functions.js"></script>
        <script type="text/javascript" src="js/main.js"></script>
    </head>
    <body>
        <div class="container">
            <div class="col-xs-12">

                <div class="graphs">
                    <div class="sketch-wrapper" width="200" height="100"></div>
                    <canvas class="predict"></canvas>
                </div>

                <div class="buttons">
                    <button class="btn btn-primary" data-action="run">Licz</button>
                    <button class="btn btn-default" data-action="clear">Czyść</button>
                </div>

            </div>
        </div>

    </body>
</html>