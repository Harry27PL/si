<?php

class Neuron
{
    /** @var NeuralNetworkLayer */
    private $neuralNetworkLayer;
    private $bias = 1;
    private $lastSum;
    private $lastDelta;
    protected $weights;
    protected $previousWeights;
    protected $data;
    protected $beta = 1;
    protected $betaWeight = 1;
    protected $betaPreviousWeight = 1;

    function __construct(NeuralNetworkLayer $neuralNetworkLayer, $numberOfData)
    {
        $this->randomizeWeights();
    }

}