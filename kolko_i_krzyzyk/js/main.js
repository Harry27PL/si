FANN_ready = function() {

    var NN = FANN.create([2, 3, 1]);

};

$(document).ready(function(){
});

$(document).on('click', '.board div', function(){

    Game.move($(this).index());

});
