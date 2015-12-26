<?php

function avg (array $array)
{
    return array_sum($array) / count($array);
}

function randFloat($min = 0, $max = 1)
{
    return $min + mt_rand() / mt_getrandmax() * ($max - $min);
}