<?php

namespace tests\functional;

use common\models\Zona;
use common\models\Local;
use Yii;

class ZonaCest
{
    protected function _before()
    {
        $local = new Local([
            'nome' => 'Test Local',
            'morada' => '123 Test Street',
            'cidade' => 'Test City',
            'capacidade' => 100,
        ]);
        $local->save();

        $zona = new Zona([
            'lugar' => 'Test Zone',
            'porta' => 1,
            'local_id' => $local->id,
            'quantidadedisponivel' => 100,
        ]);
        $zona->save();
    }

    public function testIndex()
    {
        $response = Yii::$app->runAction('zona/index', ['local_id' => 1]);

        $this->assertNotNull($response);
        $this->assertContains('Zonas de', $response);
    }

    public function testCreateZone()
    {
        $response = Yii::$app->runAction('zona/create', [
            'Zona' => [
                'lugar' => 'New Test Zone',
                'porta' => 2,
                'local_id' => 1,
                'quantidadedisponivel' => 150,
            ],
        ]);

        $this->assertContains('Zona criada com sucesso.', $response);
        $this->assertContains('New Test Zone', $response);
    }

    public function testUpdateZone()
    {
        $zona = Zona::find()->one();

        $response = Yii::$app->runAction('zona/update', [
            'id' => $zona->id,
            'Zona' => [
                'lugar' => 'Updated Test Zone',
                'porta' => 3,
                'local_id' => 1,
                'quantidadedisponivel' => 200,
            ],
        ]);

        $this->assertContains('Zona atualizada com sucesso!', $response);
        $this->assertContains('Updated Test Zone', $response);
    }

    public function testDeleteZone()
    {
        $zona = Zona::find()->one();

        Yii::$app->runAction('zona/delete', ['id' => $zona->id]);

        $this->assertNull(Zona::findOne($zona->id), 'The zone should be deleted.');
    }

    public function testViewZoneDetails()
    {
        $zona = Zona::find()->one();

        $response = Yii::$app->runAction('zona/view', ['id' => $zona->id]);

        $this->assertContains('Zona Details', $response);
        $this->assertContains($zona->lugar, $response);
        $this->assertContains($zona->porta, $response);
        $this->assertContains($zona->quantidadedisponivel, $response);
    }
}