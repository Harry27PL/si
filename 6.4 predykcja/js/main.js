'use strict';

var net;

(function(){

    var $sketch,
        $predict,
        width,
        height;

    var inputNumber = 50;
    var outputNumber = 30;

    function renderSketch()
    {
        var $wrapper = $('.sketch-wrapper');
        $wrapper.html('<canvas class="sketch" width="'+$wrapper.attr('width')+'" height="'+$wrapper.attr('height')+'"></canvas>');

        $sketch = $('.sketch');

        $('.sketch').sketch({
            defaultSize: 2
        });
    }

    $(document).ready(function(){

        renderSketch();

        width = $sketch.width();
        height = $sketch.height();

        $predict = $('.predict');
        $predict.attr('width', outputNumber);
        $predict.attr('height', height);
    });

    $(document).on('click', '[data-action=run]', function(){
        net = getNetwork();

        var trainingSet = getTrainingSet();

        var trainer = new Trainer(net);
        var results = trainer.train(trainingSet, {
            error: .005,
            iterations: 10000
        });

        console.log(print_r(results));

        test(net);
    });

    $(document).on('click', '[data-action=clear]', function(){
        renderSketch();

        var ctx = $predict[0].getContext('2d').clearRect(0, 0, outputNumber, height);
    });

    function getNetwork()
    {
        return new Architect.Perceptron(inputNumber, inputNumber * 2 + 1, outputNumber);
    }

    function getTrainingSet()
    {
        var canvasData = getCanvasData();
        var set = [];

        for (var i = 0; i < canvasData.length - inputNumber - outputNumber; i++) {
            var inputs = [];
            var outputs = [];

            for (var j = 0; j < inputNumber; j++)
                inputs.push(canvasData[i + j]);

            for (var j = 0; j < outputNumber; j++)
                outputs.push(canvasData[i + inputNumber + j]);

            set.push({
                input: inputs,
                output: outputs
            });
        }

        return set;
    }

    function getCanvasData()
    {
        var values = {};

        var canvas = $sketch[0].getContext('2d').getImageData(0, 0, width, height);
        var pixels = canvas.data;

        var y = 0;
        for (var i = 0, n = pixels.length; i < n; i += 4) {
            var pixel = pixels[i + 3];

            var x = (i / 4) % $sketch.width();

            if (!values[x] && pixel)
                values[x] = 1 - y / width;

            if (!x)
                y++;
        }

        return _.toArray(values);
    }

    function test(net)
    {
        var input = _.last(getCanvasData(), inputNumber);
        var output = net.activate(input);

        var ctx = $predict[0].getContext('2d');

        for (var x in output) {
            var y = height - output[x] * height;
            ctx.fillRect(x, y, 1, 2);
        }
    }

})();