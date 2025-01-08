<?php

namespace tests\unit;

use Codeception\Test\Unit;
use common\models\Evento;
use common\models\Categoria;
use common\models\Local;

class EventoTest extends Unit
{
    protected function _before()
    {

    }

    public function testEventoCreation()
    {
        $local = new Local(['id' => 1, 'nome' => 'Test Local']);
        $local->save();

        $categoria = new Categoria(['id' => 1, 'nome' => 'Test Categoria']);
        $categoria->save();

        $evento = new Evento([
            'titulo' => 'Test Event',
            'descricao' => 'This is a test event.',
            'datainicio' => date('Y-m-d H:i:s'),
            'datafim' => date('Y-m-d H:i:s', strtotime('+1 day')),
            'local_id' => $local->id,
            'categoria_id' => $categoria->id,
        ]);

        $this->assertTrue($evento->validate(), 'Evento should be valid.');

        $this->assertTrue($evento->save(), 'Evento should be saved successfully.');
    }

    public function testEventoValidationRules()
    {
        $evento = new Evento();

        $this->assertFalse($evento->validate(), 'Evento should not be valid without required fields.');

        // Set valid values
        $evento->titulo = 'Valid Event Title';
        $evento->descricao = 'This is a valid event description.';
        $evento->datainicio = date('Y-m-d H:i:s');
        $evento->datafim = date('Y-m-d H:i:s', strtotime('+1 day'));
        $evento->local_id = 1;
        $evento->categoria_id = 1;

        $this->assertTrue($evento->validate(), 'Evento should be valid with all required fields.');
    }

    public function testEventoAttributeLabels()
    {
        $evento = new Evento();
        $this->assertEquals('ID', $evento->attributeLabels()['id']);
        $this->assertEquals('Titulo', $evento->attributeLabels()['titulo']);
        $this->assertEquals('Descricao', $evento->attributeLabels()['descricao']);
        $this->assertEquals('Datainicio', $evento->attributeLabels()['datainicio']);
        $this->assertEquals('Datafim', $evento->attributeLabels()['datafim']);
        $this->assertEquals('Local ID', $evento->attributeLabels()['local_id']);
        $this->assertEquals('Categoria ID', $evento->attributeLabels()['categoria_id']);
    }
}