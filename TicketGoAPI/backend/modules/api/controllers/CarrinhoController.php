<?php

namespace backend\modules\api\controllers;

use backend\modules\api\components\QueryParamAuth;
use common\models\Bilhete;
use common\models\Carrinho;
use common\models\Evento;
use common\models\LinhaCarrinho;
use common\models\User;
use Yii;
use yii\rest\ActiveController;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;

class CarrinhoController extends ActiveController
{
    public $modelClass = 'common\models\Carrinho';


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


    // método para obter o carrinho do utilizador enviado nas Query Params
    public function actionGetCarrinho()
    {
        // obtém o token e o profile_id
        $token = Yii::$app->request->get('token');
        $profile_id = Yii::$app->request->get('profile_id');

        // verifica se o token e o profile_id são válidos
        $this->verifyCredentials($token, $profile_id);

        // busca o carrinho associado ao profile_id
        $carrinho = Carrinho::findOne(['profile_id' => $profile_id]);
        if (!$carrinho) {
            throw new NotFoundHttpException('Carrinho não encontrado para este perfil.');
        }

        // busca todas as linhas do carrinho
        $linhasCarrinho = LinhaCarrinho::find()->where(['carrinho_id' => $carrinho->id])->all();

        // monta a resposta final com os  detalhes dos bilhetes e dos eventos
        $itensCarrinho = [];
        foreach ($linhasCarrinho as $linha) {
            $bilhete = Bilhete::findOne($linha->bilhete_id);
            if (!$bilhete) continue;

            $evento = Evento::findOne($bilhete->evento_id);
            if (!$evento) continue;

            $itensCarrinho[] = [
                'linha_id' => $linha->id,
                'quantidade' => $linha->quantidade,
                'preco_unitario' => $linha->precounitario,
                'valor_total' => $linha->valortotal,
                'bilhete' => [
                    'bilhete_id' => $bilhete->id,
                    'codigo' => $bilhete->codigobilhete,
                    'preco' => $bilhete->precounitario,
                    'vendido' => $bilhete->vendido,
                    'evento' => [
                        'evento_id' => $evento->id,
                        'titulo' => $evento->titulo,
                        'descricao' => $evento->descricao,
                        'data_inicio' => $evento->datainicio,
                        'data_fim' => $evento->datafim
                    ]
                ]
            ];
        }

        return [
            'message' => "Carrinho do utilizador {$profile_id}:",
            'carrinho_id' => $carrinho->id,
            'itens_no_carrinho' => $itensCarrinho
        ];
    }
}
