<?php

class TrainingData
{
    private $data;
    private $decision;

    public function __construct(array $data, $decision)
    {
        $this->decision = $decision;
        $this->data = $data;
    }

    function getData()
    {
        return $this->data;
    }

    function getDecision()
    {
        return $this->decision;
    }

}