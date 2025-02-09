<?php

namespace backend\modules\api\controllers;

use Yii;
use backend\modules\api\components\QueryParamAuth;
use common\models\MetodoPagamento;
use common\models\User;
use yii\rest\ActiveController;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;

class MetodoPagamentoController extends ActiveController 
{
    public $modelClass = 'common\models\MetodoPagamento';


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
            throw new ForbiddenHttpException('Você não tem permissão para realizar esta ação.');
        }
    }


    // método para validar autenticação via query params
    public function verifyCredentials($profile_id, $token)
    {
        $user = User::find()->where(['id' => $profile_id])->andWhere(['auth_key' => $token])->one();
        
        if (!$user) {
            throw new UnauthorizedHttpException('Token de autenticação inválido ou usuário não encontrado.');
        }
    }


    
}
