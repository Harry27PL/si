<?php

abstract class Neuron
{
    private $bias = 1;
    private $lastSum;
    private $lastDelta;
    protected $weights;
    protected $previousWeights;
    protected $data;
    protected $beta = 1;
    protected $betaWeight = 1;
    protected $betaPreviousWeight = 1;

    function __construct($numberOfData)
    {
        $this->randomizeWeights($numberOfData + 1);
    }

    protected function randomizeWeights($numberOfData)
    {
        for ($y = 0; $y < $numberOfData; $y++) {
            $this->weights[] = (rand(0, 20) / 10 - 1);
        }
    }

    public function rerandomizeWeights()
    {
        foreach ($this->weights as $k => $weight) {
            $this->weights[$k] = (rand(0, 20) / 10 - 1);
        }

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

    function getBeta()
    {
        return $this->beta;
    }

    function setBeta($beta)
    {
        $this->beta = $beta;
    }

    function getBetaWeight()
    {
        return $this->betaWeight;
    }

    function setBetaWeight($betaWeight)
    {
        $this->betaWeight = $betaWeight;
    }

    function getBetaPreviousWeight()
    {
        return $this->betaPreviousWeight;
    }

    function setBetaPreviousWeight($betaPreviousWeight)
    {
        $this->betaPreviousWeight = $betaPreviousWeight;
    }

    abstract public function calculate($data);

    abstract public function getDerrivateOfLastSum();

}