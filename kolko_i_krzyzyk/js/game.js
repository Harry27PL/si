Game = (function(){

    /** kto następny */
    var player = 1;
    var size = 3;
    var board;

    function start()
    {
        board = [];

        player = 1;

        for (var i = 0; i < size*size; i++)
            board.push(0);

        resetViewBoard();
        resetViewPlayer();

        $(document).trigger('game.start');
    }

    $(document).ready(function(){
        start();
    });

    function move(position)
    {
        if (board[position])
            return;

        board[position] = player;
        resetViewBoard();

        if (isEnd()) {
            return end();
        }

        var previousPlayer = player;

        player = player == 1
            ? -1
            : 1;

        resetViewPlayer();

        $(document).trigger('game.move', [previousPlayer]);

        return null;
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
        var tie = !existsCompletedRow();

//        if (!tie)
//            alert('wygrywa '+player);
//        else
//            alert('remis');

        var winner = !tie
            ? player
            : 0;

        $(document).trigger('game.end', [winner]);

        start();

        return winner;
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

        function existsIdenticalDiagonal(matrix, rotated)
        {
            function index(i) {
                if (rotated)
                    return size - 1 - i;

                return i;
            }

            for (var i = 0; i < size; i++)
                if (!matrix[i][index(i)])
                    return false;

            for (var i = 0; i < size; i++)
                if (matrix[0][index(0)] != matrix[i][index(i)])
                    return false;

            return true;
        }

        return existsIdenticalRows(boardMatrix)
            || existsIdenticalRows(boardMatrixT)
            || existsIdenticalDiagonal(boardMatrix)
            || existsIdenticalDiagonal(boardMatrix, true);
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

    function resetViewBoard()
    {
        var boardView = $('.board');

        boardView.find('div').each(function(i){
            $(this).attr('data-status', board[i]);
        });
    }

    function resetViewPlayer()
    {
        $('.player').html(
            player == 1
                ? '&times;'
                : 'O'
        );
    }

    return {
        start:      start,
        move:       move,
        getBoard:   function() {
            return board;
        },
        existsCompletedRow: existsCompletedRow
    };
})();

