<?php

class Main
{
    const MIN = 0.1;
    const MAX = 5;
    const STEP = 0.5;

    /** @var Data[] */
    private $allData;

    /** @var Data[] */
    private $allTrainingData;

    /** @var Data[] */
    private $allTestData;

    public function __construct()
    {
        $srand = 8;
        mt_srand($srand);
        srand($srand);

        $neuralNetwork = new NeuralNetwork(4, [7, 2]);
        $neuralNetwork2 = new NeuralNetwork(4, [7, 2]);
        $neuralNetwork2->setLambda(0);

        $this->prepareData();

        $this->learn($neuralNetwork, $this->allTrainingData);
        $this->learn($neuralNetwork2, $this->allTrainingData);
        echo '<hr>';

        echo '<h2>bez weight decay</h2>';
        $this->test($neuralNetwork);
        echo '<h2>weight decay</h2>';
        $this->test($neuralNetwork2);
    }

    /** @return Data[] */
    function prepareData()
    {
        $data = [
            new Data([3.5912, 3.0129, 0.7289, 0.5642], [-1, 1]),
            new Data([2.0922, -6.8100, 8.4636, -0.6022], [-1, 1]),
            new Data([3.2032, 5.7588, -0.7535, -0.6125], [-1, 1]),
            new Data([3.6216, 8.6661, -2.8073, -0.4470], [-1, 1]),
            new Data([3.4040, 8.7261, -2.9915, -0.5724], [-1, 1]),
            new Data([4.6765, -3.3895, 3.4896, 1.4771], [-1, 1]),
            new Data([2.6719, 3.0646, 0.3716, 0.5862], [-1, 1]),
            new Data([0.8036, 2.8473, 4.3439, 0.6017], [-1, 1]),
            new Data([5.2423, 11.0272, -4.3530, -4.1013], [-1, 1]),
            new Data([3.8660, -2.6383, 1.9242, 0.1065], [-1, 1]),
            new Data([3.4566, 9.5228, -4.0112, -3.5944], [-1, 1]),
            new Data([-1.5768, 10.8430, 2.5462, -2.9362], [-1, 1]),
            new Data([1.4479, -4.8794, 8.3428, -2.1086], [-1, 1]),
            new Data([0.3292, -4.4552, 4.5718, -0.9888], [-1, 1]),
            new Data([4.5459, 8.1674, -2.4586, -1.4621], [-1, 1]),
            new Data([1.5356, 9.1772, -2.2718, -0.7354], [-1, 1]),
            new Data([1.2247, 8.7779, -2.2135, -0.8065], [-1, 1]),
            new Data([3.9899, -2.7066, 2.3946, 0.8629], [-1, 1]),
            new Data([1.8993, 7.6625, 0.1539, -3.1108], [-1, 1]),
            new Data([4.3684, 9.6718, -3.9606, -3.1625], [-1, 1]),

            new Data([-3.8483, -12.8047, 15.6824, -1.2810], [1, -1]),
            new Data([-0.8941, 3.1991, -1.8219, -2.9452], [1, -1]),
            new Data([0.3434, 0.1242, -0.2873, 0.1465], [1, -1]),
            new Data([-0.9854, -6.6610, 5.8245, 0.5461], [1, -1]),
            new Data([-2.4115, -9.1359, 9.3444, -0.6526], [1, -1]),
            new Data([-2.2804, -0.3063, 1.3347, 1.3763], [1, -1]),
            new Data([-0.7746, -1.8768, 2.4023, 1.1319], [1, -1]),
            new Data([-1.8187, -9.0366, 9.0162, -0.1224], [1, -1]),
            new Data([-1.8219, -6.8824, 5.4681, 0.0573], [1, -1]),
            new Data([-3.5681, -8.2130, 10.0830, 0.9677], [1, -1]),
            new Data([-1.3971, 3.3191, -1.3927, -1.9948], [1, -1]),
            new Data([0.3901, -0.1428, -0.0320, 0.3508], [1, -1]),
            new Data([-1.7582, 2.7397, -2.5323, -2.2340], [1, -1]),
            new Data([-3.5801, -12.9309, 13.1779, -2.5677], [1, -1]),
            new Data([-1.5252, -6.2534, 5.3524, 0.5991], [1, -1]),
            new Data([-0.6144, -0.0911, -0.3182, 0.5021], [1, -1]),
            new Data([-0.3651, 2.8928, -3.6461, -3.0603], [1, -1]),
            new Data([-5.9034, 6.5679, 0.6766, -6.6797], [1, -1]),
            new Data([-1.8215, 2.7521, -0.7226, -2.3530], [1, -1]),
            new Data([-1.6677, -7.1535, 7.8929, 0.9677], [1, -1]),
        ];

        $this->allData = $data;

        $this->normalize();

        $this->allTrainingData = $data;
        $this->allTestData = [];

        $numberOfTrainingData = round(count($data) * 0.8);

        $i = 0;
        while ($numberOfTrainingData < count($this->allTrainingData)) {

            $randomKey = array_rand($this->allTrainingData, 1);

            if ($i % 2 == 0 && $this->allTrainingData[$randomKey]->getExpectedResult() == [-1, 1])
                continue;

            if ($i % 2 != 0 && $this->allTrainingData[$randomKey]->getExpectedResult() == [1, -1])
                continue;

            $this->allTestData[] = $this->allTrainingData[$randomKey];
            unset($this->allTrainingData[$randomKey]);

            $i++;
        }
    }

