<?php

class NeuronSigmoidal extends Neuron
{
    function __construct($numberOfData)
    {
        parent::__construct($numberOfData);
    }

    public function calculate($data)
    {
        $this->data = $data;

        $result = tanh($this->sum());

        return $result;
    }

    public function getDerrivateOfLastSum()
    {
        return 1 - pow(tanh($this->getLastSum()), 2);
    }

}