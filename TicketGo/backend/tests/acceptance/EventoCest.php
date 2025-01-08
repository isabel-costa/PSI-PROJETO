<?php

namespace tests\acceptance;

use Codeception\Util\Locator;

class EventoCest
{
    public function _before(AcceptanceTester $I)
    {
        // Log in before each test (if authentication is required)
        $I->amOnPage('/site/login');
        $I->fillField('LoginForm[username]', 'admin'); // Replace with a valid username
        $I->fillField('LoginForm[password]', '1'); // Replace with a valid password
        $I->click('login-button');
        $I->see('Logout'); // Check if login was successful
    }

    public function viewEventos(AcceptanceTester $I)
    {
        // Navigate to the index page of Evento
        $I->amOnPage('/evento/index');
        $I->see('Eventos'); // Check if the title is present
        $I->see('Create Evento'); // Check if the create button is present
    }

    public function createEvento(AcceptanceTester $I)
    {
        // Navigate to the create page
        $I->amOnPage('/evento/create');
        $I->see('Create Evento'); // Check if the title is present

        // Fill in the form
        $I->fillField('Evento[titulo]', 'Test Event');
        $I->fillField('Evento[descricao]', 'This is a test event.');
        $I->fillField('Evento[datainicio]', date('Y-m-d\TH:i')); // Set start date
        $I->fillField('Evento[datafim]', date('Y-m-d\TH:i', strtotime('+1 day'))); // Set end date
        $I->selectOption('Evento[local_id]', '1'); // Replace with a valid local ID
        $I->selectOption('Evento[categoria_id]', '1'); // Replace with a valid categoria ID
        $I->attachFile('Evento[imagem_file]', 'path/to/your/test/image.jpg'); // Replace with a valid image path
        $I->click('Save'); // Submit the form

        // Check for success message
        $I->see('Evento criado com sucesso!'); // Check for success message
        $I->see('Test Event'); // Check if the new event is listed
    }

    public function updateEvento(AcceptanceTester $I)
    {
        // Navigate to the index page
        $I->amOnPage('/evento/index');
        $I->see('Eventos'); // Check if the title is present

        // Click on the update link for the first event
        $I->click('Update', Locator::first('a')); // Assuming the first event is to be updated

        // Fill in the form with new data
        $I->fillField('Evento[titulo]', 'Updated Test Event');
        $I->fillField('Evento[descricao]', 'This is an updated test event.');
        $I->click('Save'); // Submit the form

        // Check for success message
        $I->see('Evento atualizado com sucesso!'); // Check for success message
        $I->see('Updated Test Event'); // Check if the updated event is listed
    }

    public function deleteEvento(AcceptanceTester $I)
    {
        // Navigate to the index page
        $I->amOnPage('/evento/index');
        $I->see('Eventos'); // Check if the title is present

        // Click on the delete link for the first event
        $I->click('Delete', Locator::first('a')); // Assuming the first event is to be deleted

        // Confirm the deletion
        $I->acceptPopup(); // Accept the confirmation dialog

        // Check for success message
        $I->see('Evento deletado com sucesso!'); // Check for success message
        $I->dontSee('Updated Test Event'); // Check if the deleted event is no longer listed
    }

    public function viewEventDetails(AcceptanceTester $I)
    {
        // Navigate to the index page
        $I->amOnPage('/evento/index');
        $I->see('Eventos'); // Check if the title is present

        // Click on the view link for the first event
        $I->click('View', Locator::first('a')); // Assuming the first event is to be viewed

        // Check if the event details are displayed
        $I->see('Event Details'); // Check for the event details title
        $I->see('Test Event'); // Check if the event title is displayed
        $I->see('This is a test event.'); // Check if the event description is displayed
        $I->see(date('Y-m-d\TH:i')); // Check if the start date is displayed
        $I->see(date('Y-m-d\TH:i', strtotime('+1 day'))); // Check if the end date is displayed
        $I->see('Local:'); // Check if the local label is displayed
        $I->see('Categoria:'); // Check if the categoria label is displayed
    }
}