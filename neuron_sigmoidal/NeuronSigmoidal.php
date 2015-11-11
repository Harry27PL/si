<?php

class NeuronSigmoidal extends Neuron
{
    private $beta = 1;
    private $betaWeight = 1;

    function __construct($numberOfData)
    {
        parent::__construct($numberOfData);
    }

    public function calculate($data)
    {
        $this->data = $data;

        return $this->activation();
    }

    private function activation()
    {
        return tanh($this->sum() * $this->beta * $this->betaWeight);
    }

    public function getDerrivateOfLastSum()
    {
        return $this->beta * $this->betaWeight * (1 - pow(tanh($this->getLastSum()), 2));
    }

    public function getError($result, $expectedResult)
    {
        return 1/2 * pow((
            $expectedResult - $this->activation()
        ), 2);
    }

    public function correctWeights($result, $expectedResult)
    {
        $learningCoefficient = 0.2;
        $error = -($expectedResult - $result) * $this->getDerrivateOfLastSum();

        $newWeights = [];

        foreach ($this->getWeights() as $j => $weight) {
            $x = !$j
                ? $this->getBias()
                : $this->data[$j - 1];

            $newWeights[] = $weight - $learningCoefficient * $error * $x;
        }

        $this->setWeights($newWeights);

        $this->betaWeight = $this->betaWeight - $learningCoefficient * $error * $this->beta;
    }

    public function border($x)
    {
        $w1 = $this->weights[1];
        $w2 = $this->weights[2];

        $result = -($w1 / ($w2 ? $w2 : 0.00000001)) * $x - ($this->bias * $this->weights[0] / ($w2 ? $w2 : 0.00000001));

        return $result;
    }

    function getBetaWeight()
    {
        return $this->betaWeight;
    }


}