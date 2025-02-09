<?php

namespace backend\modules\api\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;
use backend\modules\api\components\QueryParamAuth;
use common\models\Evento;

class EventoController extends ActiveController 
{    
    public $modelClass = 'common\models\Evento';

    // configura os comportamentos do controlador
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        
        // adiciona autenticação via query parameter
        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::class,
        ];
        
        return $behaviors;
    }

    // método para verificar o acesso às ações
    public function checkAccess($action, $model = null, $params = [])
    {
        // bloqueia qualquer método que não seja GET
        if (in_array($action, ['create', 'update', 'delete'])) {
            throw new ForbiddenHttpException('You are not allowed to perform this action');
        }
    }
}
