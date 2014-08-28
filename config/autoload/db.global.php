<?php
return array(
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter' => function ($sm) {
                    return new Zend\Db\Adapter\Adapter(array(
                        'driver' => 'PDO_SQLITE',
                        'database' => 'sqlite:../../data/db/deploy.sqlite'
                    ));
                }
        ),
        'aliases' => array(
          'db' => 'Zend\Db\Adapter\Adapter'
        ),
    ),
);
