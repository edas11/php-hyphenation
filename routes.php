<?php
return $routes = [
    'get' => [
        'api/hyphenation/words/'  => 'ApiGetWordsController',
        'api/hyphenation/patterns/'  => 'ApiGetPatternsController'
    ],
    'post' => [
        'api/hyphenation/words/'  => 'ApiPostWordsController'
    ],
    'put' => [
        'api/hyphenation/words/{param}'  => 'ApiPutWordsController'
    ],
    'delete' => [
        'api/hyphenation/words/{param}'  => 'ApiDeleteWordsController'
    ],
    'options' => [
        '*' => 'ApiOptionsController'
    ],
    'handlerPrefix' => 'Edvardas\Hyphenation\Hyphenator\Controller\WebControllers\\'
];