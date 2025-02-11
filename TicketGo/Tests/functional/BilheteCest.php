<?php

namespace tests\functional;

use common\models\Bilhete;
use common\models\Evento;
use common\models\Zona;
use Yii;

class BilheteCest
{
    protected function _before()
    {
        $evento = new Evento(['id' => 1, 'titulo' => 'Test Event']);
        $evento->save();

        $zona = new Zona(['id' => 1, 'lugar' => 'Zone A', 'local_id' => 1, 'quantidadedisponivel' => 10]);
        $zona->save();
    }

    public function testIndex()
    {
        $response = Yii::$app->runAction('bilhete/index', ['evento_id' => 1]);

        // Check if the response is valid
        $this->assertNotNull($response);
        $this->assertContains('Bilhetes', $response);
    }

    public function testCreateBilhete()
    {
        $response = Yii::$app->runAction('bilhete/create', [
            'evento_id' => 1,
            'Bilhete' => [
                'zona_id' => 1,
                'precounitario' => 10.00,
            ],
        ]);

        $this->assertContains('Bilhetes criados com sucesso!', $response);

        $bilhetes = Bilhete::find()->where(['evento_id' => 1])->all();
        $this->assertCount(10, $bilhetes);
    }

    public function testCreateBilheteWithoutZone()
    {
        $response = Yii::$app->runAction('bilhete/create', [
            'evento_id' => 1,
            'Bilhete' => [
                'precounitario' => 10.00,
            ],
        ]);

        $this->assertContains('Zona selecionada não encontrada.', $response);
    }
}