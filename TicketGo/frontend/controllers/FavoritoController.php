<?php

namespace frontend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use common\models\Evento;
use common\models\Favorito;

class FavoritoController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'toggle'], // Adicione as ações necessárias
                        'roles' => ['@'], // Apenas usuários autenticados podem acessar
                    ],
                    [
                        'allow' => false,
                        'actions' => ['index', 'toggle'],
                        'roles' => ['?'], // Usuários não autenticados são negados
                    ],
                ],
            ],
        ];
    }


    // Ação para adicionar/remover do favorito
    public function actionToggle($eventoId)
    {
        // Verifique se o usuário está logado
        if (Yii::$app->user->isGuest) {
            Yii::$app->session->setFlash('error', 'É necessário fazer login para adicionar um evento aos favoritos.');
            return $this->redirect(['site/login']);
        }

        // Encontre o evento
        $evento = Evento::findOne($eventoId);
        if ($evento === null) {
            throw new NotFoundHttpException('Evento não encontrado.');
        }

        // Verifique se o usuário tem permissão para adicionar ou remover dos favoritos
        if (Yii::$app->user->can('addToFavorites')) {
            // Se o evento já está nos favoritos, remova-o
            $profile = Yii::$app->user->identity->profile;
            $favorito = Favorito::findOne(['profile_id' => $profile->id, 'evento_id' => $evento->id]);

            if ($favorito) {
                // Remover do favorito
                $favorito->delete();
                Yii::$app->session->setFlash('success', 'Evento removido dos favoritos.');
            } else {
                // Adicionar aos favoritos
                $novoFavorito = new Favorito();
                $novoFavorito->profile_id = $profile->id;
                $novoFavorito->evento_id = $evento->id;
                $novoFavorito->save();
                Yii::$app->session->setFlash('success', 'Evento adicionado aos favoritos.');
            }

            return $this->redirect(['evento/product-detail', 'id' => $evento->id]);
        } else {
            throw new \yii\web\ForbiddenHttpException('Você não tem permissão para adicionar ou remover eventos dos favoritos.');
        }

    }

    // Ação para listar todos os favoritos do utilizador
    public function actionIndex()
    {
        // Verifique se o utilizador está autenticado
        if (Yii::$app->user->isGuest) {
            Yii::$app->session->setFlash('error', 'É necessário fazer login para aceder aos favoritos.');
            return $this->redirect(['site/login']);
        }

        //Obter todos os favoritos do utilizador autenticado
        $profile = Yii::$app->user->identity->profile;
        $favoritos = Favorito::find()
            ->where(['profile_id' => $profile->id])
            ->all();

        return $this->render('//site/favorites', [
            'favoritos' => $favoritos,
        ]);
    }
}
