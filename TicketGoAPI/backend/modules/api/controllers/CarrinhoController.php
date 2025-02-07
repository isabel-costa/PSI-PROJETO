<?php
namespace backend\modules\api\controllers;

use common\models\Carrinho;
use Yii;
use backend\modules\api\components\QueryParamAuth;
use yii\rest\ActiveController;
use common\models\mqttPublisher;

class CarrinhoController extends ActiveController {
    public $modelClass = 'common\models\Carrinho';

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

    // Método para obter o carrinho associado a um perfil específico
    public function actionGetProfile($profile_id)
    {
        // Encontra todos os carrinhos associados ao perfil
        $carrinhos = $this->modelClass::find()->where(['profile_id' => $profile_id])->all();

        // Verifica se o carrinho foi encontrado
        if (!$carrinhos) {
            throw new NotFoundHttpException('Carrinho não encontrado para este perfil.');
        }

        return $carrinhos;
    }

    // Método para obter as linhas do carrinho associado a um perfil específico
    public function actionGetLinhasCarrinho($profile_id)
    {
        // Encontra o carrinho associado ao perfil
        $carrinho = Carrinho::findOne(['profile_id' => $profile_id]);

        // Verifica se o carrinho foi encontrado
        if (!$carrinho) {
            throw new NotFoundHttpException('Carrinho não encontrado para este perfil.');
        }

        // Retorna as linhas do carrinho
        return $carrinho->linhasCarrinhos;
    }
}