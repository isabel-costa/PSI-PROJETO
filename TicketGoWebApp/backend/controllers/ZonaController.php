<?php

namespace backend\controllers;
use common\models\Zona;
use common\models\Local;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ZonaController implements the CRUD actions for Zona model.
 */
class ZonaController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Zona models.
     *
     * @return string
     */
    public function actionIndex()
    {

        $local_id = Yii::$app->request->get('local_id');

        if (!$local_id) {
            throw new \yii\web\BadRequestHttpException('O parâmetro local_id é obrigatório.');
        }

        $local = Local::FindOne($local_id);

        if(!$local){
            throw new \yii\web\NotFoundHttpException('Local não encontrado');
        }

            $dataProvider = new \yii\data\ActiveDataProvider([
                'query' => Zona::find()->where(['local_id'=>$local_id]),
            ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'local' => $local,
            'local_id' => $local_id,
        ]);
    }

    /**
     * Displays a single Zona model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Zona model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $local_id = Yii::$app->request->get('local_id');

        if (!$local_id) {
            throw new \yii\web\BadRequestHttpException('O parâmetro local_id é obrigatório.');
        }

        $local = Local::FindOne($local_id);
        if(!$local){
            throw new \yii\web\NotFoundHttpException('Local não encontrado');
        }

        $model = new Zona();
        $model->local_id = $local_id;

       if($model->load($this->request->post()) && $model->save()) {
           return $this->redirect(['index' , 'local_id' => $local_id]);
       }

        return $this->render('create', [
            'model' => $model,
            'local' => $local,
            'local_id' => $local_id,
        ]);
    }

    /**
     * Updates an existing Zona model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Zona model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Zona model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Zona the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Zona::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
