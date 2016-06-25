<?php

class Perceptron {

    public $weights;
    private $weightsTotal;
    public $bias = 0;
    private $learningCoefficient = 0.5;

    function __construct($sizeX, $sizeY)
    {
        $this->randomizeWeights($sizeX, $sizeY);

        $this->weightsTotal = $sizeX * $sizeY;
    }

    private function randomizeWeights($sizeX, $sizeY)
    {
        for ($y = 0; $y < $sizeY; $y++) {
            for ($x = 0; $x < $sizeX; $x++) {
                $this->weights[$y][$x] = rand(0, 10) / 10;
            }
        }
    }

    public function calculate($image)
    {
        $result = 0;

        foreach ($this->weights as $y => $weightLine) {
            foreach ($weightLine as $x => $weight) {
                $result += ($image[$y][$x] * $this->weights[$y][$x]) + $this->bias;
            }
        }

        return $result > 0;
    }

    public function correctWeights($image, $correctResponse, $perceptronResponse)
    {
        foreach ($this->weights as $y => $weightLine) {
            foreach ($weightLine as $x => $weight) {
                $this->weights[$y][$x] += $this->learningCoefficient * ($correctResponse - $perceptronResponse) * $image[$y][$x];
            }
        }
        $this->bias += $this->learningCoefficient * ($correctResponse - $perceptronResponse);
    }

}