<?php

namespace frontend\controllers;

use common\models\Carrinho;
use common\models\LinhaCarrinho;
use common\models\MetodoPagamento;
use common\models\Bilhete;
use common\models\Profile;
use frontend\controllers\CarrinhoController;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class CheckoutController extends Controller
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
                        'actions' => ['checkout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionCheckout()
    {
        if (Yii::$app->user->isGuest) {
            Yii::$app->session->setFlash('error', 'precisas de  estar logado para acessar o carrinho.');
            return $this->redirect(['site/login']);
        }
        $user = Yii::$app->user->identity;

        if ($user) {
            $profile = $user->profile;
            $userProfile = Yii::$app->user->identity->profile;


            $profileId = $userProfile->id;

            $carrinho = Carrinho::findOne(['profile_id' => $profileId]);

            $metodos = MetodoPagamento::find()->all();

            if (!$carrinho) {
                Yii::$app->session->setFlash('info', 'O carrinho está vazio.');
                return $this->redirect(['site/index']);
            }

            $linhasCarrinho = LinhaCarrinho::find()
                ->where(['carrinho_id' => $carrinho->id])
                ->with(['bilhete.evento'])
                ->all();


            return $this->render('checkout', [
                'carrinho' => $carrinho,
                'linhasCarrinho' => $linhasCarrinho,
                'metodos' => $metodos,
                'profile' => $profile,
                'user' => $user,
            ]);
        }
    }
    public function actionFinalizarCompra()
    {
        if (Yii::$app->user->isGuest) {
            Yii::$app->session->setFlash('error', 'Precisas de estar logado para finalizar a compra.');
            return $this->redirect(['site/login']);
        }

        $user = Yii::$app->user->identity;
        $profile = $user->profile;
        $profileId = $profile->id;

        $carrinho = Carrinho::findOne(['profile_id' => $profileId]);

        if (!$carrinho) {
            Yii::$app->session->setFlash('info', 'O carrinho está vazio.');
            return $this->redirect(['./site']);
        }

        $linhasCarrinho = LinhaCarrinho::find()
            ->where(['carrinho_id' => $carrinho->id])
            ->with(['bilhete.evento', 'bilhete.zona'])
            ->all();

        foreach ($linhasCarrinho as $linha) {
            $bilhete = $linha->bilhete;
            $quantidadeCompra = $linha->quantidade;

            $bilhetesDisponiveis = Bilhete::find()
                ->where(['zona_id' => $bilhete->zona_id, 'vendido' => 0])
                ->limit($quantidadeCompra)
                ->all();

            if (count($bilhetesDisponiveis) < $quantidadeCompra) {
                Yii::$app->session->setFlash('error', 'Não há bilhetes suficientes disponíveis para a sua compra.');
                return $this->redirect(['checkout/checkout']);
            }

            foreach ($bilhetesDisponiveis as $bilheteDisponivel) {
                $bilheteDisponivel->vendido = 1;
                if (!$bilheteDisponivel->save()) {
                    Yii::$app->session->setFlash('error', 'Erro ao atualizar o bilhete para vendido.');
                    return $this->redirect(['checkout/checkout']);
                }
            }
        }

        Yii::$app->session->setFlash('success', 'Compra finalizada com sucesso!');

        LinhaCarrinho::deleteAll(['carrinho_id' => $carrinho->id]);

        return $this->redirect(['./site']);
    }
}
