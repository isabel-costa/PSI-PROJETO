<?php
namespace backend\modules\api\controllers;

use Yii;
use common\models\Profile;
use backend\modules\api\components\QueryParamAuth;
use yii\rest\ActiveController;

class ProfileController extends ActiveController {
    public $modelClass = 'common\models\Profile';

    // Método para verificar o acesso às ações
    public function checkAccess($action, $model = null, $params = [])
    {
        // Bloqueia qualquer método que não seja GET e PUT
        if (in_array($action, ['create', 'delete'])) {
            throw new \yii\web\ForbiddenHttpException('You are not allowed to perform this action');
        }
    }

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

    // Método para atualizar o perfil
    public function actionUpdateProfile($id)
    {
        // Encontra o perfil pelo ID
        $profile = $this->findModel($id);

        // Carrega os dados do pedido e guarda o perfil
        if ($profile->load(Yii::$app->request->post(), '') && $profile->save()) {
            return $profile;
        }

        // Retorna mensagem de erro se a atualização falhar
        return ['message' => 'Failed to update profile details', 'errors' => $profile->errors];
    }

}