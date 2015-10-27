'use strict';

var net;

$(document).ready(function(){

    function activate(x, y)
    {
        var result = Math.pow(
           1 + Math.pow(x, -2) + Math.pow(y, -1.5)
        , 2);

        return Math.tanh(result / 20000);
    }

    net = new Architect.Perceptron(2, 5, 1);

    var trainingSet = [];

    for (var i = 0.1; i < 500; i ++) {
        var x = Math.random() * 5 + 0.02;
        var y = Math.random() * 5 + 0.02;
        trainingSet.push({
            input: [x, y],
            output: [activate(x, y)]
        });
    }

    var trainer = new Trainer(net);
    var results = trainer.train(trainingSet, {
        error: .0005,
    });

    console.log(print_r(results));

    test(net);
});

function test(net)
{
    var el = $('.graph3d');
    var data = [];

    for (var x = 0; x < 5; x += 0.1) {
        for (var y = 0; y < 5; y += 0.1) {
            data.push({
                x: x,
                y: y,
                z: net.activate([x, y])[0]
            });
        }
    }

    var options = {
            width: "400px",
            height: "400px",
            style: "surface",
            showPerspective: true,
            showGrid: true,
            showShadow: false,
            keepAspectRatio: true,
            verticalRatio: 0.5
    };

    var graph3d = new vis.Graph3d(el[0], data, options);
}