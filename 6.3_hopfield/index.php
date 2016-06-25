<!DOCTYPE html>
<html>
    <head>
        <title>6.3 propagacja wsteczna</title>
        <link href="css/main.css" rel="stylesheet">
        <script type="text/javascript" src="//code.jquery.com/jquery-1.11.3.min.js"></script>
        <script type="text/javascript" src="bower_components/vis/dist/vis.js"></script>
        <script type="text/javascript" src="js/main.js"></script>
    </head>
    <body>

<?php

include 'NeuralNetworkHopfield.php';
include 'Neuron.php';
include 'TrainingData.php';
include 'functions.php';

const NUMBERS = [
    1 => [1,1,1,1,-1,1,1,1,1],
    4 => [1,-1,1,-1,1,-1,1,-1,1],
    7 => [-1,-1,-1,-1,-1,-1,-1,-1,-1]
];

function start()
{
    $neuralNetwork = new NeuralNetworkHopfield(9);

    $trainingDatas = prepareTrainingDatas();

    $neuralNetwork->learn($trainingDatas);

    test($neuralNetwork);
}
start();

/** @return TrainingData[] */
function prepareTrainingDatas()
{
    return [
        new TrainingData(NUMBERS[1]),
        //new TrainingData([-1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1]),
        new TrainingData(NUMBERS[4]),
        new TrainingData(NUMBERS[7]),
    ];
}

function test(NeuralNetworkHopfield $neuralNetwork)
{
    ?><div class="displays"><?php
        foreach (NUMBERS as $number) {
            ?><div class="display"><?php
                foreach ($number as $v) {
                    ?><div class="display-pixel" style="opacity: <?= ($v + 1) / 2 ?>"></div><?php
                }
            ?></div><?php
        }
    ?></div><?php

    $np = [0, -1, 1, -0.7, -1, 0, 1, -1, 1];

    ?><div class="displays"><?php
        ?><div class="display"><?php
            foreach ($np as $v) {
                ?><div class="display-pixel" style="opacity: <?= ($v + 1) / 2 ?>"></div><?php
            }
        ?></div><?php
    ?></div><?php

    ?><div class="displays"><?php

        $iterations = 0;

        $results = $neuralNetwork->calculate($np, $iterations);

        foreach ($results as $result) {
            ?><div class="display"><?php
                foreach ($result as $v) {
                    ?><div class="display-pixel" style="opacity: <?= ($v + 1) / 2 ?>"></div><?php
                }
            ?></div><?php
        }

        echo $iterations;

    ?></div><?php

    ?>

        <div class="graphs">
            <div class="graphNetwork" data-data="<?= htmlspecialchars(json_encode($neuralNetwork->toArray()), ENT_QUOTES, 'UTF-8') ?>"></div>
        </div>
        <script>draw()</script>

    <?php
}
?>

    </body>
</html>