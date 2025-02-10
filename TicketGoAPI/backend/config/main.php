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
        'request' => [
            'csrfParam' => '_csrf-backend',
            'enableCsrfValidation' => false,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser', ]
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'enableSession' => false,
            'loginUrl' => null,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/bilhete',
                    'pluralize' => true,
                    'extraPatterns' => [
                        'GET {evento_id}' => 'getevento',
                    ],

                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/carrinho',
                    'pluralize' => true,
                    'extraPatterns' => [
                        'GET {profile_id}' => 'getprofile',
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/favorito',
                    'pluralize' => true,
                    'extraPatterns' => [
                        'GET {profile_id}' => 'getprofile', //actionGetProfile
                        'POST /api/favorito/addfav' => 'addfav',
                        'DELETE {evento_id}' => 'deletefav', //actionDeleteFav
                    ],
                    
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/categoria',
                    'pluralize' => true,
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/evento',
                    'pluralize' => true,
                    'extraPatterns' => [
                        'GET,HEAD search' => 'search', // Adiciona a rota de pesquisa
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/fatura',
                    'pluralize' => true,
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/profile',
                    'pluralize' => true,
                    'extraPatterns' => [
                        'PUT {profile_id}' => 'updateprofile',
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/metodopagamento',
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/imagem',
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/linhacarrinho',
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/linhafatura',
                    'pluralize' => true,
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/local',
                    'pluralize' => true,
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/zona',
                    'pluralize' => true,
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/user',
                    'pluralize' => true,
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/auth',
                    'extraPatterns' => [
                        'POST /api/auth/login' => 'api/auth/login',  // Endpoint de login
                        'POST /api/auth/signup' => 'api/auth/signup',  // Endpoint signup
                    ]
                ],
            ],
        ],
    ],
    'params' => $params,
];
