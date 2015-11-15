<?php

abstract class Neuron
{
    /** @var NeuralNetworkLayer */
    private $neuralNetworkLayer;
    private $bias = 1;
    private $lastSum;
    private $lastDelta;
    protected $weights;
    protected $previousWeights;
    protected $data;

    function __construct(NeuralNetworkLayer $neuralNetworkLayer, $numberOfData)
    {
        $this->neuralNetworkLayer = $neuralNetworkLayer;

        $this->weights = range(0, $numberOfData);

        $this->randomizeWeights();
    }

    protected function randomizeWeights()
    {
        $min = $this->getNeuralNetworkLayer()->getNeuralNetwork()->getRangeMin();
        $max = $this->getNeuralNetworkLayer()->getNeuralNetwork()->getRangeMax();

        foreach ($this->weights as $k => $weight) {
            $this->weights[$k] = randFloat($min, $max);
        }
    }

    public function rerandomizeWeights()
    {
        $this->randomizeWeights();
        $this->previousWeights = $this->weights;
    }

    protected function sum()
    {
        $sum = 0;

        foreach ($this->weights as $k => $weight) {
            $sum += !$k
                ? $this->bias * $weight
                : $this->data[$k - 1] * $weight;
        }

        $this->lastSum = $sum;

        return $sum;
    }

    public function getLastSum()
    {
        return $this->lastSum;
    }

    function getLastDelta()
    {
        return $this->lastDelta;
    }

    function setLastDelta($lastDelta)
    {
        $this->lastDelta = $lastDelta;
    }

    function getWeights()
    {
        return $this->weights;
    }

    function setWeights($weights)
    {
        $this->previousWeights = $this->weights;

        $this->weights = $weights;
    }

    function getPreviousWeights()
    {
        return $this->previousWeights;
    }

    function setPreviousWeights($previousWeights)
    {
        $this->previousWeights = $previousWeights;
    }

    function getLastData()
    {
        return $this->data;
    }

    function getBias()
    {
        return $this->bias;
    }

    /** @return NeuralNetworkLayer */
    function getNeuralNetworkLayer()
    {
        return $this->neuralNetworkLayer;
    }

    abstract public function calculate($data);

    abstract public function getDerrivateOfLastSum();


}