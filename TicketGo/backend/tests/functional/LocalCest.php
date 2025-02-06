<?php

namespace tests\functional;

use common\models\Local;
use Yii;

class LocalCest
{
    protected function _before()
    {
        $local = new Local(['id' => 1, 'nome' => 'Test Local', 'morada' => '123 Test Street', 'cidade' => 'Test City', 'capacidade' => 100]);
        $local->save();
    }

    public function testIndex()
    {
        $response = Yii::$app->runAction('local/index');

        $this->assertNotNull($response);
        $this->assertContains('Locals', $response);
    }

    public function testCreateLocal()
    {
        $response = Yii::$app->runAction('local/create', [
            'Local' => [
                'nome' => 'New Test Local',
                'morada' => '456 New Street',
                'cidade' => 'New City',
                'capacidade' => 150,
            ],
        ]);

        $this->assertContains('Local created successfully!', $response);
        $this->assertContains('New Test Local', $response);
    }

    public function testUpdateLocal()
    {
        $local = Local::find()->one();


        $response = Yii::$app->runAction('local/update', [
            'id' => $local->id,
            'Local' => [
                'nome' => 'Updated Test Local',
                'morada' => '789 Updated Street',
                'cidade' => 'Updated City',
                'capacidade' => 200,
            ],
        ]);

        $this->assertContains('Local updated successfully!', $response);
        $this->assertContains('Updated Test Local', $response);
    }

    public function testDeleteLocal()
    {
        $local = Local::find()->one();


        Yii::$app->runAction('local/delete', ['id' => $local->id]);

        $this->assertNull(Local::findOne($local->id), 'The local should be deleted.');
    }

    public function testViewLocalDetails()
    {
        $local = Local::find()->one();


        $response = Yii::$app->runAction('local/view', ['id' => $local->id]);


        $this->assertContains('Local Details', $response);
        $this->assertContains($local->nome, $response);
        $this->assertContains($local->morada, $response);
        $this->assertContains($local->cidade, $response);
        $this->assertContains($local->capacidade, $response);
    }
}