<?php

namespace backend\modules\api\controllers;

use Yii;
use backend\modules\api\components\QueryParamAuth;
use common\models\Profile;
use common\models\User;
use yii\rest\ActiveController;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;

class ProfileController extends ActiveController
{
    public $modelClass = 'common\models\Profile';


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
        // bloqueia qualquer método que não seja GET ou POST
        if (in_array($action, ['create', 'delete'])) {
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


    // método para obter o profile correspondente ao token e ao profile_id
    public function actionGetProfile()
    {
        // obtém o token e o profile_id dos query params
        $token = Yii::$app->request->get('token');
        $profile_id = Yii::$app->request->get('profile_id');

        // verifica se o token e o profile_id são válidos
        $this->verifyCredentials($token, $profile_id);
        
        // mostra o perfil associado ao ID enviado na query param
        $profile = Profile::findOne(['user_id' => $profile_id]);
        
        if ($profile) {
            return [
                'message' => "O seguinte perfil {$profile_id} foi encontrado:",
                'data' => $profile,
            ];
        }

        return [
            'message' => "Nenhum perfil encontrado para este ID associado ao utilizador."
        ];
    }


   // método para atualizar o profile correspondente ao token e ao profile_id
   public function actionUpdateProfile()
   {
        // obtém o token e o profile_id dos query params
        $token = Yii::$app->request->get('token');
        $profile_id = Yii::$app->request->get('profile_id');

        // verifica se o token e o profile_id são válidos
        $this->verifyCredentials($token, $profile_id);

            // mostra o perfil associado ao ID enviado na query param
        $profile = Profile::findOne(['user_id' => $profile_id]);

        if (!$profile) {
            return [
                'message' => "O perfil não foi encontrado ou não pertence ao utilizador autenticado."
            ];
        }

        $request = Yii::$app->request;

        $profile->nome = $request->post('nome', $profile->nome);
        $profile->datanascimento = $request->post('datanascimento', $profile->datanascimento);
        $profile->nif = $request->post('nif', $profile->nif);
        $profile->morada = $request->post('morada', $profile->morada);

        if ($profile->save()) {
            return [
                'message' => "O perfil {$profile_id} foi atualizado com sucesso:",
                'data' => $profile
            ];
        }

        return [
            'message' => "Falha ao atualizar os detalhes do perfil", 
            'errors' => $profile->errors
        ];
   }
}
