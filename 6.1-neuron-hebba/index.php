<?php

include 'Neuron.php';
include 'NeuronHebb.php';
include 'TrainingData.php';

error_reporting(E_ALL);

function start()
{
    $neuron = new NeuronHebb(12);

    $trainingDatas = [
        new TrainingData([-1, -1, 1, -1, -1, 1, -1, -1, 1, -1, -1, 1], -1),
        new TrainingData([1, -1, 1, 1, 1, 1, 1, -1, -1, 1, -1, -1, 1], 1),
    ];

    learn($neuron, $trainingDatas);

    echo $neuron->calculate([-1, -1, 1, -1, -1, 1, -1, -1, 1, -1, -1, 1]).'<br>';
    echo $neuron->calculate([1, -1, 1, 1, 1, 1, 1, -1 -1, 1, -1, -1, 1]).'<br>';
    echo $neuron->calculate([1, 1, 1, -1, -1, 1, -1, -1, 1, -1, -1, 1]).'<br>';
}

start();

function learn(NeuronHebb $neuron, $trainingDatas)
{
    /* @var TrainingData $trainingDatas[] */

    $i = 0;
    while (true) {

        foreach ($trainingDatas as $trainingData) {

            $result = $neuron->calculate($trainingData->getData());

            if (abs($result - $trainingData->getDecision()) < 0.1)
                continue;

            $neuron->correctWeights($trainingData->getDecision());

        }

        $i++;

        if ($i == 10)
            break;
    }
}

