<?php

namespace frontend\controllers;

use common\models\Bilhete;
use common\models\Carrinho;
use common\models\Evento;
use common\models\LinhaCarrinho;
use common\models\Zona;
use common\models\Profile;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use Yii;

class CarrinhoController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                    ],
                    [
                        'actions' => ['add-tickets-cart', 'remove-item'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionCart()
    {
        if (Yii::$app->user->isGuest) {
            Yii::$app->session->setFlash('error', 'precisas de  estar logado para acessar o carrinho.');
            return $this->redirect(['site/login']);
        }

        $userProfile = Yii::$app->user->identity->profile;

        $profileId = $userProfile->id;

        $carrinho = Carrinho::findOne(['profile_id' => $profileId]);

        if (!$carrinho) {
            Yii::$app->session->setFlash('info', 'O carrinho está vazio.');
            return $this->redirect(['site/index']);
        }

        // Obter todas as linhas do carrinho associadas
        $linhasCarrinho = LinhaCarrinho::find()
            ->where(['carrinho_id' => $carrinho->id])
            ->with(['bilhete.evento'])
            ->all();

        $totalCarrinho = 0;

        foreach ($linhasCarrinho as $linha) {
            $totalCarrinho += $linha->precounitario * $linha->quantidade;
        }

        return $this->render('cart', [
            'linhasCarrinho' => $linhasCarrinho,
            'carrinho' => $carrinho,
            'totalCarrinho' => $totalCarrinho,
        ]);
    }


    public function actionAddTicketsCart()
    {
        $request = Yii::$app->request;

        if ($request->isPost) {
            // Obter o user e o profile
            $user = Yii::$app->user->identity;
            $profile = $user->profile;
            $profileId = $profile->id;

            // Obter o evento, zona e quantidade
            $eventoId = $request->post('evento_id');
            $zonaId = $request->post('zona_id');
            $quantidade = $request->post('quantidade', 1);

            $bilhete = Bilhete::findOne(['evento_id' => $eventoId, 'zona_id' => $zonaId]);

            if (!$bilhete) {
                Yii::$app->session->setFlash('error', 'Bilhete não encontrado.');
                return $this->redirect(['evento/product-detail', 'id' => $eventoId]);
            }

            $carrinho = Carrinho::findOne(['profile_id' => $profileId]);
            if (!$carrinho) {
                $carrinho = new Carrinho();
                $carrinho->profile_id = $profileId;
                if (!$carrinho->save()) {
                    Yii::$app->session->setFlash('error', 'Falha ao criar carrinho.');
                    return $this->redirect(['evento/product-detail', 'id' => $eventoId]);
                }
            }

            // verifica se a linha do bilhete já existe no carrinho
            $linhaExistente = LinhaCarrinho::findOne([
                'carrinho_id' => $carrinho->id,
                'bilhete_id' => $bilhete->id,
            ]);

            if ($linhaExistente) {
                $linhaExistente->quantidade += $quantidade;
                if ($linhaExistente->save()) {
                    Yii::$app->session->setFlash('success', 'Quantidade atualizada no carrinho.');
                } else {
                    Yii::$app->session->setFlash('error', 'Falha ao atualizar a quantidade no carrinho.');
                }
            } else {
                $linhaCarrinho = new LinhaCarrinho();
                $linhaCarrinho->carrinho_id = $carrinho->id;
                $linhaCarrinho->bilhete_id = $bilhete->id;
                $linhaCarrinho->precounitario = $bilhete->precounitario;
                $linhaCarrinho->quantidade = $quantidade;

                if ($linhaCarrinho->save()) {
                    Yii::$app->session->setFlash('success', 'Item adicionado ao carrinho.');
                } else {
                    Yii::$app->session->setFlash('error', 'Falha ao adicionar o item ao carrinho.');
                }
            }

            return $this->redirect(['evento/product-detail', 'id' => $eventoId]);
        }

        throw new BadRequestHttpException('Requisição inválida.');
    }


    public function actionRemoveItem($id)
    {
        $linhaCarrinho = LinhaCarrinho::findOne($id);

        if ($linhaCarrinho && $linhaCarrinho->delete()) {
            Yii::$app->session->setFlash('success', 'Item removido do carrinho.');
        } else {
            Yii::$app->session->setFlash('error', 'Falha ao remover o item do carrinho.');
        }

        return $this->redirect(['carrinho/cart']);
    }
    public function actionUpdateQuantity()
    {
        $request = Yii::$app->request;

        if ($request->isPost) {
            $linhaID = $request->post('id');
            $action = $request->post('action');

            $linhaCarrinho = LinhaCarrinho::findOne($linhaID);

            if($linhaCarrinho){
                if($action == 'mais'){
                    $linhaCarrinho ->quantidade += 1;
                }elseif ($action === 'menos' && $linhaCarrinho->quantidade > 1){
                    $linhaCarrinho ->quantidade -= 1;
                }else{
                    Yii::$app->session->setFlash('error', 'A quantidade mínima é 1 bilhete.');
                }
                $linhaCarrinho->save();

                return $this->redirect(['carrinho/cart']);
            }
        }
    }
}


