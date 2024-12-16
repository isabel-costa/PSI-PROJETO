<?php

namespace app\controllers;

use Yii;
use app\models\Carrinho;
use app\models\Bilhete;
use app\models\Evento;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class CarrinhoController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'updateTicketsCart', 'removeTicketsCart', 'purchaseTickets'],
                        'allow' => true,
                        'roles' => ['resgisteredUser'],
                    ],
                ],
            ],
        ];
    }
    // Exibe o carrinho
    public function actionIndex()
    {
        $carrinho = Carrinho::getItens();
        return $this->render('index', ['carrinho' => $carrinho]);
    }

    // Atualiza a quantidade de bilhetes de um evento no carrinho
    public function actionUpdateTicketsCart($id, $quantidade)
    {
        if (Carrinho::updateTicketsCart($id, $quantidade)) {
            Yii::$app->session->setFlash('success', 'Quantidade atualizada com sucesso.');
        } else {
            Yii::$app->session->setFlash('error', 'Falha ao atualizar a quantidade.');
        }
        return $this->redirect(['index']);
    }

    // Remove um evento do carrinho
    public function actionRemoveTicketsCart($id)
    {
        if (Carrinho::removeTicketsCart($id)) {
            Yii::$app->session->setFlash('success', 'Evento removido do carrinho.');
        } else {
            Yii::$app->session->setFlash('error', 'Falha ao remover o evento.');
        }
        return $this->redirect(['index']);
    }

    // Finaliza a compra
    public function actionPurchaseTickets()
    {
        if (Carrinho::purchaseTickets()) {
            Yii::$app->session->setFlash('success', 'Compra finalizada com sucesso!');
            return $this->redirect(['/evento/index']); // Redireciona para a página principal de eventos
        } else {
            Yii::$app->session->setFlash('error', 'Falha ao finalizar a compra.');
            return $this->redirect(['index']);
        }
    }
}