<?php
/**
 * Continuous Php Deploy Agent Configuration
 *
 * If you have a ./config/autoload/ directory set up for your project, you can
 * drop this config file in it and change the values as you wish.
 */
return [
    'bsb_flysystem' =>
    [
        'adapters' =>
        [
            'packages' =>
            [
                'type' => 'local',
                'options' =>
                [
                    'root' => './data/packages'
                ]
            ]
        ]
    ],
    
    'agent' => [
        'package_retention' => 2, // package retention per application
        'package_storage_path' => './data/packages', // flysystem adapter key to store package history
        /* hexadecimal key to crypt/decrypt sensitive data */
        'hash-key' => 'f01ee0962998007d40c7ce32bfec773028785cadfa0064a467662cb87171012c'
    ]
];