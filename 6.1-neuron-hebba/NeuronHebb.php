<?php

class NeuronHebb extends Neuron
{
    //private $oldWeights;
    private $learningCoefficient = 0.1;

    function __construct($weightsCount)
    {
        parent::__construct($weightsCount);

        //$this->oldWeights = $this->weights;
    }

    public function calculate($data)
    {
        $this->data = $data;

        $result = tanh($this->sum());

        return $result;
    }

    public function correctWeights($result)
    {
        foreach ($this->weights as $k => $weight) {
            $this->weights[$k] = $weight + $this->learningCoefficient * $this->data[$k] * $result;
        }
    }

}