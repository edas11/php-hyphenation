<?php
// route => Edvardas\Hyphenation\Hyphenator\Controller\HelperHttpController method name
return $routes = [
    'get' => [
        'hyphenation/' => 'getPage',
        'hyphenation/show/words' => 'getWordsPage',
        'hyphenation/show/patterns' => 'getPatternsPage',
        'hyphenation/hyphenated-words' => 'hyphenateWordsPage',
        'hyphenation/change-hyphenation' => 'changeHyphenationPage',
        'api/hyphenation/words/'  => 'getWords'
    ],
    'post' => [
        'api/hyphenation/words/'  => 'postWords'
    ],
    'put' => [
        'api/hyphenation/words/{param}'  => 'putWords'
    ],
    'delete' => [
        'api/hyphenation/words/{param}'  => 'deleteWords'
    ]
];