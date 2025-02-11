<?php

namespace backend\modules\api\controllers;

use backend\modules\api\components\QueryParamAuth;
use common\models\Bilhete;
use common\models\Evento;
use common\models\User;
use common\models\Zona;
use Yii;
use yii\rest\ActiveController;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;

class BilheteController extends ActiveController
{
    public $modelClass = 'common\models\Bilhete';


    // configura os comportamentos do controlador
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        
        // adiciona autenticação via Query Params
        /*$behaviors['authenticator'] = [
            'class' => QueryParamAuth::class,
        ];*/
        
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


    // método para obter bilhetes por evento
    public function actionGetBilhetesEvento($evento_id)
    {
        // obtém o token e o profile_id
        $token = Yii::$app->request->get('token');
        $profile_id = Yii::$app->request->get('profile_id');

        // verifica se o token e o profile_id são válidos
        $this->verifyCredentials($token, $profile_id);

        // verifica se o parâmetro é válido
        if (!$evento_id || !is_numeric($evento_id)) {
            throw new BadRequestHttpException('ID do evento inválido.');
        }

        // encontra o evento pelo ID
        $evento = Evento::findOne($evento_id);

        // caso não encontre
        if (!$evento) {
            throw new NotFoundHttpException('Evento não encontrado.');
        }

        // encontra todos os bilhetes associados ao evento
        $bilhetes = Bilhete::find()->where(['evento_id' => $evento_id])->all();

        if (empty($bilhetes)) {
            throw new NotFoundHttpException('Nenhum bilhete encontrado para este evento.');
        }

        return [
            'message' => "Foram encontrados os seguintes bilhetes para o evento {$evento_id}:",
            'evento' => $evento,
            'bilhetes' => $bilhetes
        ];
    }


    // método para obter bilhetes por zona
    public function actionGetBilhetesZona($zona_id)
    {
        // obtém o token e o profile_id
        $token = Yii::$app->request->get('token');
        $profile_id = Yii::$app->request->get('profile_id');

        // verifica se o token e o profile_id são válidos
        $this->verifyCredentials($token, $profile_id);

        // verifica se o parâmetro é válido
        if (!$zona_id || !is_numeric($zona_id)) {
            throw new BadRequestHttpException('ID da zona inválido.');
        }

        // encontra a zona pelo ID
        $zona = Zona::findOne($zona_id);

        // caso não encontre
        if (!$zona) {
            throw new NotFoundHttpException('Zona não encontrada.');
        }

        // encontra todos os bilhetes associados à zona
        $bilhetes = Bilhete::find()->where(['zona_id' => $zona_id])->all();

        if (empty($bilhetes)) {
            throw new NotFoundHttpException('Nenhum bilhete encontrado para esta zona.');
        }

        return [
            'message' => "Foram encontrados os seguintes bilhetes para a zona {$zona_id}:",
            'zona' => $zona,
            'bilhetes' => $bilhetes
        ];
    }
}
