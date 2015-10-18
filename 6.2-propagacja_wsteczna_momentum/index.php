<!DOCTYPE html>
<html>
    <head>
        <title>Graph 3D demo</title>
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

const MIN = 0.1;
const MAX = 5;
const STEP = 0.2;

function start()
{
    $neuralNetwork = new NeuralNetwork(2, [5, 1]);

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
    $result = [];

    for ($i = 0; $i <= 100; $i++) {
        $x = rand(0, 5000) / 1000 + 0.001;
        $y = rand(0, 5000) / 1000 + 0.001;

        $result[] = new TrainingData([$x, $y], [func($x, $y)]);
    }

    return $result;
}

function func($x, $y)
{
    $result = pow(
       1 + pow($x, -2) + pow($y, -1.5)
    , 2);

    return tanh($result / 20000);
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

//            if ($i % 100 == 0) {
//                test($neuralNetwork);
//            }
        }

        /*?></table><?php*/

//        test($neuralNetwork);

//        echo '<div style="width: '.(avg($errors) * 1000).'px; height: 1px; background: #666;"></div>';

//        echo '<b>'.round(avg($errors), 3).'</b><br>';

        if (avg($errors) < 0.001)
            return;

        $i++;

        if ($i > 1) {
            $lastHistoricErrors = array_slice($historicErrors, -500);

            if (avg($lastHistoricErrors) > avg($historicErrors)) {
                $historicErrors = [];
                $i = 0;
                test($neuralNetwork);

                $neuralNetwork->rerandomizeWeights();

                test($neuralNetwork);
            }
        }

        $historicErrors[] = avg($errors);

        if ($i == 10000) {
            echo 'break';
            break;
        }

        shuffle($trainingDatas);
    }
}

function test(NeuralNetwork $neuralNetwork)
{
    $correctResult = [];

    for ($x = MIN; $x <= MAX; $x += STEP) {
        for ($y = MIN; $y <= MAX; $y += STEP) {
            $result = func($x, $y);

            if (is_infinite($result))
                continue;

            $correctResult[] = [$x, $y, func($x, $y)];
        }
    }

    $networkResult = [];

    for ($x = MIN; $x <= MAX; $x += STEP) {
        for ($y = MIN; $y <= MAX; $y += STEP) {
            $networkResult[] = [$x, $y, $neuralNetwork->calculate([$x, $y])[0]];
        }
    }

//    echo '<pre>'.print_r($neuralNetwork->toArray(), true).'</pre>';

    ?>

        <div class="graphs">
            <div class="graph3d" data-data="<?= json_encode($correctResult) ?>"></div>
            <div class="graph3d" data-data="<?= json_encode($networkResult) ?>"></div>
            <div class="graphNetwork" data-data="<?= htmlspecialchars(json_encode($neuralNetwork->toArray()), ENT_QUOTES, 'UTF-8') ?>"></div>
        </div>
        <script>draw()</script>

    <?php
}
?>

    </body>
</html>