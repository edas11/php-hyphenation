<?php
// route => Edvardas\Hyphenation\Hyphenator\Controller\HelperHttpController method name
return $routes = [
    'get' => [
        'hyphenation/words/'  => 'getWords'
    ],
    'post' => [
        'hyphenation/words/'  => 'postWords'
    ],
    'put' => [
        'hyphenation/words/{param}'  => 'putWords'
    ],
    'delete' => [
        'hyphenation/words/{param}'  => 'deleteWords'
    ]
];