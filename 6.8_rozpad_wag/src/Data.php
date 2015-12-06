<?php

class Data implements JsonSerializable
{
    private $data;
    private $expectedResult;

    public function __construct(array $data, array $expectedResult)
    {
        $this->expectedResult = $expectedResult;
        $this->data = $data;
    }

    function getData()
    {
        return $this->data;
    }

    function setData($col, $value)
    {
        $this->data[$col] = $value;
    }

    function getExpectedResult()
    {
        return $this->expectedResult;
    }

    public function jsonSerialize()
    {
        return [
            'data' => $this->data,
            'expectedResult' => $this->expectedResult,
        ];
    }

}