FANN_ready = function() {

    var XOR_DATA = [
        [[-1, -1], [-1]],
        [[ 1,  1], [-1]],
        [[-1,  1], [ 1]],
        [[ 1, -1], [ 1]]
    ];

    NN = FANN.create([2, 3, 1]);

    // domyślnie 0.5
    // A large steepness is well suited for classification problems while a small steepness is well suited for function approximation.
    NN.set_activation_steepness_hidden(1);
    NN.set_activation_steepness_output(1);
    // symmetric oznacza od -1 do 1
    NN.set_activation_function_hidden(FANN.SIGMOID_SYMMETRIC);
    NN.set_activation_function_output(FANN.SIGMOID_SYMMETRIC);
    // mean square error albo bit (dużo razy będzie fail to stop)
    NN.set_train_stop_function(FANN.STOPFUNC_BIT);
    NN.set_bit_fail_limit(0.01);
    NN.set_training_algorithm(FANN.TRAIN_RPROP);

    var data = FANN.createTraining(XOR_DATA);

    NN.init_weights(data);
    NN.train_on_data(data, 1000, 10, 0.01);

    console.log(
        " -1 , -1 => " + NN.run([-1, -1])[0], '\n',
        "-1 ,  1 => " + NN.run([-1, 1])[0], '\n',
        " 1 , -1 => " + NN.run([1, -1])[0], '\n',
        " 1 ,  1 => " + NN.run([1, 1])[0], '\n'
    );

}