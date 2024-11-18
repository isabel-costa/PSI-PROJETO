<?php

namespace backend\controllers;

use common\models\Evento;
use Yii;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\web\ForbiddenHttpException;

class EventoController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['createEvents', 'updateEvents', 'deleteEvents'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin', 'organizer'],
                    ]
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Evento::find(),
            'pagination' => [
                'pageSize' => 10, // Define o número de itens por página
            ],
        ]);

        // Renderiza a view index e passa os eventos para a view
        return $this->render('index', ['dataProvider' => $dataProvider]);

    }

    public function actionCreate()
    {
        //Verifica se o utilizador tem permissão para criar eventos
        if (!Yii::$app->user->can('createEvents')) {
            Yii::$app->session->setFlash('error', 'Não tem permissão para criar eventos.');
            return $this->redirect(['index']);
        }
        $model = new Evento();


        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            // Salvar o modelo no banco de dados
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Evento criado com sucesso!');
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('error', 'Erro ao criar o evento.');
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        // Verifica se o utilizador tem permissão para editar eventos
        if (!Yii::$app->user->can('updateEvents')) {
            Yii::$app->session->setFlash('error', 'Não tem permissão para editar eventos.');
            return $this->redirect(['index']);
        }

        // Procura o evento pelo ID
        $model = $this->findModel($id);

        // Se o evento foi atualizado e o formulário foi enviado com sucesso, faz:
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            // Atualiza o evento na BD
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Evento atualizado com sucesso!');
                return $this->redirect(['index']);  // Corrigido: adicionando o parêntese de fechamento
            } else {
                Yii::$app->session->setFlash('error', 'Erro ao atualizar o evento.');
            }
        }

        // Se não houve um post ou houve falha na validação, renderiza o formulário de edição
        return $this->render('update', [
            'model' => $model,
        ]);
    }


    public function actionDelete($id)
    {
        $event = Evento::findOne($id);

        if (!$event || !Yii::$app->user->can('deleteEvents', ['eventId' => $id])) {
            throw new ForbiddenHttpException('Não tem permissão para eliminar eventos.');
        }

        $event->delete();
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Evento::findOne($id)) !== null) {
            return $model;
        } else {
            throw new \yii\web\NotFoundHttpException('A página solicitada não existe.');
        }

        //Exibe todos os eventos

        /*public function actionViewEventDetails($id)
        {

            $event = Evento::findOne($id);

            //Verifica se o evento foi encontrado
            if (!$event) {
                Yii::$app->session->setFlash('error', 'Evento não encontrado.');

                return $this->redirect(['index']);
            }

            return $this->render('view', ['event' => $event]);
        }*/
    }
}