<?php
return yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/main.php',
    [
        'components' => [
            'db' => [
                'class' => 'yii\db\Connection',
                'dsn' => 'mysql:host=127.0.0.1;ticketgo',
                'username' => 'root',  // Altere se necessário
                'password' => '',      // Altere se necessário
                'charset' => 'utf8',
            ],
        ],
    ]
);
