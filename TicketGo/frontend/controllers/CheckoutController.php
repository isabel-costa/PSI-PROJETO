<?php

namespace frontend\controllers;

use common\models\Carrinho;
use common\models\LinhaCarrinho;
use common\models\MetodoPagamento;
use common\models\Bilhete;
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
        ]);
    }

    public function actionConfirmar()
    {

        $carrinho = new Carrinho();
        if (empty($carrinho->itens)) {
            Yii::$app->session->setFlash('erro', 'Seu carrinho está vazio.');
            return $this->redirect(['checkout/index']);
        }

        // Coletar dados do formulário
        $dados = Yii::$app->request->post();

        // Validar dados (simples validação, você pode expandir conforme necessário)
        if (empty($dados['billingFirstName']) || empty($dados['billingLastName'])) {
            Yii::$app->session->setFlash('erro', 'Por favor, preencha todos os campos obrigatórios.');
            return $this->redirect(['checkout/index']);
        }

        // Verifica se foi escolhido um método de pagamento
        $metodoPagamentoId = $dados['paymentMethod'];
        if (!$metodoPagamentoId) {
            Yii::$app->session->setFlash('erro', 'Selecione um método de pagamento.');
            return $this->redirect(['checkout/index']);
        }

        // Carrega o método de pagamento
        $metodoPagamento = MetodoPagamento::findOne($metodoPagamentoId);
        if (!$metodoPagamento) {
            throw new NotFoundHttpException('Método de pagamento não encontrado.');
        }

        // Processar pagamento (isso depende da sua lógica de integração com gateways de pagamento)
        $resultadoPagamento = $this->processarPagamento($carrinho->calcularTotal(), $metodoPagamento);

        if ($resultadoPagamento['status'] === 'sucesso') {
            // Criar o pedido e salvar no banco de dados
            $pedido = new Pedido();
            $pedido->user_id = Yii::$app->user->id;
            $pedido->total = $carrinho->calcularTotal();
            $pedido->status = 'Pendente';
            $pedido->metodo_pagamento_id = $metodoPagamento->id;
            $pedido->save();

            // Criar registros para os itens no pedido (relacionamento com os bilhetes)
            foreach ($carrinho->itens as $item) {
                // Adicionar detalhes dos bilhetes ao pedido
            }

            // Limpar o carrinho após a compra
            Yii::$app->session->remove('carrinho');

            // Redirecionar para a página de sucesso
            return $this->render('sucesso', [
                'pedido' => $pedido,
            ]);
        } else {
            Yii::$app->session->setFlash('erro', 'O pagamento falhou. Tente novamente.');
            return $this->redirect(['checkout/index']);
        }
    }

    private function processarPagamento($valor, $metodoPagamento)
    {
        // Aqui você implementa a integração com um gateway de pagamento (PayPal, Stripe, etc.)
        // Neste exemplo, vamos simular o pagamento bem-sucedido.
        return [
            'status' => 'sucesso',  // Isso pode ser alterado dependendo da resposta do gateway
            'message' => 'Pagamento realizado com sucesso!',
        ];
    }

}