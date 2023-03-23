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
                .'|/(.*)/placeholder(*:24)'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        24 => [
            [['_route' => 'placeholder', '_controller' => 'BernhardWebstudio\\PlaceholderBundle\\Controller\\PlaceholderProviderController::placeholderAction'], ['imagePath'], null, null, false, false, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
