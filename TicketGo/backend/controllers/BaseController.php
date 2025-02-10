<?php
namespace backend\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;

class BaseController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                        'roles' => ['?', 'registeredUser'],
                    ],
                    [
                        // organizadores so podem acessar aos eventos e ah pagina inicial
                        'allow' => true,
                        'roles' => ['organizer'],
                        'controllers' => ['evento', 'site'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
                // Callback para redirecionar o user
                'denyCallback' => function ($rule, $action) {
                    // Se ja tiver no login nao redireciona
                    if (\Yii::$app->requestedRoute === 'site/login') {
                        return;
                    }
                    \Yii::$app->session->setFlash('error', 'Você não tem permissão para acessar esta área.');
                    return \Yii::$app->response->redirect(['site/login']);
                },
            ],
        ];
    }
}