<?php

namespace backend\modules\api\controllers;

use Yii;
use common\models\Bilhete;
use common\models\Evento;
use yii\rest\ActiveController;
use backend\modules\api\components\QueryParamAuth;
use yii\web\ForbiddenHttpException;
use common\models\mqttPublisher;

class BilheteController extends ActiveController
{
    public $modelClass = 'common\models\Bilhete';

    // Método para verificar o acesso às ações
    public function checkAccess($action, $model = null, $params = [])
    {
        // Bloqueia qualquer método que não seja GET
        if (in_array($action, ['create', 'update', 'delete'])) {
            throw new \yii\web\ForbiddenHttpException('You are not allowed to perform this action');
        }
    }

    // Método para obter bilhetes de um evento específico
    public function actionGetEvento($evento_id)
    {
        // Encontra todos os bilhetes associados ao evento
        $bilhetes = $this->modelClass::find()->where(['evento_id' => $evento_id])->all();

        // Verifica se existem bilhetes para o evento
        if ($bilhetes) {
            return $bilhetes;
        } else {
            return [
                'success' => false,
                'message' => 'Este evento não tem bilhetes associados.',
            ];
        }
    }
}