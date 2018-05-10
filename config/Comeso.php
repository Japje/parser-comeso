<?php

return [
    'parser' => [
        'name'          => 'Comeso',
        'enabled'       => true,
        'sender_map'    => [
            '/tracy@comeso.org/',
        ],
        'body_map'      => [
            //
        ],
    ],

    'feeds' => [
        'default' => [
            'class'     => 'COPYRIGHT_INFRINGEMENT',
            'type'      => 'ABUSE',
            'enabled'   => true,
            'fields'    => [
                'IP',
                'timestamp',
            ],
        ],
    ],
];
