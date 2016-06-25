<?php

class TrainingData
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    function getData()
    {
        return $this->data;
    }

}