<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/tweets/([^/]++)(*:23)'
                .'|/_error/(\\d+)(?:\\.([^/]++))?(*:58)'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        23 => [[['_route' => 'app_infrastructure_tweet_http_tweetconverter_index', '_controller' => 'App\\Infrastructure\\Tweet\\Http\\TweetConverterController::index'], ['userName'], ['GET' => 0], null, false, true, null]],
        58 => [
            [['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
