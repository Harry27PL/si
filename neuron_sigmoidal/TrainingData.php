<?php

class TrainingData
{
    private $data;
    private $expectedResult;

    public function __construct(array $data, $expectedResult)
    {
        $this->expectedResult = $expectedResult;
        $this->data = $data;
    }

    function getData()
    {
        return $this->data;
    }

    function getExpectedResult()
    {
        return $this->expectedResult;
    }

}