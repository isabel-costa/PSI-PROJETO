<?php

namespace tests\functional;

use common\models\Evento;
use common\models\Categoria;
use common\models\Local;
use Yii;

class EventoCest
{
    protected function _before()
    {
        $local = new Local(['id' => 1, 'nome' => 'Test Local']);
        $local->save();

        $categoria = new Categoria(['id' => 1, 'nome' => 'Test Categoria']);
        $categoria->save();

        $evento = new Evento([
            'titulo' => 'Initial Test Event',
            'descricao' => 'This is an initial test event.',
            'datainicio' => date('Y-m-d H:i:s'),
            'datafim' => date('Y-m-d H:i:s', strtotime('+1 day')),
            'local_id' => $local->id,
            'categoria_id' => $categoria->id,
        ]);
        $evento->save();
    }

    public function testIndex()
    {
        $response = Yii::$app->runAction('evento/index');

        $this->assertNotNull($response);
        $this->assertContains('Eventos', $response);
    }

    public function testCreateEvento()
    {
        $response = Yii::$app->runAction('evento/create', [
            'Evento' => [
                'titulo' => 'New Test Event',
                'descricao' => 'This is a new test event.',
                'datainicio' => date('Y-m-d H:i:s'),
                'datafim' => date('Y-m-d H:i:s', strtotime('+2 days')),
                'local_id' => 1,
                'categoria_id' => 1,
            ],
        ]);

        $this->assertContains('Evento criado com sucesso!', $response);
        $this->assertContains('New Test Event', $response);
    }

    public function testUpdateEvento()
    {
        $evento = Evento::find()->one();

        $response = Yii::$app->runAction('evento/update', [
            'id' => $evento->id,
            'Evento' => [
                'titulo' => 'Updated Test Event',
                'descricao' => 'This is an updated test event.',
                'datainicio' => date('Y-m-d H:i:s'),
                'datafim' => date('Y-m-d H:i:s', strtotime('+3 days')),
                'local_id' => 1,
                'categoria_id' => 1,
            ],
        ]);

        $this->assertContains('Evento atualizado com sucesso!', $response);
        $this->assertContains('Updated Test Event', $response);
    }

    public function testDeleteEvento()
    {
        $evento = Evento::find()->one();

        Yii::$app->runAction('evento/delete', ['id' => $evento->id]);

        $this->assertNull(Evento::findOne($evento->id), 'The event should be deleted.');
    }

    public function testViewEventDetails()
    {
        $evento = Evento::find()->one();

        $response = Yii::$app->runAction('evento/view', ['id' => $evento->id]);

        $this->assertContains('Event Details', $response);
        $this->assertContains($evento->titulo, $response);
        $this->assertContains($evento->descricao, $response);
        $this->assertContains($evento->datainicio, $response);
        $this->assertContains($evento->datafim, $response);
        $this->assertContains('Local:', $response);
        $this->assertContains('Categoria:', $response);
    }
}