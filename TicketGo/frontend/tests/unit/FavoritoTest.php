<?php

namespace tests\unit;

use Codeception\Test\Unit;
use frontend\controllers\FavoritoController;
use Yii;
use common\models\Favorito;
use common\models\Evento;
use common\models\Profile;

class FavoritoTest extends Unit
{
    protected $controller;

    protected function _before()
    {
        // Create a mock user and log them in
        $profile = new Profile(['id' => 1]);
        $user = $this->getMockBuilder(\common\models\User::class)
            ->setMethods(['getProfile'])
            ->getMock();
        $user->method('getProfile')->willReturn($profile);

        Yii::$app->user->setIdentity($user);

        // Initialize the controller
        $this->controller = new FavoritoController('favorito', Yii::$app);
    }

    public function testToggleAddToFavorites()
    {
        // Create a mock event
        $evento = new Evento(['id' => 1]);
        $evento->save();

        // Simulate adding to favorites
        $this->controller->actionToggle($evento->id);

        // Check if the favorite was added
        $favorito = Favorito::findOne(['profile_id' => 1, 'evento_id' => $evento->id]);
        $this->assertNotNull($favorito, 'Favorito should be added to the database.');
    }

    public function testToggleRemoveFromFavorites()
    {
        // Create a mock event
        $evento = new Evento(['id' => 2]);
        $evento->save();

        // First, add the event to favorites
        $favorito = new Favorito(['profile_id' => 1, 'evento_id' => $evento->id]);
        $favorito->save();

        // Simulate removing from favorites
        $this->controller->actionToggle($evento->id);

        // Check if the favorite was removed
        $favorito = Favorito::findOne(['profile_id' => 1, 'evento_id' => $evento->id]);
        $this->assertNull($favorito, 'Favorito should be removed from the database.');
    }

    public function testToggleEventNotFound()
    {
        // Simulate trying to toggle a non-existent event
        $this->expectException(\yii\web\NotFoundHttpException::class);
        $this->controller->actionToggle(999); // Assuming 999 does not exist
    }

    public function testIndexFavorites()
    {
        // Create a mock event and add it to favorites
        $evento = new Evento(['id' => 3]);
        $evento->save();
        $favorito = new Favorito(['profile_id' => 1, 'evento_id' => $evento->id]);
        $favorito->save();

        // Call the index action
        $result = $this->controller->actionIndex();

        // Check if the result contains the favorite
        $this->assertContains($favorito, $result->getData()['favoritos'], 'The favorite should be in the index result.');
    }

    public function testIndexNoFavorites()
    {
        // Call the index action with no favorites
        $result = $this->controller->actionIndex();

        // Check if the result indicates no favorites
        $this->assertEmpty($result->getData()['favoritos'], 'The favorites list should be empty.');
    }
}