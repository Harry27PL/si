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
//                hierarchical: {
//                    enabled: true,
//                    levelSeparation: 150,
//                    direction: 'UD',
//                    sortMethod: 'directed'
//                }
            },
            nodes: {
//                shape: 'box'
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