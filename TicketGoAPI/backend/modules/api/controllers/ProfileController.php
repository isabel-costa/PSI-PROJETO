<?php

namespace backend\modules\api\controllers;

use backend\modules\api\components\QueryParamAuth;
use common\models\Profile;
use common\models\User;
use Yii;
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
        
        // adiciona autenticação via Query Params

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


    // método para validar a autenticação do utilizador enviado nas Query Params
    public function verifyCredentials($token, $profile_id)
    {
        // procura o utilizador enviado através das Query Params na tabela User
        $user = User::find()->where(['id' => $profile_id])->andWhere(['auth_key' => $token])->one();
        
        // caso o token e o profile_id não coincidam
        if (!$user) {
            throw new UnauthorizedHttpException('Token ou ID inválidos.');
        }
    }


    // método para obter o profile do utilizador enviado nas Query Params
    public function actionGetProfile()
    {
        // obtém o token e o profile_id
        $token = Yii::$app->request->get('token');
        $profile_id = Yii::$app->request->get('profile_id');

        // verifica se o token e o profile_id são válidos
        $this->verifyCredentials($token, $profile_id);
        
        // busca o profile associado ao profile_id
        $profile = Profile::findOne(['user_id' => $profile_id]);
        if (!$profile) {
            throw new NotFoundHttpException('Nenhum perfil foi encontrado para este utilizador.');
        }

        // retorna os detalhes do profile encontrado
        return [
            'message' => "Perfil do utilizador {$profile_id}:",
            'profile' => $profile,
        ];
    }


    // método para atualizar o profile do utilizador enviado nas Query Params
    public function actionUpdateProfile()
    {
        // obtém o token e o profile_id
        $token = Yii::$app->request->get('token');
        $profile_id = Yii::$app->request->get('profile_id');

        // verifica se o token e o profile_id são válidos
        $this->verifyCredentials($token, $profile_id);
        
        // busca o profile associado ao profile_id
        $profile = Profile::findOne(['user_id' => $profile_id]);
        if (!$profile) {
            throw new NotFoundHttpException('Nenhum perfil foi encontrado para este utilizador.');
        }

        $request = Yii::$app->request;

        // atualiza os campos do profile do utilizador
        $profile->nome = $request->post('nome', $profile->nome);
        $profile->datanascimento = $request->post('datanascimento', $profile->datanascimento);
        $profile->nif = $request->post('nif', $profile->nif);
        $profile->morada = $request->post('morada', $profile->morada);

        // guarda as alterações no banco de dados
        if ($profile->save()) {
            return [
                'message' => "O perfil do utilizador {$profile_id} foi atualizado com sucesso!",
                'profile' => $profile
            ];
        }

        throw new BadRequestHttpException("Erro ao atualizar os detalhes do perfil do utilizador {$profile_id}.", $profile->errors);
    }

}