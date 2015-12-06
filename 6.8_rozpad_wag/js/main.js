function draw() {

    $('.graphNetwork').each(function(){

        var el = $(this);

        var nodes = new vis.DataSet(el.data('data')['nodes']);

        var edges = new vis.DataSet(el.data('data')['edges']);

        var network = new vis.Network(el[0], {
            nodes: nodes,
            edges: edges
        }, {
            layout: {
                hierarchical: {
                    enabled: true,
                    levelSeparation: 100,
                    direction: 'UD',
                    sortMethod: 'directed'
                }
            },
            nodes: {
                shape: 'box'
            }
        });

    });

    $('.graphError').each(function(){

        var data = $(this).data('data');

        var chartData = ['błąd'];

        for (var k in data) {
            chartData.push( data[k]);
        }

        var chart = c3.generate({
            bindto: $(this)[0],
            data: {
              columns: [
                chartData,
              ]
            },
            line: {
                connectNull: true
            }
        });

    });

};

function drawData()
{
    $('.graph2d').each(function(){

        var allData = $(this).data('data');
        var allDataNormalized = $(this).data('data-normalized');

        var chartData1 = ['początkowe'];
        var chartData2 = ['znormalizowane'];

        for (var k in allData) {
            chartData1.push( allData[k].data[0] );
            chartData2.push( allDataNormalized[k].data[0] );
        }

        var chart = c3.generate({
            bindto: $(this)[0],
            data: {
              columns: [
                chartData1,
                chartData2
              ]
            },
            grid: {
                x: {
                    show: true
                },
                y: {
                    show: true
                }
            }
        });

    });
}

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