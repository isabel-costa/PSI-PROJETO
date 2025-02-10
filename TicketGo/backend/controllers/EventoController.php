<?php

namespace backend\controllers;


use common\models\Evento;
use common\models\Categoria;
use common\models\Local;
use common\models\Imagem;
use Yii;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\web\ForbiddenHttpException;
use yii\web\UploadedFile;

class EventoController extends BaseController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        return $behaviors;
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

        // Categorias bd
        $categorias = Categoria::find()->all();
        $locais = Local::find()->all(); // Buscar os locais


        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            //Guardar o Modelo na BD
            if ($model->save()) {
                //logica imagem
                $model->imagem_file = UploadedFile::getInstance($model, 'imagem_file');
                if($model->imagem_file){
                    $filename = $model->imagem_file->baseName . '.' . $model->imagem_file->extension;
                    $path = Yii::getAlias('@frontend/web/uploads/') . $filename;
                    if($model->imagem_file->saveAs($path)){
                        // tabel imagens bd
                        $imagem = new Imagem();
                        $imagem->nome = $filename;
                        $imagem->evento_id = $model->id;
                        $imagem->save();
                    }
                }
                Yii::$app->session->setFlash('success', 'Evento criado com sucesso!');
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('error', 'Erro ao criar o evento.');
            }
        }

        return $this->render('create', [
            'model' => $model,
            'categorias' => $categorias,
            'locais' => $locais,
        ]);
    }

    public function actionUpdate($id)
    {
        // Verifica se o utilizador tem permissão para editar eventos
        if (!Yii::$app->user->can('updateEvents')) {
            Yii::$app->session->setFlash('error', 'Não tem permissão para editar eventos.');
            return $this->redirect(['index']);
        }

        $model = $this->findModel($id);

        // Categorias e locais
        $categorias = Categoria::find()->all();
        $locais = Local::find()->all();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            // Atualizar o evento
            if ($model->save()) {
                // Lógica para upload da nova imagem
                $model->imagem_file = UploadedFile::getInstance($model, 'imagem_file');
                if ($model->imagem_file) {
                    $filename = uniqid() . '.' . $model->imagem_file->extension;
                    $path = Yii::getAlias('@frontend/web/uploads/') . $filename;

                    if ($model->imagem_file->saveAs($path)) {
                        // Substituir ou criar registro na tabela de imagens
                        $imagem = Imagem::findOne(['evento_id' => $model->id]) ?? new Imagem();
                        $imagem->nome = $filename;
                        $imagem->evento_id = $model->id;
                        $imagem->save(false);
                    }
                }

                Yii::$app->session->setFlash('success', 'Evento atualizado com sucesso!');
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('error', 'Erro ao atualizar o evento.');
            }
        }

        return $this->render('update', [
            'model' => $model,
            'categorias' => $categorias,
            'locais' => $locais,
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
    }

        //Exibe todos os eventos

        public function actionViewEventDetails($id)
        {

            $event = Evento::findOne($id);

            //Verifica se o evento foi encontrado
            if (!$event) {
                Yii::$app->session->setFlash('error', 'Evento não encontrado.');

                return $this->redirect(['index']);
            }

            return $this->render('view', ['model' => $event]);
        }
}