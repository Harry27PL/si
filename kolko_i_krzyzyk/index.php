<?php session_start(); ?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Kółko i krzyżyk</title>
        <link href="bower_components/bootstrap/dist/css/bootstrap.css" rel="stylesheet">
        <link href="bower_components/c3/c3.css" rel="stylesheet">
        <link href="css/main.css" rel="stylesheet">
        <script type="text/javascript" src="//code.jquery.com/jquery-1.11.3.min.js"></script>
        <script type="text/javascript" src="bower_components/jquery/dist/jquery.min.js"></script>
        <script type="text/javascript" src="bower_components/underscore/underscore.js"></script>
        <script type="text/javascript" src="bower_components/d3/d3.min.js"></script>
        <script type="text/javascript" src="bower_components/c3/c3.min.js"></script>
        <script type="text/javascript" src="bower_components/synaptic/dist/synaptic.js"></script>
        <script type="text/javascript" src="js/functions.js"></script>
        <!--<script type="text/javascript" src="js/fann.js"></script>-->
        <script type="text/javascript" src="js/game.js"></script>
        <script type="text/javascript" src="js/main.js"></script>
    </head>
    <body>

        <div class="game-head">

            <div class="game-head-stats">
                <div class="stats-row" data-stats-games>
                    <div class="stats-label">Gier</div>
                    <div class="stats-value">0</div>
                </div>

                <div class="stats-winners">
                    <div class="stats-row" data-stats-winners="-1">
                        <div class="stats-label">O</div>
                        <div class="stats-value">0%</div>
                    </div>
                    <div class="stats-row" data-stats-winners="0">
                        <div class="stats-label">remis</div>
                        <div class="stats-value">0%</div>
                    </div>
                    <div class="stats-row" data-stats-winners="1">
                        <div class="stats-label">&times;</div>
                        <div class="stats-value">0%</div>
                    </div>
                </div>
            </div>

            <div class="game-head-board">
                <div class="player"></div>
                <div class="board">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            </div>

            <div class="game-head-buttons">
                <input type="text" value="1000" class="form-control" data-training-steps>
                <button class="btn btn-default" data-action="start">Start</button>
            </div>

            <div class="game-head-data">
                <label>Baza:</label>
                <textarea class="form-control" rows="6" cols="50" data-network-data></textarea>
            </div>

        </div>

        <div class="game-graph">

        </div>


    </body>
</html>