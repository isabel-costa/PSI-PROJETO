<?php

namespace tests\acceptance;

use Codeception\Util\Locator;

class EventoCest
{
    public function _before(AcceptanceTester $I)
    {
        $I->amOnPage('/site/login');
        $I->fillField('LoginForm[username]', 'admin');
        $I->fillField('LoginForm[password]', '1');
        $I->click('login-button');
        $I->see('Logout');
    }

    public function viewEventos(AcceptanceTester $I)
    {
        $I->amOnPage('/evento/index');
        $I->see('Eventos');
        $I->see('Create Evento');
    }

    public function createEvento(AcceptanceTester $I)
    {
        $I->amOnPage('/evento/create');
        $I->see('Create Evento');

        // Fill in the form
        $I->fillField('Evento[titulo]', 'Test Event');
        $I->fillField('Evento[descricao]', 'This is a test event.');
        $I->fillField('Evento[datainicio]', date('Y-m-d\TH:i'));
        $I->fillField('Evento[datafim]', date('Y-m-d\TH:i', strtotime('+1 day')));
        $I->selectOption('Evento[local_id]', '1');
        $I->selectOption('Evento[categoria_id]', '1');
        $I->attachFile('Evento[imagem_file]', 'path/to/your/test/image.jpg');
        $I->click('Save');

        $I->see('Evento criado com sucesso!');
        $I->see('Test Event');
    }

    public function updateEvento(AcceptanceTester $I)
    {
        $I->amOnPage('/evento/index');
        $I->see('Eventos');

        $I->click('Update', Locator::first('a'));

        $I->fillField('Evento[titulo]', 'Updated Test Event');
        $I->fillField('Evento[descricao]', 'This is an updated test event.');
        $I->click('Save');

        // Check for success message
        $I->see('Evento atualizado com sucesso!');
        $I->see('Updated Test Event');
    }

    public function deleteEvento(AcceptanceTester $I)
    {
        $I->amOnPage('/evento/index');
        $I->see('Eventos');

        $I->click('Delete', Locator::first('a'));

        $I->acceptPopup();

        $I->see('Evento deletado com sucesso!');
        $I->dontSee('Updated Test Event');
    }

    public function viewEventDetails(AcceptanceTester $I)
    {
        $I->amOnPage('/evento/index');
        $I->see('Eventos');

        $I->click('View', Locator::first('a'));

        $I->see('Event Details');
        $I->see('Test Event');
        $I->see('This is a test event.');
        $I->see(date('Y-m-d\TH:i'));
        $I->see(date('Y-m-d\TH:i', strtotime('+1 day')));
        $I->see('Local:');
        $I->see('Categoria:');
    }
}