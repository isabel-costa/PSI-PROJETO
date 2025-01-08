<?php

namespace tests\acceptance;

use Codeception\Util\Locator;

class ZonaCest
{
    public function _before(AcceptanceTester $I)
    {
        // Log in before each test (if authentication is required)
        $I->amOnPage('/site/login');
        $I->fillField('LoginForm[username]', 'admin'); // Replace with a valid admin username
        $I->fillField('LoginForm[password]', 'admin_password'); // Replace with a valid admin password
        $I->click('login-button');
        $I->see('Logout'); // Check if login was successful
    }

    public function viewZones(AcceptanceTester $I)
    {
        // Navigate to the index page of Zones
        $I->amOnPage('/zona/index?local_id=1'); // Assuming local_id=1 exists
        $I->see('Zonas de'); // Check if the title is present
        $I->see('Create Zona'); // Check if the create button is present
    }

    public function createZone(AcceptanceTester $I)
    {
        // Navigate to the create page
        $I->amOnPage('/zona/create?local_id=1'); // Assuming local_id=1 exists
        $I->see('Create Zona'); // Check if the title is present

        // Fill in the form
        $I->fillField('Zona[lugar]', 'Test Zone');
        $I->fillField('Zona[porta]', '1');
        $I->fillField('Zona[quantidadedisponivel]', '100');
        $I->click('Save'); // Submit the form

        // Check for success message
        $I->see('Zona criada com sucesso.'); // Check for success message
        $I->see('Test Zone'); // Check if the new zone is listed
    }

    public function updateZone(AcceptanceTester $I)
    {
        // Navigate to the index page
        $I->amOnPage('/zona/index?local_id=1'); // Assuming local_id=1 exists
        $I->see('Zonas de'); // Check if the title is present

        // Click on the update link for the first zone
        $I->click('Update', Locator::first('a')); // Assuming the first zone is to be updated

        // Fill in the form with new data
        $I->fillField('Zona[lugar]', 'Updated Test Zone');
        $I->fillField('Zona[porta]', '2');
        $I->fillField('Zona[quantidadedisponivel]', '150');
        $I->click('Save'); // Submit the form

        // Check for success message
        $I->see('Zona atualizada com sucesso!'); // Check for success message
        $I->see('Updated Test Zone'); // Check if the updated zone is listed
    }

    public function deleteZone(AcceptanceTester $I)
    {
        // Navigate to the index page
        $I->amOnPage('/zona/index?local_id=1'); // Assuming local_id=1 exists
        $I->see('Zonas de'); // Check if the title is present

        // Click on the delete link for the first zone
        $I->click('Delete', Locator::first('a')); // Assuming the first zone is to be deleted

        // Confirm the deletion
        $I->acceptPopup(); // Accept the confirmation dialog

        // Check for success message
        $I->see('Zona eliminada com sucesso!'); // Check for success message
        $I->dontSee('Updated Test Zone'); // Check if the deleted zone is no longer listed
    }

    public function viewZoneDetails(AcceptanceTester $I)
    {
        // Navigate to the index page
        $I->amOnPage('/zona/index?local_id=1'); // Assuming local_id=1 exists
        $I->see('Zonas de'); // Check if the title is present

        // Click on the view link for the first zone
        $I->click('View', Locator::first('a')); // Assuming the first zone is to be viewed

        // Check if the zone details are displayed
        $I->see('Zona Details'); // Check for the zone details title
        $I->see('Test Zone'); // Check if the zone name is displayed
        $I->see('1'); // Check if the porta is displayed
        $I->see('100'); // Check if the quantidadedisponivel is displayed
        // Add any other fields you want to verify
    }
}