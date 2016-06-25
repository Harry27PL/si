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

include 'NeuralNetwork.php';
include 'NeuralNetworkLayer.php';
include 'Neuron.php';
include 'NeuronSigmoidal.php';
include 'TrainingData.php';
include 'functions.php';

set_time_limit(120);

const NUMBERS = [
    1 => [-1, -1, 1, -1, -1, 1, -1, -1, 1, -1, -1, 1],
    4 => [1, -1, 1, 1, 1, 1, -1, -1, 1, -1, -1, 1],
    7 => [1, 1, 1, -1, -1, 1, -1, -1, 1, -1, -1, 1]
];

function start()
{
    $neuralNetwork = new NeuralNetwork(12, [25, 12]);

    $trainingDatas = prepareTrainingDatas();

    learn($neuralNetwork, $trainingDatas);

    test($neuralNetwork);

    //echo $neuralNetwork->calculate([1]);

    //echo '<pre>'.print_r($neuralNetwork, true).'</pre>';
}
start();

/** @return TrainingData[] */
function prepareTrainingDatas()
{
    return [
        new TrainingData(NUMBERS[1], NUMBERS[1]),
        new TrainingData(NUMBERS[4], NUMBERS[4]),
        new TrainingData(NUMBERS[7], NUMBERS[7]),
    ];
}

function learn(NeuralNetwork $neuralNetwork, array $trainingDatas)
{
    /* @var $trainingDatas TrainingData[] */

    test($neuralNetwork);

    $historicErrors = [];

    $i = 0;
    while (true) {

        $errors = [];

        /*?>
        <table style=""><tr>
            <th>dane</th>
            <th>wynik</th>
            <th>spodziewany</th>
            <th>błąd</th>
        </tr><?php*/

        foreach ($trainingDatas as $trainingData) {

            /*?><tr><?php*/

            $result = $neuralNetwork->calculate($trainingData->getData());

            $error = $neuralNetwork->getError($result, $trainingData->getExpectedResult());

            $errors[] = $error;

            /*?><td><?php foreach($trainingData->getData() as $v) { echo round($v, 2).'     '; } ?></td><?php
            ?><td><?= round($result[0], 2) ?></td><?php
            ?><td><?= round($trainingData->getExpectedResult()[0], 2) ?></td><?php
            ?><td><?= round($error, 2) ?></td><?php

            ?><td><?php*/
            $neuralNetwork->correctWeights($result, $trainingData->getExpectedResult());
            /*?></td><?php

            ?></tr><?php*/

        }
//        if ($i % 5000 == 0) {
//            test($neuralNetwork);
//        }

        /*?></table><?php*/

//        test($neuralNetwork);

//        echo '<div style="width: '.(avg($errors) * 1000).'px; height: 1px; background: #666;"></div>';

//        echo '<b>'.round(avg($errors), 3).'</b><br>';

        if (avg($errors) < 0.01)
            return;

        $i++;

        if ($i > 1) {
            $lastHistoricErrors = array_slice($historicErrors, -50);

            if (avg($lastHistoricErrors) > avg($historicErrors)) {
                $historicErrors = [];
                $i = 0;
                test($neuralNetwork);

                $neuralNetwork->rerandomizeWeights();

                //test($neuralNetwork);
            }
        }

        $historicErrors[] = avg($errors);

        if ($i == 100000) {
            echo 'break';
            break;
        }

        shuffle($trainingDatas);
    }
}

function test(NeuralNetwork $neuralNetwork)
{
    $trainingDatas = prepareTrainingDatas();

    foreach (NUMBERS as $number) {
        ?><div class="displays"><?php

            ?><div class="display"><?php
                foreach ($number as $v) {
                    ?><div class="display-pixel" style="opacity: <?= ($v + 1) / 2 ?>"></div><?php
                }
            ?></div><?php

            ?><div class="display"><?php
                foreach ($neuralNetwork->calculate($number) as $v) {
                    ?><div class="display-pixel" style="opacity: <?= ($v + 1) / 2 ?>"></div><?php
                }
            ?></div><?php

        ?></div><?php
    }

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