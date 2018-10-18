<?php
// route => Edvardas\Hyphenation\Hyphenator\Controller\WebControllers class name
return $routes = [
    'get' => [
        'hyphenation/' => 'PageMainController',
        'hyphenation/show/words' => 'PageGetWordsController',
        'hyphenation/show/patterns' => 'PageGetPatternsController',
        'hyphenation/hyphenated-words' => 'PageHyphenateWordsController',
        'hyphenation/change-hyphenation' => 'PageChangeHyphenationController',
        'api/hyphenation/words/'  => 'ApiGetWordsController'
    ],
    'post' => [
        'hyphenation/hyphenated-words' => 'PageHyphenateWordsController',
        'api/hyphenation/words/'  => 'ApiPostWordsController'
    ],
    'put' => [
        'api/hyphenation/words/{param}'  => 'ApiPutWordsController'
    ],
    'delete' => [
        'api/hyphenation/words/{param}'  => 'ApiDeleteWordsController'
    ]
];