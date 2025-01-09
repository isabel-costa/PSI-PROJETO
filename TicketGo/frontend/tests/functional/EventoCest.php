<?php

namespace tests\functional;

use frontend\controllers\EventoController;
use Yii;
use common\models\Evento;
use common\models\Favorito;

class EventoCest
{
    protected $controller;

    protected function _before()
    {
        $user = $this->getMockBuilder(\common\models\User::class)
            ->setMethods(['getProfile'])
            ->getMock();
        $user->method('getProfile')->willReturn((object)['id' => 1]);

        Yii::$app->user->setIdentity($user);

        $this->controller = new EventoController('evento', Yii::$app);
    }

    public function testIndex()
    {
        $result = $this->controller->actionIndex();

        $this->assertInstanceOf(\yii\web\View::class, $result);
        $this->assertArrayHasKey('dataProvider', $result->getData());
    }

    public function testProductListWithSearch()
    {
        $evento1 = new Evento(['id' => 1, 'titulo' => 'Concert']);
        $evento1->save();

        $evento2 = new Evento(['id' => 2, 'titulo' => 'Theater']);
        $evento2->save();

        $result = $this->controller->actionProductList('Concert');

        $this->assertArrayHasKey('eventos', $result->getData());
        $this->assertCount(1, $result->getData()['eventos']);
        $this->assertEquals('Concert', $result->getData()['eventos'][0]->titulo);
    }

    public function testProductDetail()
    {
        $evento = new Evento(['id' => 3, 'titulo' => 'Test Event']);
        $evento->save();

        $result = $this->controller->actionProductDetail($evento->id);

        $this->assertInstanceOf(\yii\web\View::class, $result);
        $this->assertArrayHasKey('evento', $result->getData());
        $this->assertEquals('Test Event', $result->getData()['evento']->titulo);
    }

    public function testToggleFavoriteAdd()
    {
        $evento = new Evento(['id' => 4, 'titulo' => 'Favorite Event']);
        $evento->save();

        $this->controller->actionToggleFavorite($evento->id);

        $favorito = Favorito::findOne(['profile_id' => 1, 'evento_id' => $evento->id]);
        $this->assertNotNull($favorito, 'Favorito should be added to the database.');
    }

    public function testToggleFavoriteRemove()
    {
        $evento = new Evento(['id' => 5, 'titulo' => 'Another Favorite Event']);
        $evento->save();

        $favorito = new Favorito(['profile_id' => 1, 'evento_id' => $evento->id]);
        $favorito->save();

        $this->controller->actionToggleFavorite($evento->id);

        $favorito = Favorito::findOne(['profile_id' => 1, 'evento_id' => $evento->id]);
        $this->assertNull($favorito, 'Favorito should be removed from the database.');
    }

    public function testToggleFavoriteEventNotFound()
    {
        $this->expectException(\yii\web\NotFoundHttpException::class);
        $this->controller->actionToggleFavorite(999);
    }

    public function testToggleFavoriteAsGuest()
    {
        Yii::$app->user->logout();

        $evento = new Evento(['id' => 6, 'titulo' => 'Guest Favorite Event']);
        $evento->save();

        $this->controller->actionToggleFavorite($evento->id);

        $this->assertEquals(Yii::$app->response->statusCode, 302);
        $this->assertEquals(Yii::$app->request->url, '/site/login');
    }

    public function testProductDetailWithInvalidId()
    {
        $this->expectException(\yii\web\NotFoundHttpException::class);
        $this->controller->actionProductDetail(999);
    }
}