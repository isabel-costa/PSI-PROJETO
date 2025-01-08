<?php

namespace tests\acceptance;

use Codeception\Util\Locator;

class FavoritoCest
{
    public function _before(AcceptanceTester $I)
    {
        // Log in before each test
        $I->amOnPage('/site/login');
        $I->fillField('LoginForm[username]', 'isabel'); // Replace with a valid username
        $I->fillField('LoginForm[password]', 'isabel123'); // Replace with a valid password
        $I->click('login-button');
        $I->see('Logout'); // Check if login was successful
    }

    public function addEventToFavorites(AcceptanceTester $I)
    {
        // Navigate to an event page
        $I->amOnPage('/evento/view?id=9'); // Replace with a valid event ID
        $I->click('.fa-heart'); // Assuming this is the button text
        $I->see('Evento adicionado aos favoritos.'); // Check for success message
    }

    public function removeEventFromFavorites(AcceptanceTester $I)
    {
        // Navigate to the favorites page
        $I->amOnPage('/favorito/index');
        $I->see('Os Seus Favoritos'); // Check if the favorites page is loaded

        // Remove an event from favorites
        $I->click('Remover dos Favoritos'); // Assuming this is the button text
        $I->see('Evento removido dos favoritos.'); // Check for success message
    }

    public function viewFavorites(AcceptanceTester $I)
    {
        // Navigate to the favorites page
        $I->amOnPage('/favorito/index');
        $I->see('Os Seus Favoritos'); // Check if the favorites page is loaded
        $I->see('Hibrid Theory'); // Replace with a valid event title
    }

    public function addEventToFavoritesAsGuest(AcceptanceTester $I)
    {
        // Log out
        $I->amOnPage('/site/logout');

        // Try to add an event to favorites while logged out
        $I->amOnPage('/evento/view?id=1'); // Replace with a valid event ID
        $I->click('Add to Favorites'); // Assuming this is the button text
        $I->see('É necessário fazer login para adicionar um evento aos favoritos.'); // Check for error message
    }

    public function viewFavoritesAsGuest(AcceptanceTester $I)
    {
        // Log out
        $I->amOnPage('/site/logout');

        // Try to view favorites while logged out
        $I->amOnPage('/favorito/index');
        $I->see('É necessário fazer login para aceder aos favoritos.'); // Check for error message
    }

    public function addRemoveFavoritesWithoutPermission(AcceptanceTester $I)
    {
        // Log in as a user without permission
        $I->amOnPage('/site/login');
        $I->fillField('LoginForm[username]', 'user_without_permission'); // Replace with a valid username
        $I->fillField('LoginForm[password]', 'password'); // Replace with a valid password
        $I->click('login-button');
        $I->see('Logout'); // Check if login was successful

        // Try to add an event to favorites
        $I->amOnPage('/evento/view?id=1'); // Replace with a valid event ID
        $I->click('Add to Favorites'); // Assuming this is the button text
        $I->see('Você não tem permissão para adicionar ou remover eventos dos favoritos.'); // Check for error message
    }
}