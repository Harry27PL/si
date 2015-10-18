<?php

abstract class Neuron
{
    protected $bias = 1;
    protected $weights;
    protected $data;

    function __construct($weightsCount)
    {
        $this->randomizeWeights($weightsCount);
    }

    protected function randomizeWeights($weightsCount)
    {
        for ($y = 0; $y < $weightsCount; $y++) {
            $this->weights[] = rand(0, 20) / 10 - 1;
        }
    }

    protected function sum()
    {
        $sum = $this->bias;

        foreach ($this->weights as $k => $weight) {
            $sum += $this->data[$k] * $weight;
        }

        return $sum;
    }

    abstract public function calculate($data);

}