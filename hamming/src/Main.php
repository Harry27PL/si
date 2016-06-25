<?php

class Main
{
    public function __construct()
    {
        $neuralNetwork = new NeuralNetwork();

        $this->learn($neuralNetwork);
    }

    /** @return Data[] */
    function prepareData()
    {

    }

    function learn(NeuralNetwork $neuralNetwork, array $trainingDatas)
    {
    }

}