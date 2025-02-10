<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'api' => [
            'class' => 'backend\modules\api\ModuleAPI',
        ],
    ],
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'request' => [
            'csrfParam' => '_csrf-backend',
            'enableCsrfValidation' => false,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
       'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true, // permite login automático baseado em cookies
            'enableSession' => false,
            'loginUrl' => null,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],

        ],
        'session' => [
            'name' => 'advanced-backend', // define o nome do cookie de sessão
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false, // remove index.php das URLs
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/bilhete',
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET evento/<evento_id>' => 'getbilhetesevento',    // mostra todos os bilhetes de um evento específico
                        'GET zona/<zona_id>' => 'getbilheteszona',          // mostra todos os bilhetes de uma zona específica
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/carrinho',
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET {profile_id}' => 'getcarrinho',                // mostra os itens do carrinho a partir das query params
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/favorito',
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET {profile_id}' => 'getfavorito',                // mostra os favoritos a partir das query params
                        'POST {evento_id}' => 'addfavorito',                // adiciona um favorito
                        'PUT {evento_id}' => 'updatefavorito',              // atualiza um favorito
                        'DELETE {evento_id}' => 'deletefavorito',           // remove um favorito
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/categoria',
                    'pluralize' => false,
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/evento',
                    'pluralize' => false,
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/fatura',
                    'pluralize' => false,
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/profile',
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET {profile_id}' => 'getprofile',                 // mostra o perfil a partir das query params
                        'PUT {profile_id}' => 'updateprofile',              // atualiza o perfil a partir das query params
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/metodopagamento',
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET {metodopagamento_id}' => 'getmetodopagamento',     // mostra todos os métodos de pagamento
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/linhacarrinho',
                    'pluralize' => false,
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/linhafatura',
                    'pluralize' => false,
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/local',
                    'pluralize' => false,
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/zona',
                    'pluralize' => false,
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/user',
                    'pluralize' => false,
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/auth',
                    'pluralize' => false,
                    'extraPatterns' => [
                        'POST login' => 'login',                            // endpoint de login
                        'POST signup' => 'signup',                          // endpoint de signup
                    ]
                ],
            ],
        ],
    ],
    'params' => $params,
];
