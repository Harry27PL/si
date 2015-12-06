<?php

class NeuralNetwork
{
    private $numberOfData;

    /** @var NeuralNetworkLayer[] */
    private $neuralNetworkLayers;

    private $numberOfLayers;

    private $rangeMin;
    private $rangeMax;

    private $learningRate = 0.1;
    private $momentum = 0.4;
    private $weightDecay = 0.00005;
//    private $weightDecay = 0;

    private $historicErrors = [];

    public function __construct($numberOfData, array $numberOfNeurons)
    {
        $this->numberOfLayers = count($numberOfNeurons);

        $this->numberOfData = $numberOfData;

        $this->randomizeRange();

        for ($k = 0; $k < $this->numberOfLayers; $k++) {

            $numberOfNeuronData = $k == 0
                ? $numberOfData
                : $numberOfNeurons[$k - 1];

            $isLast   = $k == $this->numberOfLayers - 1;
            $isHidden = !$isLast && $k;

            $this->neuralNetworkLayers[] = new NeuralNetworkLayer($this, $numberOfNeurons[$k], $numberOfNeuronData, $isLast, $isHidden);
        }
    }

    function setLambda($lambda)
    {
        $this->weightDecay = $lambda;
    }


    private function randomizeRange()
    {
        $this->rangeMin = randFloat(-2, -1);
        $this->rangeMax = randFloat(1, 2);
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
        $this->randomizeRange();

        $this->historicErrors = [];

        foreach ($this->neuralNetworkLayers as $layer) {

            foreach ($layer->getNeurons() as $neuron) {
                $neuron->rerandomizeWeights();
            }

        }
    }

    public function correctWeights(array $result, array $expectedResult)
    {
        $nextLayer = null;

        for ($k = $this->numberOfLayers - 1; $k > -1; $k--) {

            $layer = $this->neuralNetworkLayers[$k];

            foreach ($layer->getNeurons() as $i => $neuron) {

                $error = $layer->isLast()
                    ? $this->getErrorForLastLayer($result[$i], $expectedResult[$i])
                    : $this->getErrorForHiddenLayer($i, $nextLayer);

                $delta = $error * $neuron->getDerrivateOfLastSum();

                $neuron->setLastDelta($delta);

                $newWeights = [];

                foreach ($neuron->getWeights() as $j => $weight) {

                    $newWeight = $weight;

                    $newWeight += $this->learningRate * $delta * (
                        !$j
                            ? $neuron->getBias()
                            : $neuron->getLastData()[$j - 1]
                    );
                    $newWeight += $this->momentum * ($weight - $neuron->getPreviousWeights()[$j]);

//                    if ($j) {
//                        $newWeight *= 1 - $this->weightDecay;
//                    }
                    $newWeight -= $weight * $this->weightDecay;
                    // tak i tak działa tzn z mnożeniem
                    // przez $weight jak i przez $newWeight

                    $newWeights[] = $newWeight;
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

        $this->historicErrors[] = $error;

        return $error;
    }

    private function getErrorForLastLayer($result, $expectedResult)
    {
        return $expectedResult - $result;
    }

    private function getErrorForHiddenLayer($i, NeuralNetworkLayer $nextLayer)
    {
        $sum = 0;

        foreach ($nextLayer->getNeurons() as $m => $neuronFromNextLayer) {

            $sum += $neuronFromNextLayer->getLastDelta() * $neuronFromNextLayer->getWeights()[$i];

        }

        return $sum;
    }

    public function removeSmallWeights()
    {
        for ($k = 1; $k < count($this->neuralNetworkLayers); $k++) {

            $layer = $this->neuralNetworkLayers[$k];

            foreach ($layer->getNeurons() as $neuron) {
                foreach ($neuron->getWeights() as $i => $weight) {
                    if (abs($weight) < 0.1)
                        $neuron->removeWeight($i);
                }
            }
        }
    }

    public function toArray()
    {
        $nodes = [];
        $edges = [];

        foreach ($this->neuralNetworkLayers as $kLayer => $layer) {
            foreach ($layer->getNeurons() as $kNeuron => $neuron) {

                $label = $neuron->getBeta()."\n--\n";
                foreach ($neuron->getWeights() as $weight) {
                    $label .= $weight."\n";
                }

                $nodes[] = [
                    'id' => $kLayer.'_'.$kNeuron,
                    'label' => $label
                ];
            }
        }

        foreach ($this->neuralNetworkLayers as $kLayer => $layer) {

            $kLayer2 = $kLayer + 1;

            if (!isset($this->neuralNetworkLayers[$kLayer2]))
                break;

            $nextLayer = $this->neuralNetworkLayers[$kLayer2];

            foreach ($layer->getNeurons() as $kNeuron => $neuron) {
                foreach ($nextLayer->getNeurons() as $kNeuron2 => $neuron) {
                    $edges[] = [
                        'from' => $kLayer.'_'.$kNeuron,
                        'to' => $kLayer2.'_'.$kNeuron2,
                    ];
                }
            }
        }

        for ($i = 0; $i < $this->numberOfData; $i++) {
            $nodes[] = [
                'id' => $i,
                'label' => 'x'.$i
            ];

            foreach ($this->neuralNetworkLayers[0]->getNeurons() as $kNeuron => $neuron) {
                $edges[] = [
                    'from' => $i,
                    'to' => '0_'.$kNeuron,
                ];
            }
        }

        return [
            'nodes' => $nodes,
            'edges' => $edges
        ];
    }

    public function getRangeMin()
    {
        return $this->rangeMin;
    }

    public function getRangeMax()
    {
        return $this->rangeMax;
    }

    public function getHistoricErrors()
    {
        return $this->historicErrors;
    }

}