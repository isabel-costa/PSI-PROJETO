<?php

namespace tests\acceptance;

use Codeception\Util\Locator;

class EventoCest
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

    public function viewEventList(AcceptanceTester $I)
    {
        // Navigate to the event list page
        $I->amOnPage('/evento/index');
        $I->see('Lista de Eventos'); // Check if the page title is displayed
        $I->see('Encontre todos os eventos disponíveis e garante já o teu bilhete!'); // Check for description
    }

    public function searchEvents(AcceptanceTester $I)
    {
        // Navigate to the product list page with a search term
        $I->amOnPage('/evento/product-list?search=Hibrid'); // Replace with a valid search term
        $I->see('Lista de Eventos'); // Check if the page title is displayed
        $I->see('Hibrid'); // Check if the search term is displayed in the results
    }

    public function viewEventDetails(AcceptanceTester $I)
    {
        // Navigate to the product detail page for a specific event
        $I->amOnPage('/evento/product-detail?id=9'); // Replace with a valid event ID
        $I->see('Descrição Do Evento'); // Check if the description section is present
        $I->see('Escolha a sua Plateia:'); // Check if the seating selection is present
    }

    public function toggleFavorite(AcceptanceTester $I)
    {
        // Navigate to the product detail page for a specific event
        $I->amOnPage('/evento/product-detail?id=9'); // Replace with a valid event ID
        $I->click('i.fa-heart'); // Click on the heart icon to toggle favorite
        $I->see('Evento adicionado aos favoritos.'); // Check for success message
    }

    public function toggleFavoriteAsGuest(AcceptanceTester $I)
    {
        // Log out
        $I->amOnPage('/site/logout');

        // Try to toggle favorite while logged out
        $I->amOnPage('/evento/product-detail?id=9'); // Replace with a valid event ID
        $I->click('i.fa-heart'); // Click on the heart icon
        $I->see('Você precisa estar logado para adicionar aos favoritos.'); // Check for error message
    }

    public function viewFeaturedEvents(AcceptanceTester $I)
    {
        // Navigate to the event list page
        $I->amOnPage('/evento/index');
        $I->see('Eventos em Destaque'); // Check if the featured events section is present
    }
}