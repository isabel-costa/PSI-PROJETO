<?php

namespace tests\functional;

use Codeception\Util\Fixtures;

class FavoritoCest
{
    public function _before(FunctionalTester $I)
    {
        $I->amLoggedInAs(1);
    }

    public function testAddEventToFavorites(FunctionalTester $I)
    {
        $eventoId = $I->haveInDatabase('Eventos', [
            'id' => 1,
            'titulo' => 'Test Event',
            'imagemUrl' => 'path/to/image.jpg',
        ]);

        $I->sendPOST('/favorito/toggle', ['eventoId' => $eventoId]);
        $I->seeInDatabase('Favoritos', [
            'profile_id' => 1,
            'evento_id' => $eventoId,
        ]);
        $I->see('Evento adicionado aos favoritos.');
    }

    public function testRemoveEventFromFavorites(FunctionalTester $I)
    {
        $eventoId = $I->haveInDatabase('Eventos', [
            'id' => 2,
            'titulo' => 'Test Event 2',
            'imagemUrl' => 'path/to/image2.jpg',
        ]);

        $I->sendPOST('/favorito/toggle', ['eventoId' => $eventoId]);

        $I->sendPOST('/favorito/toggle', ['eventoId' => $eventoId]);
        $I->dontSeeInDatabase('Favoritos', [
            'profile_id' => 1,
            'evento_id' => $eventoId,
        ]);
        $I->see('Evento removido dos favoritos.');
    }

    public function testViewFavorites(FunctionalTester $I)
    {
        $eventoId = $I->haveInDatabase('Eventos', [
            'id' => 3,
            'titulo' => 'Test Event 3',
            'imagemUrl' => 'path/to/image3.jpg',
        ]);
        $I->sendPOST('/favorito/toggle', ['eventoId' => $eventoId]);

        // Now check the favorites
        $I->amOnPage('/favorito/index');
        $I->see('Os Seus Favoritos');
        $I->see('Test Event 3');
    }

    public function testAddEventToFavoritesAsGuest(FunctionalTester $I)
    {
        $I->amLoggedOut();

        $eventoId = 1;
        $I->sendPOST('/favorito/toggle', ['eventoId' => $eventoId]);
        $I->see('É necessário fazer login para adicionar um evento aos favoritos.');
    }

    public function testViewFavoritesAsGuest(FunctionalTester $I)
    {
        $I->amLoggedOut();

        $I->amOnPage('/favorito/index');
        $I->see('É necessário fazer login para aceder aos favoritos.');
    }
}