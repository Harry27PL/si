function draw() {

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