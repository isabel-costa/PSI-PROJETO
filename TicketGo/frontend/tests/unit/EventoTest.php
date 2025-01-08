<?php

namespace tests\unit;

use Codeception\Test\Unit;
use frontend\controllers\EventoController;
use Yii;
use common\models\Evento;
use common\models\Favorito;
use yii\web\NotFoundHttpException;

class EventoControllerTest extends Unit
{
    protected $controller;

    protected function _before()
    {
        // Create a mock user and log them in
        $user = $this->getMockBuilder(\common\models\User::class)
            ->setMethods(['getProfile'])
            ->getMock();
        $user->method('getProfile')->willReturn((object)['id' => 1]);

        Yii::$app->user->setIdentity($user);

        // Initialize the controller
        $this->controller = new EventoController('evento', Yii::$app);
    }

    public function testIndex()
    {
        // Simulate accessing the index action
        $result = $this->controller->actionIndex();

        // Check if the result is a valid response
        $this->assertInstanceOf(\yii\web\View::class, $result);
        $this->assertArrayHasKey('dataProvider', $result->getData());
    }

    public function testProductListWithSearch()
    {
        // Create mock events
        $evento1 = new Evento(['id' => 1, 'titulo' => 'Concert']);
        $evento1->save();

        $evento2 = new Evento(['id' => 2, 'titulo' => 'Theater']);
        $evento2->save();

        // Simulate searching for events
        $result = $this->controller->actionProductList('Concert');

        // Check if the result contains the searched event
        $this->assertArrayHasKey('eventos', $result->getData());
        $this->assertCount(1, $result->getData()['eventos']);
        $this->assertEquals('Concert', $result->getData()['eventos'][0]->titulo);
    }

    public function testProductDetail()
    {
        // Create a mock event
        $evento = new Evento(['id' => 3, 'titulo' => 'Test Event']);
        $evento->save();

        // Simulate accessing the product detail action
        $result = $this->controller->actionProductDetail($evento->id);

        // Check if the result is a valid response
        $this->assertInstanceOf(\yii\web\View::class, $result);
        $this->assertArrayHasKey('evento', $result->getData());
        $this->assertEquals('Test Event', $result->getData()['evento']->titulo);
    }

    public function testToggleFavoriteAdd()
    {
        // Create a mock event
        $evento = new Evento(['id' => 4, 'titulo' => 'Favorite Event']);
        $evento->save();

        // Simulate toggling favorite
        $this->controller->actionToggleFavorite($evento->id);

        // Check if the favorite was added
        $favorito = Favorito::findOne(['profile_id' => 1, 'evento_id' => $evento->id]);
        $this->assertNotNull($favorito, 'Favorito should be added to the database.');
    }

    public function testToggleFavoriteRemove()
    {
        // Create a mock event
        $evento = new Evento(['id' => 5, 'titulo' => 'Another Favorite Event']);
        $evento->save();

        // Add the event to favorites first
        $favorito = new Favorito(['profile_id' => 1, 'evento_id' => $evento->id]);
        $favorito->save();

        // Simulate toggling favorite to remove it
        $this->controller->actionToggleFavorite($evento->id);

        // Check if the favorite was removed
        $favorito = Favorito::findOne(['profile_id' => 1, 'evento_id' => $evento->id]);
        $this->assertNull($favorito, 'Favorito should be removed from the database.');
    }

    public function testToggleFavoriteEventNotFound()
    {
        // Simulate trying to toggle a non-existent event
        $this->expectException(NotFoundHttpException::class);
        $this->controller->actionToggleFavorite(999); // Assuming 999 does not exist
    }

    public function testToggleFavoriteAsGuest()
    {
        // Log out the user
        Yii::$app->user->logout();

        // Create a mock event
        $evento = new Evento(['id' => 6, 'titulo' => 'Guest Favorite Event']);
        $evento->save();

        // Try to toggle favorite while logged out
        $this->controller->actionToggleFavorite($evento->id);

        // Check if the user is redirected to the login page
        $this->assertEquals(Yii::$app->response->statusCode, 302); // Check for redirect
        $this->assertEquals(Yii::$app->request->url, '/site/login'); // Check if redirected to login
    }

    public function testProductDetailWithInvalidId()
    {
        // Simulate accessing the product detail action with an invalid ID
        $this->expectException(NotFoundHttpException::class);
        $this->controller->actionProductDetail(999); // Assuming 999 does not exist
    }
}