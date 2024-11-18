<?php

namespace backend\controllers;

use common\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;


class UserController extends \yii\web\Controller
{
    public function behaviors()
{
    return [
        'access' => [
            'class' => AccessControl::class,
            'only' => ['create', 'update', 'delete'],
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['admin'],
                ]
            ],
        ],
    ];
}
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => User::find(),
            'pagination' => [
                'pageSize' => 10, // Define o número de itens por página
            ],
        ]);

        // Renderiza a view index e passa os eventos para a view
        return $this->render('index', ['dataProvider' => $dataProvider]);
    }
    public function actionCreate()
    {
        //Verifica se o utilizador tem permissão para criar Users
        if (!Yii::$app->user->can('createUsers')) {
            Yii::$app->session->setFlash('error', 'Não tem permissão para criar Users.');
            return $this->redirect(['index']);
        }
        $model = new User();


        if ($model->load(Yii::$app->request->post())) {
            // O hash da senha será gerado automaticamente no modelo antes de salvar
            if ($model->save(false)) {
                $auth = Yii::$app->authManager;
                $role = $auth->getRole($model->role);
                if($role) {
                    $auth->assign($role, $model->id);

                }
                Yii::$app->session->setFlash('success', 'user criado com sucesso.');
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('error', 'Erro ao criar USer.');
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);

    }

    public function actionUpdate($id)
    {
        //Verifica se o utilizador tem permissão para editar eventos
        if (!Yii::$app->user->can('updateUsers')) {
            Yii::$app->session->setFlash('error', 'Não tem permissão para editar Users.');
            return $this->redirect(['index']);
        }

        //Procura o User pelo ID
        $model = $this->findModel($id);

        //Se o User foi atualizado e o formulário foi enviado com sucesso faz:
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            //Atualiza o evento na bd
            if ($model->save(false)) {
                $auth = Yii::$app->authManager;
                $auth->revokeAll($model->id);
                $role = $auth->getRole($model->role);
                if($role) {
                    $auth->assign($role, $model->id);
                }
                Yii::$app->session->setFlash('success', 'User atualizado com sucesso!');
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('error', 'Erro ao atualizar o User.');
            }
        }

        //Se não houve um post ou houve falha na validação, renderiza o formulário de edição
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $event = User::findOne($id);

        if (!$event || !Yii::$app->user->can('deleteUsers', ['userId' => $id])) {
            throw new ForbiddenHttpException('Não tem permissão para eliminar Users.');
        }

        $event->delete();
        return $this->redirect(['index']);
    }
    protected function findModel($id)
    {
        if (($model = User::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
