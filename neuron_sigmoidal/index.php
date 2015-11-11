<!DOCTYPE html>
<html>
    <head>
        <title>Neuron sigmoidalny</title>
        <link href="css/main.css" rel="stylesheet">
        <script type="text/javascript" src="//code.jquery.com/jquery-1.11.3.min.js"></script>
        <script type="text/javascript" src="vendor/flot/jquery.flot.min.js"></script>
        <script type="text/javascript" src="js/main.js"></script>
    </head>
    <body>

<?php

include 'Neuron.php';
include 'NeuronSigmoidal.php';
include 'TrainingData.php';
include 'TestData.php';
include 'functions.php';

const MIN = -3;
const MAX = 3;
const STEP = 0.5;

function start()
{
    $neuron = new NeuronSigmoidal(2);

    $trainingDatas = prepareTrainingDatas();

    learn($neuron, $trainingDatas);

    test($neuron);

    //echo $neuralNetwork->calculate([1]);

    //echo '<pre>'.print_r($neuralNetwork, true).'</pre>';
}
start();

/** @return TrainingData[] */
function prepareTrainingDatas()
{
    return [
        new TrainingData([-2.5, 2], 1),
        new TrainingData([-1, 0.5], 1),
        new TrainingData([-0.5, -0.5], 1),
        new TrainingData([0.5, -0.5], 1),
        new TrainingData([0.5, 0.5], 1),
        new TrainingData([1, 0.5], 1),
        new TrainingData([1, 3], -1),
        new TrainingData([2, 1], -1),
        new TrainingData([3, 0], -1),
    ];
}

/** @return TestData[] */
function getTestData()
{
    return [
        new TestData([-2.5, 2], 1),
        new TestData([-1, 0.5], 1),
        new TestData([-0.5, -0.5], 1),
        new TestData([0.5, -0.5], 1),
        new TestData([0.5, 0.5], 1),
        new TestData([1, 0.5], 1),
        new TestData([1, 3], -1),
        new TestData([2, 1], -1),
        new TestData([3, 0], -1),
    ];
}

function learn(NeuronSigmoidal $neuron, array $trainingDatas)
{
    /* @var $trainingDatas TrainingData[] */

    test($neuron);

    $i = 0;

    while (true) {
        $errors = [];

        foreach ($trainingDatas as $trainingData) {

            $result = $neuron->calculate($trainingData->getData());

            $error = $neuron->getError($result, $trainingData->getExpectedResult());
            $errors[] = abs($error);

            $neuron->correctWeights($result, $trainingData->getExpectedResult());
        }

//        echo avg($errors).'<br>';

        if (avg($errors) < 0.05)
            break;

//        if ($i % 10 == 0)
//            test($neuron);

        $i++;
        if ($i == 100)
            break;
    }

//    echo '<pre>'.print_r($neuron->getWeights(), true).'</pre>';
//    echo '<pre>'.print_r($neuron->getBetaWeight(), true).'</pre>';

    echo avg($errors).'<br>';
    echo $i.'<br>';
}

function test(Neuron $neuron)
{
    $neuronResult = [];

    for ($x = MIN; $x <= MAX; $x += STEP) {
        $neuronResult[] = [$x, $neuron->border($x)];
    }

    $dots = [];

    foreach (getTestData() as $testData) {
        $dots[$testData->getExpectedResult()][] = [$testData->getData()[0], $testData->getData()[1]];
    }

    ?>

        <div class="graphs">
            <div class="graph2d" style="width:600px; height: 300px"
                    data-data="<?= json_encode($neuronResult) ?>"
                    data-dots="<?= htmlspecialchars(json_encode($dots), ENT_QUOTES, 'UTF-8') ?>"></div>
        </div>
        <script>draw()</script>

    <?php
}
?>

    </body>
</html>