    private function normalize()
    {
        foreach (array_keys($this->allData[0]->getData()) as $col)
        {
            $sum = 0;

            foreach ($this->allData as $data) {
                $sum += $data->getData()[$col];
            }

            $mean = $sum / count($this->allData);

            $sum = 0;

            foreach ($this->allData as $data) {
                $sum += pow($data->getData()[$col] - $mean, 2);
            }

            $sd = sqrt($sum / (count($this->allData) - 1));

            foreach ($this->allData as $data) {
                $data->setData($col, ($data->getData()[$col] - $mean) / $sd);
            }
        }
    }

    function learn(NeuralNetwork $neuralNetwork, array $trainingDatas)
    {
        /* @var $trainingDatas Data[] */

        $this->test($neuralNetwork, false);

        $historicErrors = [];

        $i = 0;
        while (true) {

            $errors = [];

            foreach ($trainingDatas as $trainingData) {

                $result = $neuralNetwork->calculate($trainingData->getData());

                $error = $neuralNetwork->getError($result, $trainingData->getExpectedResult());

                $errors[] = $error;

                $neuralNetwork->correctWeights($result, $trainingData->getExpectedResult());
            }

            if ($i % 10 == 0) {
                echo $i.', ';
    //            echo 'błąd <strong>'.round(avg($errors), 3).'</strong>';
    //            $this->test($neuralNetwork);
            }

    //        $this->test($neuralNetwork);

    //echo '<b>'.avg($errors).'</b><br>';
            if (avg($errors) < 0.01)
                return;

            $i++;

            if ($i > 1) {
                $lastHistoricErrors = array_slice($historicErrors, -50);

                if (avg($lastHistoricErrors) > avg($historicErrors)) {
                    $historicErrors = [];
                    $i = 0;
    //                $this->test($neuralNetwork);

                    $neuralNetwork->rerandomizeWeights();

                    $this->test($neuralNetwork);
                    echo '<br><br>----------------------------<br><br><br>';
                }
            }

            $historicErrors[] = avg($errors);

            if ($i == 1000) {
                echo 'break';
                break;
            }

            shuffle($trainingDatas);
        }
    }

    function drawData($allData, $allDataNormalized)
    {
        ?>
            <div class="graphs">
                <div class="graph2d"
                     data-data="<?= htmlspecialchars(json_encode($allData)) ?>"
                     data-data-normalized="<?= htmlspecialchars(json_encode($allDataNormalized)) ?>"
                 ></div>
            </div>
            <script>drawData()</script>

        <?php
    }

    function test(NeuralNetwork $neuralNetwork, $withError = true)
    {
        $accuracy = [];
        $errors = [];

        foreach ($this->allTestData as $testData) {
            $result = $neuralNetwork->calculate($testData->getData());

            $resultBool = 0;
            if ($result[0] < 0 && $result[1] > 0 && $testData->getExpectedResult() == [-1, 1])
                $resultBool = 1;

            if ($result[0] > 0 && $result[1] < 0 && $testData->getExpectedResult() == [1, -1])
                $resultBool = 1;

            $accuracy[] = $resultBool;
            $errors[] = $neuralNetwork->getError($result, $testData->getExpectedResult());
        }

        echo '<div>Skuteczność <b>'.avg($accuracy).'</b></div>';
        echo '<div>Błąd <b>'.avg($errors).'</b></div>';

    //    echo '<pre>'.print_r($neuralNetwork->toArray(), true).'</pre>';

        ?>

            <div class="graphs">
                <?php if ($withError) { ?>
                    <div class="graphError" data-data="<?= htmlspecialchars(json_encode($neuralNetwork->getHistoricErrors())) ?>"></div>
                <?php } ?>
                <div class="graphNetwork" data-data="<?= htmlspecialchars(json_encode($neuralNetwork->toArray()), ENT_QUOTES, 'UTF-8') ?>"></div>
            </div>
            <script>draw()</script>

        <?php
        flush();
        ob_flush();
    }
}