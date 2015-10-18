<?php

class NeuralNetwork
{
    /** @var NeuralNetworkLayer[] */
    private $neuralNetworkLayers;

    private $numberOfLayers;

    public function __construct($numberOfData, array $numberOfNeurons)
    {
        $this->numberOfLayers = count($numberOfNeurons);

        for ($k = 0; $k < $this->numberOfLayers; $k++) {

            $numberOfNeuronData = $k == 0
                ? $numberOfData
                : $numberOfNeurons[$k - 1];

            $isLast   = $k == $this->numberOfLayers - 1;
            $isHidden = !$isLast && $k;

            $this->neuralNetworkLayers[] = new NeuralNetworkLayer($numberOfNeurons[$k], $numberOfNeuronData, $isLast, $isHidden);
        }
    }

    public function calculate(array $data)
    {
        foreach ($this->neuralNetworkLayers as $k => $neuralNetworkLayer) {

            $data = $neuralNetworkLayer->calculate($data);

        }

        return $data;
    }

    public function rerandomizeWeights()
    {
        foreach ($this->neuralNetworkLayers as $layer) {

            foreach ($layer->getNeurons() as $neuron) {
                $neuron->rerandomizeWeights();
            }

        }
    }

    public function correctWeights(array $result, array $expectedResult)
    {
        $learningCoefficient = 0.1;

        $nextLayer = null;

        for ($k = $this->numberOfLayers - 1; $k > -1; $k--) {

            $layer = $this->neuralNetworkLayers[$k];

            foreach ($layer->getNeurons() as $i => $neuron) {

                $error = $layer->isLast()
                    ? $this->getErrorForLastLayer($result[$i], $expectedResult[$i])
                    : $this->getErrorForNotLastLayer($i, $nextLayer);

                $delta = $error * $neuron->getDerrivateOfLastSum();

                //echo '<b style="color: blue">'.round($neuron->getDerrivateOfLastSum(), 2).'</b> -> ';

                $neuron->setLastDelta($delta);

                $newWeights = [];

                foreach ($neuron->getWeights() as $j => $weight) {

                    // delta jest czasem dziwnie duża
                    // może to zasługa pochodnej

/*echo round(2 * $learningCoefficient * $delta * (
                        !$j
                            ? $neuron->getBias()
                            : $neuron->getLastData()[$j - 1]
                    ), 2).'<br>';*/

                    $newWeights[] = $weight + 2 * $learningCoefficient * $delta * (
                        !$j
                            ? $neuron->getBias()
                            : $neuron->getLastData()[$j - 1]
                    );

                }

                $neuron->setWeights($newWeights);
            }

            $nextLayer = $layer;
        }
    }

    public function getError(array $result, array $expectedResult)
    {
        $error = 0;

        for ($i = 0; $i < count($result); $i++) {
            $error += pow($expectedResult[$i] - $result[$i], 2);
        }

        return $error;
    }

    private function getErrorForLastLayer($result, $expectedResult)
    {
        return $expectedResult - $result;
    }

    private function getErrorForNotLastLayer($i, NeuralNetworkLayer $nextLayer)
    {
        $sum = 0;

        foreach ($nextLayer->getNeurons() as $m => $neuronFromNextLayer) {

            $sum += $neuronFromNextLayer->getLastDelta() * $neuronFromNextLayer->getWeights()[$i];

        }

        return $sum;
    }
}