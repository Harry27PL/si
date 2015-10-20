<?php

class NeuralNetworkHopfield
{
    private $numberOfData;

    /** @var Neuron[] */
    private $neurons;

    public function __construct($numberOfData)
    {
        $this->numberOfData = $numberOfData;

        for ($i = 0; $i < $numberOfData; $i++) {
            $this->neurons[] = new Neuron($numberOfData);
        }
    }

    public function learn(array $trainingDatas)
    {
        /* @var $trainingDatas TrainingData[] */

        $numberOfNeurons = count($this->neurons);

        foreach ($this->neurons as $k => $neuron) {

            $weights = [];

            foreach ($this->neurons as $k2 => $neuron2) {

                if ($k == $k2) {
                    $weights[] = 0;
                    continue;
                }

                $sum = 0;

                foreach ($trainingDatas as $trainingData) {
                    $sum += $trainingData->getData()[$k] * $trainingData->getData()[$k2];
                }

                $weights[] = $sum / $numberOfNeurons;
            }

            $neuron->setWeights($weights);
        }


        // narysować fajną tabelkę

        ?><table><?php
            ?><tr><?php
                ?><td></td><?php
                for ($k = 0; $k < $numberOfNeurons; $k++) {
                    ?><th>Neuron <?= $k ?></th><?php
                }
            ?></tr><?php

        for ($k = 0; $k < $numberOfNeurons; $k++) {
            ?><tr><?php
                ?><th>Neuron <?= $k ?></th><?php
                for ($j = 0; $j < $numberOfNeurons; $j++) {
                    ?><td><?= round($this->neurons[$k]->getWeights()[$j], 2) ?></td><?php
                }
            ?></tr><?php
        }

        ?></table><?php
    }

    public function calculate(array $data, &$iterations)
    {
        foreach ($this->neurons as $k => $neuron) {
            $neuron->setPreviousResult($data[$k]);
        }

        $iterations = 1;

        $previousResult = $data;

        $results = [];

        while (true) {

//            echo '<hr>';

            $result = [];

            foreach ($this->neurons as $k => $neuron) {
//                echo 'y<sub>'.$k.' = </sub>';
                $result[] = $this->calculateForNeuron($neuron, $k);
//                echo '<br>';
            }

            foreach ($this->neurons as $k => $neuron) {
                $neuron->setPreviousResult($result[$k]);
            }

            $results[] = $result;

            if ($previousResult == $result) {
                break;
            }

            $previousResult = $result;

            $iterations++;

            if ($iterations == $this->numberOfData)
                break;
        }

        return $results;
    }

    private function calculateForNeuron(Neuron $neuron, $k)
    {
        $sum = 0;

//        echo '<div style="padding-left: 40px;">';

            foreach ($this->neurons as $j => $neuron2) {
                if ($neuron == $neuron2)
                    continue;

//                echo 'w<sub>'.$k.''.$j.'</sub> * y<sub>'.$j.'</sub>(t-1)<br>';
//                echo round($neuron->getWeights()[$j], 2).' * '.$neuron->getPreviousResult().'<br>';

                $sum += $neuron->getWeights()[$j] * $neuron2->getPreviousResult();
            }

//        echo '</div>';

        return $sum + $neuron->getBias() > 0
            ? 1
            : -1;
    }

    public function toArray()
    {
        $nodes = [];
        $edges = [];

        foreach ($this->neurons as $k => $neuron) {
            $nodes[] = [
                'id' => $k,
                'label' => $k
            ];

            $nodes[] = [
                'id' => 'y'.$k,
                'label' => 'y'.$k
            ];
        }

        foreach ($this->neurons as $k => $neuron) {
            foreach ($this->neurons as $k2 => $neuron2) {

                if ($k < $k2)
                    break;

                $edges[] = [
                    'from' => $k,
                    'to' => $k2
                ];
            }

            $edges[] = [
                'from' => $k,
                'to' => 'y'.$k
            ];
        }

        return [
            'nodes' => $nodes,
            'edges' => $edges
        ];
    }
}