(function() {

    'use strict';
    let a = [
        'shared_variablesScript',
        'myScript',
        'technician_manager_administrativeScript',
        'commercialScript',
        'loginScript',
    ];
    let i;
    let s = [];
    for (i = 0; i < a.length; i += 1) {
        s = s.concat(['<script src="/js/my_javascript/', a[i], '.js"></script>']);
    }
    document.write(s.join(''));
}());
