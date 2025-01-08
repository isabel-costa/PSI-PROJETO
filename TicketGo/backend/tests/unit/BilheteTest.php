<?php

namespace tests\unit;

use Codeception\Test\Unit;
use common\models\Bilhete;
use common\models\Evento;
use common\models\Zona;

class BilheteTest extends Unit
{
    protected function _before()
    {
        $evento = new Evento(['id' => 1, 'titulo' => 'Test Event']);
        $evento->save();

        $zona = new Zona(['id' => 1, 'lugar' => 'Zone A', 'local_id' => 1, 'quantidadedisponivel' => 10]);
        $zona->save();
    }

    public function testBilheteCreation()
    {
        $bilhete = new Bilhete([
            'evento_id' => 1,
            'zona_id' => 1,
            'precounitario' => 10.00,
            'vendido' => 0,
            'data' => date('Y-m-d H:i:s'),
            'codigobilhete' => 'ABC1234567',
        ]);

        $this->assertTrue($bilhete->validate(), 'Bilhete should be valid.');

        $this->assertTrue($bilhete->save(), 'Bilhete should be saved successfully.');
    }

    public function testBilheteValidationRules()
    {
        $bilhete = new Bilhete();

        $this->assertFalse($bilhete->validate(), 'Bilhete should not be valid without required fields.');

        $bilhete->evento_id = 1;
        $bilhete->zona_id = 1;
        $bilhete->precounitario = 10.00;

        $this->assertTrue($bilhete->validate(), 'Bilhete should be valid with all required fields.');
    }

    public function testBilheteAttributeLabels()
    {
        $bilhete = new Bilhete();
        $this->assertEquals('ID', $bilhete->attributeLabels()['id']);
        $this->assertEquals('Evento ID', $bilhete->attributeLabels()['evento_id']);
        $this->assertEquals('Zona ID', $bilhete->attributeLabels()['zona_id']);
        $this->assertEquals('Precounitario', $bilhete->attributeLabels()['precounitario']);
        $this->assertEquals('Vendido', $bilhete->attributeLabels()['vendido']);
        $this->assertEquals('Data', $bilhete->attributeLabels()['data']);
        $this->assertEquals('Codigobilhete', $bilhete->attributeLabels()['codigobilhete']);
        $this->assertEquals('Linhafatura ID', $bilhete->attributeLabels()['linhafatura_id']);
    }
}