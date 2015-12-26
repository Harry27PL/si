FANN_ready = function() {

    NN = FANN.create([9, 4, 1]);
    NN.set_activation_function_hidden(FANN.SIGMOID_SYMMETRIC);
    NN.set_activation_function_output(FANN.SIGMOID);

};

$(document).on('game.move', function(e, data){

    if (data == -1)
        return;

    var board = Game.getBoard();

    var result = NN.run(board);

    var moves = _.filter(board, function(value){
        return !value;
    }).length;

    // zbugowane bo czasami wstawia tam gdzie jest postawione
    var position = (function(){
        var iterations = Math.round(result * moves);

        for (var i in board) {
            if (board[i])
                continue;

            if (!iterations)
                return i;

            iterations--;
        }
    })();

    Game.move(position);
});

$(document).on('click', '.board div', function(){

    Game.move($(this).index());

});
