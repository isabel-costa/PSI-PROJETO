<?php

namespace tests\unit;

use Codeception\Test\Unit;
use common\models\Zona;
use common\models\Local;
use Yii;

class ZonaTest extends Unit
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
    }

    public function testZonaCreation()
    {
        $zona = new Zona([
            'lugar' => 'Test Zone',
            'porta' => 1,
            'local_id' => 1,
            'quantidadedisponivel' => 100,
        ]);

        $this->assertTrue($zona->validate(), 'Zona should be valid.');

        $this->assertTrue($zona->save(), 'Zona should be saved successfully.');
    }

    public function testZonaValidationRules()
    {
        $zona = new Zona();

        // Test that the zona is not valid without required fields
        $this->assertFalse($zona->validate(), 'Zona should not be valid without required fields.');

        // Set valid values
        $zona->lugar = 'Valid Zone';
        $zona->porta = 1;
        $zona->local_id = 1;
        $zona->quantidadedisponivel = 50;

        $this->assertTrue($zona->validate(), 'Zona should be valid with all required fields.');
    }

    public function testZonaAttributeLabels()
    {
        $zona = new Zona();
        $this->assertEquals('ID', $zona->attributeLabels()['id']);
        $this->assertEquals('Lugar', $zona->attributeLabels()['lugar']);
        $this->assertEquals('Porta', $zona->attributeLabels()['porta']);
        $this->assertEquals('Local ID', $zona->attributeLabels()['local_id']);
        $this->assertEquals('Quantidadedisponivel', $zona->attributeLabels()['quantidadedisponivel']);
    }
}