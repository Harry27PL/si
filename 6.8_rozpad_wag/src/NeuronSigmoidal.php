<?php

class NeuronSigmoidal extends Neuron
{
    public function calculate($data)
    {
        $this->data = $data;

        $result = tanh($this->sum() * $this->beta * $this->betaWeight);
//        $result = tanh($this->sum() * $this->beta * $this->betaWeight);

        return $result;
    }

    private function activate($x)
    {
        return 1 / (1 + pow(exp(1), -$this->beta * $x));
    }

    public function getDerrivateOfLastSum()
    {
        return $this->beta * $this->activate($this->getLastSum()) * (1 - $this->activate($this->getLastSum()));
//        return $this->beta * $this->betaWeight * (1 - pow(tanh($this->getLastSum()), 2));
    }

}