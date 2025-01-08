<?php

namespace tests\functional;

use common\models\MetodoPagamento;
use Yii;

class MetodoPagamentoCest
{
    protected function _before()
    {
        $metodoPagamento = new MetodoPagamento(['id' => 1, 'nome' => 'Test Payment Method']);
        $metodoPagamento->save();
    }

    public function testIndex()
    {
        $response = Yii::$app->runAction('metodo-pagamento/index');

        $this->assertNotNull($response);
        $this->assertContains('Metodo Pagamentos', $response);
    }

    public function testCreatePaymentMethod()
    {
        $response = Yii::$app->runAction('metodo-pagamento/create', [
            'MetodoPagamento' => [
                'nome' => 'New Test Payment Method',
            ],
        ]);

        $this->assertContains('Metodo Pagamento created successfully!', $response);
        $this->assertContains('New Test Payment Method', $response);
    }

    public function testUpdatePaymentMethod()
    {
        $metodoPagamento = MetodoPagamento::find()->one();

        $response = Yii::$app->runAction('metodo-pagamento/update', [
            'id' => $metodoPagamento->id,
            'MetodoPagamento' => [
                'nome' => 'Updated Test Payment Method',
            ],
        ]);

        // Check for success message
        $this->assertContains('Metodo Pagamento updated successfully!', $response);
        $this->assertContains('Updated Test Payment Method', $response);
    }

    public function testDeletePaymentMethod()
    {
        $metodoPagamento = MetodoPagamento::find()->one();

        Yii::$app->runAction('metodo-pagamento/delete', ['id' => $metodoPagamento->id]);

        $this->assertNull(MetodoPagamento::findOne($metodoPagamento->id), 'The payment method should be deleted.');
    }

    public function testViewPaymentMethodDetails()
    {
        $metodoPagamento = MetodoPagamento::find()->one();

        $response = Yii::$app->runAction('metodo-pagamento/view', ['id' => $metodoPagamento->id]);

        $this->assertContains('Metodo Pagamento Details', $response);
        $this->assertContains($metodoPagamento->nome, $response);
    }
}