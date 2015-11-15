function draw() {

    $('.graph3d').each(function(){

        var el = $(this);
        var data = new vis.DataSet();

        for (var k in el.data('data')) {
            var row = el.data('data')[k];
            data.add({
                x: parseFloat(row[0]),
                y: parseFloat(row[1]),
                z: parseFloat(row[2])
            });
        }

        var options = {
            width: "300px",
            height: "200px",
            style: "surface",
            showPerspective: true,
            showGrid: true,
            showShadow: false,
            keepAspectRatio: true,
            verticalRatio: 0.5
        };

        var graph3d = new vis.Graph3d(el[0], data, options);

    });

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