<?php

namespace tests\unit;

use Codeception\Test\Unit;
use common\models\Local;

class LocalTest extends Unit
{
    protected function _before()
    {

    }

    public function testLocalCreation()
    {
        $local = new Local([
            'nome' => 'Test Local',
            'morada' => '123 Test Street',
            'cidade' => 'Test City',
            'capacidade' => 100,
        ]);

        $this->assertTrue($local->validate(), 'Local should be valid.');

        $this->assertTrue($local->save(), 'Local should be saved successfully.');
    }

    public function testLocalValidationRules()
    {
        $local = new Local();

        $this->assertFalse($local->validate(), 'Local should not be valid without required fields.');

        $local->nome = 'Valid Local Name';
        $local->morada = '456 Valid Street';
        $local->cidade = 'Valid City';
        $local->capacidade = 150;

        $this->assertTrue($local->validate(), 'Local should be valid with all required fields.');
    }

    public function testLocalAttributeLabels()
    {
        $local = new Local();
        $this->assertEquals('ID', $local->attributeLabels()['id']);
        $this->assertEquals('Nome', $local->attributeLabels()['nome']);
        $this->assertEquals('Morada', $local->attributeLabels()['morada']);
        $this->assertEquals('Cidade', $local->attributeLabels()['cidade']);
        $this->assertEquals('Capacidade', $local->attributeLabels()['capacidade']);
    }
}