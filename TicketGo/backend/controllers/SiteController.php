<?php

namespace backend\controllers;

use common\models\LoginForm;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['createUsers', 'updateUsers', 'deleteUsers', 'createEvents', 'updateEvents', 'deleteEvents', 'createPaymentMethod', 'updatePaymentMethod', 'deletePaymentMethod', 'createCategories', 'updateCategories', 'deleteCategories', 'createPlaces', 'updatePlaces', 'deletePlaces', 'createZones', 'updateZones', 'deleteZones', 'viewReports'],
                'rules' => [
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions'=> ['createUsers', 'updateUsers', 'deleteUsers', 'createEvents', 'updateEvents', 'deleteEvents', 'createPaymentMethod', 'updatePaymentMethod', 'deletePaymentMethod', 'createCategories', 'updateCategories', 'deleteCategories', 'createPlaces', 'updatePlaces', 'deletePlaces', 'createZones', 'updateZones', 'deleteZones'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                    [
                        'actions' => ['createEvents', 'updateEvents', 'deleteEvents'],
                        'allow' => true,
                        'roles' => ['organizer'],
                    ],
                    [
                        'actions' => ['viewReports'],
                        'allow' => true,
                        'roles' => ['partner'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['index'],
                        'roles' => ['admin', 'organizer', 'partner'],
                        'denyCallback' => function ($rule, $action) {
                            Yii::$app->session->setFlash('error', 'Não tem permissão para o backend.');
                            return $this->redirect(['site/login']); // Redireciona para o login em caso de negação
                        },
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }



    /**
     * Login action.
     *
     * @return string|Response
     */



    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            // Usuário já está logado, redireciona para o índice
            return $this->goHome();  // Isso vai levar para a página principal (index)
        }

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack(); // Redireciona para a página anterior
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }


    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->redirect(['site/login']);
    }
}
