<?php

namespace backend\modules\api\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;
use backend\modules\api\components\QueryParamAuth;
use common\models\LinhaCarrinho;
use yii\base\DynamicModel;

class LinhaCarrinhoController extends ActiveController
{
    public $modelClass = 'common\models\LinhaCarrinho';

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

    /**Adiciona uma linha ao carrinho com informações de evento e bilhete.*
    @return array
    @throws BadRequestHttpException*/
public function actionAdicionarLinha(){
        $data = Yii::$app->getRequest()->getBodyParams();

        // Validação básica dos dados recebidos
        $model = DynamicModel::validateData($data, [
            [['evento_id', 'bilhete_id', 'quantidade','carrinho_id'], 'required'],
            [['evento_id', 'bilhete_id','carrinho_id'], 'integer'],
            [['quantidade'], 'number', 'min' => 1],
        ]);

        if ($model->hasErrors()) {
            throw new BadRequestHttpException('Dados inválidos');
        }

        // Cria a nova linha de carrinho
        $linhaCarrinho = new LinhaCarrinho();
        $linhaCarrinho->evento_id = $data['evento_id'];
        $linhaCarrinho->carrinho_id=$data['carrinho_id']
        $linhaCarrinho->bilhete_id = $data['bilhete_id'];
        $linhaCarrinho->quantidade = $data['quantidade'];
        $linhaCarrinho->profile_id = Yii::$app->profile->id;  // Supondo que você armazene o ID do usuário logado

        if (!$linhaCarrinho->save()) {
            throw new BadRequestHttpException('Erro ao salvar a linha no carrinho');
        }

        return [
            'status' => 'success',
            'message' => 'Linha adicionada ao carrinho com sucesso.',
            'data' => $linhaCarrinho
        ];
    }
}