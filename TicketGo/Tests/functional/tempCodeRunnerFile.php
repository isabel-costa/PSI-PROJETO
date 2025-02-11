<?php

namespace frontend\tests\functional;

use frontend\tests\FunctionalTester;
use common\models\Categoria;
use common\models\Evento;
use common\models\Local;

// teste funcional evento
class EventoTest
{
    private $eventoId;

    public function _before(FunctionalTester $I)
    {
        $I->amOnRoute('site/evento');
    }


    // teste de criação de evento
    public function testCreateEvento(FunctionalTester $I)
    {
        // criação de um local e de uma categoria para associar ao evento
        $local = new Local(['nome' => 'nome_local']);
        $I->assertTrue($local->save(), 'Falha ao salvar local');

        $categoria = new Categoria(['nome' => 'nome_categoria']);
        $I->assertTrue($categoria->save(), 'Falha ao salvar categoria');

        // criação do evento
        $evento = new Evento();

        $evento->titulo = 'titulo';
        $evento->descricao = 'descricao';
        $evento->datainicio = '2025-03-10 20:00:00';
        $evento->datafim = '2025-03-10 23:00:00';
        $evento->local_id = $local->id;
        $evento->categoria_id = $categoria->id;

        $I->assertTrue($evento->save(), 'O evento não foi salvo corretamente.');
        $I->seeRecord('common\models\Evento', ['titulo' => 'titulo']);

        // armazena o ID do evento para os próximos testes
        $this->eventoId = $evento->id;
    }


    // teste de vista de evento
    public function testReadEvento(FunctionalTester $I)
    {
        $evento = Evento::find()->where(['titulo' => 'titulo ao Vivo'])->one();
        $I->assertNotNull($evento, 'Evento não encontrado');

        // acessa a view do evento
        $I->amOnPage(['/evento/view', 'id' => $evento->id]);
        $I->see($evento->titulo);
        $I->see($evento->descricao);
        $I->see($evento->datainicio);
        $I->see($evento->datafim);
    }


    // teste de atualização de evento
    public function testUpdateEvento(FunctionalTester $I)
    {
        $evento = Evento::find()->where(['titulo' => 'titulo'])->one();
        $I->assertNotNull($evento, 'Evento não encontrado');

        // atualização do evento
        $evento->titulo = 'titulo2';
        $I->assertTrue($evento->save(), 'Falha ao atualizar o evento');

        $I->seeRecord('common\models\Evento', ['titulo' => 'titulo2']);
    }


    // teste de eliminação de evento
    public function testDeleteEvento(FunctionalTester $I)
    {
        $evento = Evento::find()->where(['titulo' => 'titulo2'])->one();
        $I->assertNotNull($evento, 'Evento não encontrado');

        $I->assertTrue($evento->delete(), 'Falha ao excluir o evento');

        $I->dontSeeRecord('common\models\Evento', ['titulo' => 'titulo2']);
    }
}