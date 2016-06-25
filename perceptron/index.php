<?php

include 'ImageToLetters.php';
include 'Perceptron.php';

function displayPixel($value, $background)
{
    $value = ($value);
    echo '<span style="width: 5px; height: 5px; background: '.$background.'; opacity:'.$value.'; display: inline-block; border-radius: 100%;"></span>';
}

function display(array $image, $background = '#000')
{
    foreach ($image as $y => $pixels) {
        foreach ($pixels as $pixel) {
            displayPixel($pixel, $background);
        }
        echo '<br>';
    }
    echo '<div style="height: 5px;"></div>';
}

$alfabet = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L'];

$letterWidth    = 9;
$letterHeight   = 9;
$tabWidth       = 36;

$letters = ImageToLetters::getLetters($letterWidth, $letterHeight, $tabWidth, 'litery.png');
$lettersTest = ImageToLetters::getLetters($letterWidth, $letterHeight, $tabWidth, 'literyTestowe.png');

/*foreach ($letters as $letterNumber => $fonts) {
    echo $letterNumber.'<br>';

    foreach ($fonts as $font) {
        display($font);
    }
}*/

//

$perceptron = new Perceptron($letterWidth, $letterHeight);

function learn($letters, Perceptron $perceptron)
{
    $learnFor = 11;

    $iterations = 100;

    display($perceptron->weights);
    for ($i = 0; $i <= $iterations; $i++) {

        foreach ($letters as $letterNumber => $fonts) {

            $isCorrect = $letterNumber == $learnFor;

            foreach ($fonts as $letter) {

                //if ($i % 250 == 0)
                  //  display($letter, '#999');

                $perceptronResponse = $perceptron->calculate($letter);

                if ($perceptronResponse == $isCorrect) {
                    //if ($i % 250 == 0)
                      //  display($perceptron->weights, 'green');
                    continue;
                }

                //if ($i % 250 == 0)
                    //display($perceptron->weights, 'red');

                $perceptron->correctWeights($letter, (int) $isCorrect, (int) $perceptronResponse);
            }
        }
        //if ($i % 250 == 0)
          //  display($perceptron->weights);
    }
    display($perceptron->weights);
}

learn($letters, $perceptron);

foreach ($lettersTest as $letterTest) {
    display($letterTest[0], $perceptron->calculate($letterTest[0]) ? 'green' : '#666');
}

