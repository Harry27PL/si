<?php

class NeuronSigmoidal extends Neuron
{
    public function calculate($data)
    {
        $this->data = $data;

        $result = tanh($this->sum() * $this->beta * $this->betaWeight);

        return $result;
    }

    public function getDerrivateOfLastSum()
    {
        return $this->beta * $this->betaWeight * (1 - pow(tanh($this->getLastSum()), 2));
    }

}