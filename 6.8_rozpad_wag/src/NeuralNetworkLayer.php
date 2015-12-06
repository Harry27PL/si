<?php

class NeuralNetworkLayer
{
    /** @var NeuralNetwork*/
    private $neuralNetwork;

    private $neurons;

    private $last;
    private $hidden;

    public function __construct(NeuralNetwork $neuralNetwork, $numberOfNeurons, $numberOfData, $isLast, $isHidden)
    {
        $this->neuralNetwork = $neuralNetwork;

        for ($i = 0; $i < $numberOfNeurons; $i++) {
            $this->neurons[] = new NeuronSigmoidal($this, $numberOfData);
        }

        $this->hidden = $isHidden;
        $this->last   = $isLast;
    }

    public function calculate($data)
    {
        $resultData = [];

        foreach ($this->neurons as $neuron) {

            $resultData[] = $neuron->calculate($data);

        }

        return $resultData;
    }

    public function isHidden()
    {
        return $this->hidden;
    }

    public function isLast()
    {
        return $this->last;
    }

    /** @return Neuron[] */
    function getNeurons()
    {
        return $this->neurons;
    }

    /** @return NeuralNetwork */
    function getNeuralNetwork()
    {
        return $this->neuralNetwork;
    }

}