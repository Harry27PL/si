function draw() {

    $('.graph2d').each(function(){

        var el = $(this);

        var data = [];

        var border = [];

        for (var k in el.data('data')) {
            var row = el.data('data')[k];
            border.push([parseFloat(row[0]), parseFloat(row[1])]);
        }

        data.push(border);

        for (var k in el.data('dots')) {
            var dots = [];


            for (var j in el.data('dots')[k]) {
                var row = el.data('dots')[k][j];
                dots.push([parseFloat(row[0]), parseFloat(row[1])]);
            }

            data.push({
                data: dots,
                points: {
                    show: true,
                }
            });
        }

		$.plot(el, data, {
            shadowSize: 0,
            grid: {
                markings: [
                    {color: '#000', lineWidth: 1, xaxis: {from: 0, to: 0}},
                    {color: '#000', lineWidth: 1, yaxis: {from: 0, to: 0}},
                ]
            }
        });

    });

};

function print_r(o)
{
    function f(o, p, s)
    {
        for(var x in o)
        {
            if ('object' == typeof o[x])
            {
                s += p + x + ' obiekt: \n';
                var pre = p + '\t';
                s = f(o[x], pre, s);
            }
            else
            {
                s += p + x + ' : ' + o[x] + '\n';
            }
        }
        return s;
    }
    return f(o, '', '');
}