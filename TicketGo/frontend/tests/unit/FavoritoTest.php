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
        $profile = new Profile(['id' => 1]);
        $user = $this->getMockBuilder(\common\models\User::class)
            ->setMethods(['getProfile'])
            ->getMock();
        $user->method('getProfile')->willReturn($profile);

        Yii::$app->user->setIdentity($user);

        $this->controller = new FavoritoController('favorito', Yii::$app);
    }

    public function testToggleAddToFavorites()
    {
        $evento = new Evento(['id' => 1]);
        $evento->save();

        $this->controller->actionToggle($evento->id);

        $favorito = Favorito::findOne(['profile_id' => 1, 'evento_id' => $evento->id]);
        $this->assertNotNull($favorito, 'Favorito should be added to the database.');
    }

    public function testToggleRemoveFromFavorites()
    {
        $evento = new Evento(['id' => 2]);
        $evento->save();

        $favorito = new Favorito(['profile_id' => 1, 'evento_id' => $evento->id]);
        $favorito->save();

        $this->controller->actionToggle($evento->id);

        $favorito = Favorito::findOne(['profile_id' => 1, 'evento_id' => $evento->id]);
        $this->assertNull($favorito, 'Favorito should be removed from the database.');
    }

    public function testToggleEventNotFound()
    {
        $this->expectException(\yii\web\NotFoundHttpException::class);
        $this->controller->actionToggle(999);
    }

    public function testIndexFavorites()
    {
        $evento = new Evento(['id' => 3]);
        $evento->save();
        $favorito = new Favorito(['profile_id' => 1, 'evento_id' => $evento->id]);
        $favorito->save();

        $result = $this->controller->actionIndex();

        $this->assertContains($favorito, $result->getData()['favoritos'], 'The favorite should be in the index result.');
    }

    public function testIndexNoFavorites()
    {
        $result = $this->controller->actionIndex();

        $this->assertEmpty($result->getData()['favoritos'], 'The favorites list should be empty.');
    }
}