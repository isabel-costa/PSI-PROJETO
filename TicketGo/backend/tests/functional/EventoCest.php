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
        // Set up necessary data for tests
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
        // Simulate a request to the index action
        $response = Yii::$app->runAction('evento/index');

        // Check if the response is valid
        $this->assertNotNull($response);
        $this->assertContains('Eventos', $response); // Check if the title is present
    }

    public function testCreateEvento()
    {
        // Simulate a request to the create action
        $response = Yii::$app->runAction('evento/create', [
            'Evento' => [
                'titulo' => 'New Test Event',
                'descricao' => 'This is a new test event.',
                'datainicio' => date('Y-m-d H:i:s'),
                'datafim' => date('Y-m-d H:i:s', strtotime('+2 days')),
                'local_id' => 1, // Assuming this local ID exists
                'categoria_id' => 1, // Assuming this category ID exists
            ],
        ]);

        // Check for success message
        $this->assertContains('Evento criado com sucesso!', $response); // Check for success message
        $this->assertContains('New Test Event', $response); // Check if the new event is listed
    }

    public function testUpdateEvento()
    {
        // Get the first event
        $evento = Evento::find()->one(); // Get the first event

        // Simulate a request to the update action
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

        // Check for success message
        $this->assertContains('Evento atualizado com sucesso!', $response); // Check for success message
        $this->assertContains('Updated Test Event', $response); // Check if the updated event is listed
    }

    public function testDeleteEvento()
    {
        // Get the first event
        $evento = Evento::find()->one(); // Get the first event

        // Simulate a request to the delete action
        Yii::$app->runAction('evento/delete', ['id' => $evento->id]);

        // Check that the event no longer exists
        $this->assertNull(Evento::findOne($evento->id), 'The event should be deleted.');
    }

    public function testViewEventDetails()
    {
        // Get the first event
        $evento = Evento::find()->one(); // Get the first event

        // Simulate a request to the view action
        $response = Yii::$app->runAction('evento/view', ['id' => $evento->id]);

        // Check if the event details are displayed
        $this->assertContains('Event Details', $response); // Check for the event details title
        $this->assertContains($evento->titulo, $response); // Check if the event title is displayed
        $this->assertContains($evento->descricao, $response); // Check if the event description is displayed
        $this->assertContains($evento->datainicio, $response); // Check if the start date is displayed
        $this->assertContains($evento->datafim, $response); // Check if the end date is displayed
        $this->assertContains('Local:', $response); // Check if the local label is displayed
        $this->assertContains('Categoria:', $response); // Check if the category label is displayed
    }
}