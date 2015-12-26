Game = (function(){

    /** kto następny */
    var player = 1;
    var size = 3;
    var board;

    function start()
    {
        board = [];

        for (var i = 0; i < size*size; i++)
            board.push(0);

        resetView();
    }

    $(document).ready(function(){
        start();
    });

    function move(position)
    {
        if (board[position])
            return;

        board[position] = player;

        //$(document).trigger('game.move', [player]);

        if (isEnd()) {
            end();

            return;
        }

        player = player == 1
            ? -1
            : 1;

        resetView();
    }

    function isEnd()
    {
        if (existsCompletedRow())
            return true;

        return board.every(function(value) {
            return value;
        });
    }

    function end()
    {
        if (existsCompletedRow())
            alert('wygrywa '+player);
        else
            alert('remis');

        start();
    }

    function existsCompletedRow()
    {
        var boardMatrix = getBoardAsMatrix();

        var boardMatrixT = boardMatrix[0].map(function(col, i) {
            return boardMatrix.map(function(row) {
                return row[i];
            });
        });

        function existsIdenticalRows(matrix)
        {
            return matrix.some(function(row){
                return row.every(function(value) {
                    return value && value == row[0];
                });
            });
        };

        function existsIdenticalDiagonal(matrix)
        {
            for (var i = 0; i < size; i++)
                if (!matrix[i][i])
                    return false;

            for (var i = 0; i < size; i++)
                if (matrix[0][0] != matrix[i][i])
                    return false;

            return true;
        }

        return existsIdenticalRows(boardMatrix)
            || existsIdenticalRows(boardMatrixT)
            || existsIdenticalDiagonal(boardMatrix)
            || existsIdenticalDiagonal(boardMatrixT);
    }

    function getBoardAsMatrix(){
        var i = 0;
        var newBoard = [];

        _.each(board, function(v) {
            if (i % size == 0) {
                newBoard.push([]);
            }

            newBoard[newBoard.length-1].push(v);

            i++;
        });

        return newBoard;
    }

    function resetView()
    {
        var boardView = $('.board');

        boardView.find('div').each(function(i){
            $(this).attr('data-status', board[i]);
        });

        $('.player').html(
            player == 1
                ? '&times;'
                : 'O'
        );
    }

    return {
        start:  start,
        move:   move,
        board:  board
    };
})();

