<?php

class NeuralNetwork
{
    private $numberOfData;

    /** @var NeuralNetworkLayer[] */
    private $neuralNetworkLayers;

    private $numberOfLayers;

    private $rangeMin;
    private $rangeMax;

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

    private function randomizeRange()
    {
        $this->rangeMin = randFloat(-2, -1);
        $this->rangeMax = randFloat(1, 2);

        echo '<br>range: '.$this->rangeMin.', '.$this->rangeMax.'<br>';
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

        foreach ($this->neuralNetworkLayers as $layer) {

            foreach ($layer->getNeurons() as $neuron) {
                $neuron->rerandomizeWeights();
            }

        }
    }

    public function correctWeights(array $result, array $expectedResult)
    {
        $learningCoefficient = 0.1;
        $alfa = 0.4;

        $nextLayer = null;

        for ($k = $this->numberOfLayers - 1; $k > -1; $k--) {

            $layer = $this->neuralNetworkLayers[$k];

            foreach ($layer->getNeurons() as $i => $neuron) {

                $error = $layer->isLast()
                    ? $this->getErrorForLastLayer($result[$i], $expectedResult[$i])
                    : $this->getErrorForNotLastLayer($i, $nextLayer);

                $delta = $error * $neuron->getDerrivateOfLastSum();

                $neuron->setLastDelta($delta);

                $newWeights = [];

                foreach ($neuron->getWeights() as $j => $weight) {

                    $newWeights[] = $weight + 2 * $learningCoefficient * $delta * (
                        !$j
                            ? $neuron->getBias()
                            : $neuron->getLastData()[$j - 1]
                    ) + $alfa * ($weight - $neuron->getPreviousWeights()[$j]);

                }

                $neuron->setWeights($newWeights);

                $betaPrevious = $neuron->getBetaWeight();

                $neuron->setBetaWeight(
                    $neuron->getBetaWeight() + 2 * $learningCoefficient * $delta /*+ $alfa * ($neuron->getBetaWeight() - $neuron->getBetaPreviousWeight())*/
                );

                //echo $neuron->getBetaWeight().' + 2 * '.$learningCoefficient.' * '.$delta.' + '.$alfa.' * ('.$neuron->getBetaWeight().' - '.$neuron->getBetaPreviousWeight().')<br>';

                $neuron->setBetaPreviousWeight($betaPrevious);
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
                $label .= "--\n".$neuron->getBetaWeight();

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
}