function AI()
{
    var historicMoves = [];
    var NN = generateNetwork();

    function generateNetwork()
    {
        var NN = FANN.create([9, 4, 9]);
        NN.set_activation_function_hidden(FANN.SIGMOID_SYMMETRIC);
        NN.set_activation_function_output(FANN.SIGMOID);

        NN.randomize_weights(-Math.random(), Math.random());

        return NN;
    }

    this.getResult = function()
    {
        var board  = Game.getBoard();

        return NN.run(board);
    }

    this.getMove = function()
    {
        var board  = Game.getBoard();
        var result = NN.run(board);

        var position = (function(){
            while (true) {
                var max = (function(){
                    var max = 0;
                    for (var i in result) {
                        if (result[i] > result[max])
                            max = i;
                    }
                    return max;
                })();

                if (!board[max])
                    return max;

                result[max] = 0;
            }
        })();

        historicMoves.push({
            input: board,
            decision: (function(){
                var decision = [];

                for (var i = 0; i < 9; i++)
                    decision.push(0);

                decision[position] = 1;

                return decision;
            })()
        });

        return position;
    };

    this.train = function()
    {
        for (var i in historicMoves) {
            NN.train(historicMoves[i].input, historicMoves[i].decision);
        }

    };

    this.resetHistoricMoves = function()
    {
        historicMoves = [];
    }
};

FANN_ready = function() {

//    startGame();

};

var historicWinners = [];

var toTrainAI;

$(document).on('click', '[data-action="start"]', function(){
    startGame();
});

function startGame()
{
    var totalGames = 0;

    if (!toTrainAI)
        toTrainAI = new AI();

    var randomAI  = new AI();
    var currentPlayer = 1;

    while (totalGames < 50) {

        console.log(totalGames)

        var currentAI = currentPlayer == 1
            ? randomAI
            : toTrainAI;

        var winner = Game.move(currentAI.getMove());

        if (winner !== null) {

            if (winner == -1) {
                toTrainAI.train();
            }

            toTrainAI.resetHistoricMoves();

//            if (totalGames % 100 == 0) {
//                var avg = (function(){
//                    var last = _.last(historicWinners, 100);
//
//                    var sum = last.reduce(function(previousValue, currentValue) {
//                        return previousValue + currentValue;
//                    });
//
//                    return sum / last.length;
//                })();
//
////                if (avg > 0) {
////                    console.log('resetuję')
////                    toTrainAI = new AI();
////                }
//            }

            randomAI = new AI();

            currentPlayer = 1;

            totalGames++;

        } else {
            currentPlayer = currentPlayer == 1
                ? -1
                : 1;
        }

    }

    resetGraph();
}

(function(){

    $(document).on('game.end', function(e, winner){
        historicWinners.push(winner);

        resetStats();
    });

    function resetStats()
    {
        $('[data-stats-games] .stats-value').html(historicWinners.length);
        statsWinners(-1);
        statsWinners(0);
        statsWinners(1);

        function statsWinners(player) {
            var total = historicWinners.filter(function(value){
                return value == player;
            }).length;

            $('[data-stats-winners="'+player+'"] .stats-value').html(
                Math.round(total / historicWinners.length * 100)+'%'
            );
        }
    }

})();

function resetGraph()
{
    function getData(name, player)
    {
        var data = [name];

        for (var k in historicWinners) {

            var total = historicWinners.filter(function(value, index){
                return index <= k && value == player;
            }).length;

            data.push(Math.round(total / (parseInt(k) + 1) * 100));
        }

        return data;
    }

    var winData = getData('wygrane', -1);
    var loseData = getData('przegrane', 1);
    var tieData = getData('remisy', 0);

    var chart = c3.generate({
        bindto: $('.game-graph')[0],
        data: {
          columns: [
            winData,
            loseData,
            tieData,
          ]
        },
        line: {
            connectNull: true
        }
    });

}

$(document).on('click', '.board div', function(){

    var winner = Game.move($(this).index());

    if (winner === null) {
        Game.move(toTrainAI.getMove());
    }

});
