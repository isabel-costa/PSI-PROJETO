<?php

namespace backend\modules\api\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;
use backend\modules\api\components\QueryParamAuth;
use common\models\User;

class UserController extends ActiveController
{
    public $modelClass = 'common\models\User';

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
}
