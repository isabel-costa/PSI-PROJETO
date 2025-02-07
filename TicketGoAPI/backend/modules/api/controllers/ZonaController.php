<?php

namespace backend\modules\api\controllers;

use common\models\Zona;
use yii\rest\ActiveController;
use yii\web\ForbiddenHttpException;
use common\models\mqttPublisher;

class ZonaController extends ActiveController {
    public $modelClass = 'common\models\Zona';

    // Método para verificar o acesso às ações
    public function checkAccess($action, $model = null, $params = [])
    {
        // Bloqueia qualquer método que não seja GET
        if (in_array($action, ['create', 'update', 'delete'])) {
            throw new \yii\web\ForbiddenHttpException('You are not allowed to perform this action');
        }
    }
}