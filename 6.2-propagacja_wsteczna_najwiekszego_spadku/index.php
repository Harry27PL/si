<style>
    body {
        font-family: arial;
        font-size: 12px;
    }
    table {
        font-size: 12px;
        margin: 10px 0px;
    }
    td, th {
        padding: 2px 10px;
        vertical-align: top;
        border-bottom: 1px solid #ddd;
    }
</style>

<?php

include 'NeuralNetwork.php';
include 'NeuralNetworkLayer.php';
include 'Neuron.php';
include 'NeuronSigmoidal.php';
include 'TrainingData.php';
include 'functions.php';

function start()
{
    $neuralNetwork = new NeuralNetwork(1, [3, 1]);

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

    for ($i = 0; $i < 6.5; $i += 0.2) {
        $result[] = new TrainingData([$i], [sin($i)]);
    }

    return $result;
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
        <table style="left: <?= $i * 400 ?>px; top: 0px; position: absolute; display: none;"><tr>
            <th>dane</th>
            <th>wynik</th>
            <th>spodziewany</th>
            <th>błąd</th>
            <th>poprawa</th>
        </tr><?php*/

        foreach ($trainingDatas as $trainingData) {

            /*?><tr><?php*/

            $result = $neuralNetwork->calculate($trainingData->getData());

            $error = $neuralNetwork->getError($result, $trainingData->getExpectedResult());

            $errors[] = $error;

            /*?><td><?= round($trainingData->getData()[0], 2) ?></td><?php
            ?><td><?= round($result[0], 2) ?></td><?php
            ?><td><?= round($trainingData->getExpectedResult()[0], 2) ?></td><?php
            ?><td><?= round($error, 2) ?></td><?php*/

            /*?><td><?php*/
            $neuralNetwork->correctWeights($result, $trainingData->getExpectedResult());
            /*?></td><?php

            ?></tr><?php*/

            if ($i % 100 == 0) {
            //    test($neuralNetwork);
            }
        }

        /*?></table><?php*/

        echo '<div style="width: '.(avg($errors) * 1000).'px; height: 1px; background: #666;"></div>';

        //echo '<b>'.round(avg($errors), 3).'</b><br>';

        if (avg($errors) < 0.05)
            return;

        $i++;

        if ($i > 1) {
            $lastHistoricErrors = array_slice($historicErrors, -50);

            if (avg($lastHistoricErrors) > avg($historicErrors)) {
                $historicErrors = [];
                $i = 0;
                test($neuralNetwork);

                $neuralNetwork->rerandomizeWeights();

                test($neuralNetwork);
            }
        }

        $historicErrors[] = avg($errors);

        if ($i == 5000)
            break;

        shuffle($trainingDatas);
    }
}

function test(NeuralNetwork $neuralNetwork)
{
    ?><div style="position: relative; width: 1000px; height: 100px; background: #eee; border-radius: 5px; margin: 20px 0px 20px;"><?php

        for ($i = 0; $i < 6.5; $i += 0.1) {

            $result = $neuralNetwork->calculate([$i])[0];
            $left = $i * 100;
            $height = $result + 1;
            $height *= 50;

            ?><div style="position: absolute; left: <?= $left ?>px; width: 10px; bottom: 0px; height: <?= $height ?>px; background: green; opacity: 0.5;"></div><?php
        }

        for ($i = 0; $i < 6.5; $i += 0.1) {

            $result = sin($i);
            $left = $i * 100;
            $height = $result + 1;
            $height *= 50;

            ?><div style="position: absolute; left: <?= $left ?>px; width: 10px; bottom: 0px; height: <?= $height ?>px; background: red; opacity:0.5;"></div><?php
        }

    ?></div><?php
}