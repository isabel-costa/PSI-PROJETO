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
            Yii::$app->session->setFlash('error', 'Precisas de estar logado para acessar o carrinho.');
            return $this->redirect(['site/login']);
        }

        $user = Yii::$app->user->identity;

        if ($user) {
            $profile = $user->profile;
            $profileId = $profile->id;

            $carrinho = Carrinho::findOne(['profile_id' => $profileId]);

            $metodos = MetodoPagamento::find()->all();
            $selectPaymentMethod = Yii::$app->request->post('payment_method', null);



            $linhasCarrinho = LinhaCarrinho::find()
                ->where(['carrinho_id' => $carrinho->id])
                ->with(['bilhete.evento'])
                ->all();

            if (!$linhasCarrinho) {
                Yii::$app->session->setFlash('info', 'O carrinho está vazio.');
                return $this->redirect(['./']);
            }

            return $this->render('checkout', [
                'carrinho' => $carrinho,
                'linhasCarrinho' => $linhasCarrinho,
                'metodos' => $metodos,
                'profile' => $profile,
                'user' => $user,
                'selectedPaymentMethod' => $selectPaymentMethod,
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

        $selectedPaymentMethod = Yii::$app->request->post('payment_method', null);

        if (!MetodoPagamento::findOne($selectedPaymentMethod)) {
            Yii::$app->session->setFlash('error', 'Método de pagamento inválido.');
            return $this->redirect(['checkout/checkout']);
        }

        $bilhetesComprados =[];

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
                $bilhetesComprados[] = $bilheteDisponivel -> id;
            }
        }
        $this -> gerarFatura($carrinho, $profile, $selectedPaymentMethod, $bilhetesComprados);

        Yii::$app->session->setFlash('success', 'Compra finalizada com sucesso!');

        LinhaCarrinho::deleteAll(['carrinho_id' => $carrinho->id]);

        return $this->redirect(['./site']);
    }
    public function gerarFatura($carrinho, $profile, $selectedPaymentMethod,$bilhetesComprados)
    {
        Yii::error("A gerar a fatura para o carrinho {$carrinho->id} do perfil {$profile->id}");

        $linhasCarrinho = LinhaCarrinho::find()
            ->where(['carrinho_id' => $carrinho->id])
            ->with(['bilhete.evento', 'bilhete.zona'])
            ->all();

        $metodoPagamento = MetodoPagamento::findOne($selectedPaymentMethod);
        $metodoPagamentoNome = $metodoPagamento ? $metodoPagamento->nome : 'Metodo nao enconradao';

        $mpdf = new \Mpdf\Mpdf();

        $content = $this->renderPartial('fatura', [
            'carrinho' => $carrinho,
            'profile' => $profile,
            'linhasCarrinho' => $linhasCarrinho,
            'selectedPaymentMethod' => $metodoPagamentoNome,
        ]);

        $mpdf->WriteHTML($content);
        $mpdf->AddPage();

        foreach ($bilhetesComprados as $bilhete){
            $content = $this->renderPartial('bilhete', [
                'bilhete' => Bilhete::findOne($bilhete),
            ]);
            $mpdf->WriteHTML($content);
            $mpdf->AddPage();
        }

        try {
            $mpdf->WriteHTML($content);

            $pdfOutput = $mpdf->Output('', 'S');

            Yii::error("PDF gerado com sucesso.");


            return $mpdf->Output('fatura_compra.pdf', 'I'); // 'I' exibe o PDF no navegador


        } catch (\Mpdf\MpdfException $e) {
            Yii::error("Erro ao gerar o PDF: " . $e->getMessage());
        }
    }

}
