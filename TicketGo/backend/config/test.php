<?php
return yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/../../common/config/main.php',       // Configuração global
    require __DIR__ . '/../../common/config/main-local.php', // Configuração local global
    require __DIR__ . '/main.php',       // Configuração específica do backend
    require __DIR__ . '/main-local.php', // Configuração local do backend
    [
        'components' => [
            'db' => [
                'class' => 'yii\db\Connection',
                'dsn' => 'mysql:host=localhost;dbname=ticketgo_tests', // ⚠️ Banco de dados de testes
                'username' => 'root',
                'password' => '',
                'charset' => 'utf8',
                'tablePrefix' => '',
                'enableSchemaCache' => false, // Desativa cache para testes
            ],
            'mailer' => [
                'useFileTransport' => true, // Evita envio real de e-mails nos testes
            ],
        ],
    ]
);
