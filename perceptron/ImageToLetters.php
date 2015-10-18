<?php

class ImageToLetters
{
    private static function getPixels($file)
    {
        $image = imagecreatefrompng($file);

        $width = imagesx($image);
        $height = imagesy($image);

        $pixels = [];

        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $rgb = imagecolorat($image, $x, $y);
                $pixel = $rgb >> 16 ? 0 : 1;

                $pixels[$y][$x] = $pixel;
            }
        }

        return $pixels;
    }

    public static function getLetters($letterWidth, $letterHeight, $tabWidth, $file)
    {
        $letters = [];

        $lastLetterNumber = 0;

        $pixelOfLetter = 0;

        foreach (self::getPixels($file) as $lineNumber => $line) {

            $letterNumer = floor($lineNumber / $letterHeight);

            $newLine = $lastLetterNumber != $letterNumer;

            if ($newLine) {
                $pixelOfLetter = 0;
            }

            $lastFontNumber = 0;
            $pixelOfFont = 0;

            foreach ($line as $pixelNumber => $pixel) {

                $fontNumber = floor($pixelNumber / $tabWidth);

                $newFont = $lastFontNumber != $fontNumber;

                if ($newFont) {
                    $pixelOfFont = 0;
                }

                if ($pixelOfFont >= $letterWidth) {
                    continue;
                }

                $letters[$letterNumer][$fontNumber][$pixelOfLetter][$pixelOfFont] = $pixel;

                $pixelOfFont++;
                $lastFontNumber = $fontNumber;
            }

            $pixelOfLetter++;
            $lastLetterNumber = $letterNumer;
        }

        return $letters;
    }
}
