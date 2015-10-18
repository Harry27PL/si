<?php

abstract class Neuron
{
    private $bias = 1;
    private $lastSum;
    private $lastDelta;
    protected $weights;
    protected $data;

    function __construct($numberOfData)
    {
        $this->randomizeWeights($numberOfData + 1);
    }

    protected function randomizeWeights($numberOfData)
    {
        for ($y = 0; $y < $numberOfData; $y++) {
            $this->weights[] = (rand(0, 10) / 10 - 1);
        }
    }

    public function rerandomizeWeights()
    {
        foreach ($this->weights as $k => $weight) {
            $this->weights[$k] = (rand(0, 10) / 10 - 1);
        }
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
        $this->weights = $weights;
    }

    function getLastData()
    {
        return $this->data;
    }

    function getBias()
    {
        return $this->bias;
    }

    abstract public function calculate($data);

    abstract public function getDerrivateOfLastSum();


}