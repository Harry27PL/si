<?php

class Neuron
{
    private $bias = 0; // wartość progowa neuronu, str. 208, (-1; 1)
    private $weights;
    private $previousResult;

    function __construct($numberOfData)
    {
        array_fill(0, $numberOfData - 1, 0);
    }

    function getWeights()
    {
        return $this->weights;
    }

    function setWeights($weights)
    {
        $this->weights = $weights;
    }

    function getBias()
    {
        return $this->bias;
    }

    function getPreviousResult()
    {
        return $this->previousResult;
    }

    function setPreviousResult($previousResult)
    {
        $this->previousResult = $previousResult;
    }

}