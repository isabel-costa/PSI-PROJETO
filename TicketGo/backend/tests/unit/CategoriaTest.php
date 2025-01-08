<?php

namespace tests\unit;

use Codeception\Test\Unit;
use common\models\Categoria;

class CategoriaTest extends Unit
{
    protected function _before()
    {

    }

    public function testCategoriaCreation()
    {
        $categoria = new Categoria([
            'nome' => 'Test Category',
            'descricao' => 'This is a test category.',
        ]);

        $this->assertTrue($categoria->validate(), 'Categoria should be valid.');

        $this->assertTrue($categoria->save(), 'Categoria should be saved successfully.');
    }

    public function testCategoriaValidationRules()
    {
        $categoria = new Categoria();

        $this->assertFalse($categoria->validate(), 'Categoria should not be valid without required fields.');

        $categoria->nome = 'Valid Category Name';

        $this->assertTrue($categoria->validate(), 'Categoria should be valid with all required fields.');
    }

    public function testCategoriaAttributeLabels()
    {
        $categoria = new Categoria();
        $this->assertEquals('ID', $categoria->attributeLabels()['id']);
        $this->assertEquals('Nome', $categoria->attributeLabels()['nome']);
        $this->assertEquals('Descricao', $categoria->attributeLabels()['descricao']);
    }
}