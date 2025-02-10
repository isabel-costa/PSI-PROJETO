<?php
namespace backend\modules\api\controllers;

use backend\modules\api\components\QueryParamAuth;
use yii\rest\ActiveController;
use common\models\mqttPublisher;

class LinhaCarrinhoController extends ActiveController {
    public $modelClass = 'common\models\LinhaCarrinho';

    // Configura os comportamentos do controlador
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // Adiciona autenticação via query parameter
        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::class,
        ];

        return $behaviors;
    }
}