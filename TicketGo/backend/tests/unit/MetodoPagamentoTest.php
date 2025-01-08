<?php

namespace tests\unit;

use Codeception\Test\Unit;
use common\models\MetodoPagamento;

class MetodoPagamentoTest extends Unit
{
    protected function _before()
    {

    }

    public function testMetodoPagamentoCreation()
    {
        $metodoPagamento = new MetodoPagamento([
            'nome' => 'Test Payment Method',
        ]);

        $this->assertTrue($metodoPagamento->validate(), 'MetodoPagamento should be valid.');

        $this->assertTrue($metodoPagamento->save(), 'MetodoPagamento should be saved successfully.');
    }

    public function testMetodoPagamentoValidationRules()
    {
        $metodoPagamento = new MetodoPagamento();

        $this->assertFalse($metodoPagamento->validate(), 'MetodoPagamento should not be valid without required fields.');

        $metodoPagamento->nome = 'Valid Payment Method Name';

        $this->assertTrue($metodoPagamento->validate(), 'MetodoPagamento should be valid with all required fields.');
    }

    public function testMetodoPagamentoAttributeLabels()
    {
        $metodoPagamento = new MetodoPagamento();
        $this->assertEquals('ID', $metodoPagamento->attributeLabels()['id']);
        $this->assertEquals('Nome', $metodoPagamento->attributeLabels()['nome']);
    }
}