<?php
namespace backend\modules\api\controllers;

use Yii;
use common\models\Profile;
use backend\modules\api\components\QueryParamAuth;
use yii\rest\ActiveController;

class ProfileController extends ActiveController {
    public $modelClass = 'common\models\Profile';

    //Configura os comportamentos do controlador
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        //Adiciona autenticação via query parameter
        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::class,
        ];

        return $behaviors;
    }

    //Método para atualizar o perfil
    public function actionUpdateProfile($id)
    {
        //Encontra o perfil pelo ID
        $profile = $this->findModel($id);

        //Carrega os dados do pedido e guarda o perfil
        if ($profile->load(Yii::$app->request->post(), '') && $profile->save()) {
            return $profile;
        }

        //Retorna uma mensagem de erro se a atualização falhar
        return ['message' => 'Failed to update profile details', 'errors' => $profile->errors];
    }

    public function actionGetProfileByNome($nome)
    {
        // Encontra o perfil pelo username
        $profile = Profile::find()
            ->where(['nome' => $nome])
            ->one();

        if ($profile) {
            return [
                'id' => $profile->id,
                'nome' => $profile->nome,
                'email' => $profile->email,
            ];
        }

        // Se não encontrar, retorna erro 404
        Yii::$app->response->statusCode = 404;
        return [
            'message' => 'Profile not found',
            'error_code' => 'PROFILE_NOT_FOUND'
        ];
    }
}