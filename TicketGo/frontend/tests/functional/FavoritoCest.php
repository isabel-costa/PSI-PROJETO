<?php

namespace tests\functional;

use Codeception\Util\Fixtures;

class FavoritoCest
{
    public function _before(FunctionalTester $I)
    {
        // Log in before each test
        $I->amLoggedInAs(1); // Assuming user with ID 1 is a valid user
    }

    public function testAddEventToFavorites(FunctionalTester $I)
    {
        // Create a new event for testing
        $eventoId = $I->haveInDatabase('Eventos', [
            'id' => 1,
            'titulo' => 'Test Event',
            'imagemUrl' => 'path/to/image.jpg',
            // Add other necessary fields
        ]);

        // Add to favorites
        $I->sendPOST('/favorito/toggle', ['eventoId' => $eventoId]);
        $I->seeInDatabase('Favoritos', [
            'profile_id' => 1, // Assuming profile ID is 1
            'evento_id' => $eventoId,
        ]);
        $I->see('Evento adicionado aos favoritos.');
    }

    public function testRemoveEventFromFavorites(FunctionalTester $I)
    {
        // Create a new event for testing
        $eventoId = $I->haveInDatabase('Eventos', [
            'id' => 2,
            'titulo' => 'Test Event 2',
            'imagemUrl' => 'path/to/image2.jpg',
            // Add other necessary fields
        ]);

        // Add to favorites first
        $I->sendPOST('/favorito/toggle', ['eventoId' => $eventoId]);

        // Now remove from favorites
        $I->sendPOST('/favorito/toggle', ['eventoId' => $eventoId]);
        $I->dontSeeInDatabase('Favoritos', [
            'profile_id' => 1, // Assuming profile ID is 1
            'evento_id' => $eventoId,
        ]);
        $I->see('Evento removido dos favoritos.');
    }

    public function testViewFavorites(FunctionalTester $I)
    {
        // Create a new event and add it to favorites
        $eventoId = $I->haveInDatabase('Eventos', [
            'id' => 3,
            'titulo' => 'Test Event 3',
            'imagemUrl' => 'path/to/image3.jpg',
            // Add other necessary fields
        ]);
        $I->sendPOST('/favorito/toggle', ['eventoId' => $eventoId]);

        // Now check the favorites
        $I->amOnPage('/favorito/index');
        $I->see('Os Seus Favoritos');
        $I->see('Test Event 3'); // Check if the event title is displayed
    }

    public function testAddEventToFavoritesAsGuest(FunctionalTester $I)
    {
        // Log out
        $I->amLoggedOut();

        // Try to add an event to favorites while logged out
        $eventoId = 1; // Assuming this event exists
        $I->sendPOST('/favorito/toggle', ['eventoId' => $eventoId]);
        $I->see('É necessário fazer login para adicionar um evento aos favoritos.');
    }

    public function testViewFavoritesAsGuest(FunctionalTester $I)
    {
        // Log out
        $I->amLoggedOut();

        // Try to view favorites while logged out
        $I->amOnPage('/favorito/index');
        $I->see('É necessário fazer login para aceder aos favoritos.');
    }
}