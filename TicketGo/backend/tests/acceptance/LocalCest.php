<?php

namespace tests\acceptance;

use Codeception\Util\Locator;

class LocalCest
{
    public function _before(AcceptanceTester $I)
    {
        // Log in before each test (if authentication is required)
        $I->amOnPage('/site/login');
        $I->fillField('LoginForm[username]', 'your_username'); // Replace with a valid username
        $I->fillField('LoginForm[password]', 'your_password'); // Replace with a valid password
        $I->click('login-button');
        $I->see('Logout'); // Check if login was successful
    }

    public function viewLocals(AcceptanceTester $I)
    {
        // Navigate to the index page of Local
        $I->amOnPage('/local/index');
        $I->see('Locals'); // Check if the title is present
        $I->see('Create Local'); // Check if the create button is present
    }

    public function createLocal(AcceptanceTester $I)
    {
        // Navigate to the create page
        $I->amOnPage('/local/create');
        $I->see('Create Local'); // Check if the title is present

        // Fill in the form
        $I->fillField('Local[nome]', 'Test Local');
        $I->fillField('Local[morada]', '123 Test Street');
        $I->fillField('Local[cidade]', 'Test City');
        $I->fillField('Local[capacidade]', '100');
        $I->click('Save'); // Submit the form

        // Check for success message
        $I->see('Local created successfully!'); // Check for success message
        $I->see('Test Local'); // Check if the new local is listed
    }

    public function updateLocal(AcceptanceTester $I)
    {
        // Navigate to the index page
        $I->amOnPage('/local/index');
        $I->see('Locals'); // Check if the title is present

        // Click on the update link for the first local
        $I->click('Update', Locator::first('a')); // Assuming the first local is to be updated

        // Fill in the form with new data
        $I->fillField('Local[nome]', 'Updated Test Local');
        $I->fillField('Local[morada]', '456 Updated Street');
        $I->fillField('Local[cidade]', 'Updated City');
        $I->fillField('Local[capacidade]', '150');
        $I->click('Save'); // Submit the form

        // Check for success message
        $I->see('Local updated successfully!'); // Check for success message
        $I->see('Updated Test Local'); // Check if the updated local is listed
    }

    public function deleteLocal(AcceptanceTester $I)
    {
        // Navigate to the index page
        $I->amOnPage('/local/index');
        $I->see('Locals'); // Check if the title is present

        // Click on the delete link for the first local
        $I->click('Delete', Locator::first('a')); // Assuming the first local is to be deleted

        // Confirm the deletion
        $I->acceptPopup(); // Accept the confirmation dialog

        // Check for success message
        $I->see('Local deleted successfully!'); // Check for success message
        $I->dontSee('Updated Test Local'); // Check if the deleted local is no longer listed
    }

    public function viewLocalDetails(AcceptanceTester $I)
    {
        // Navigate to the index page
        $I->amOnPage('/local/index');
        $I->see('Locals'); // Check if the title is present

        // Click on the view link for the first local
        $I->click('View', Locator::first('a')); // Assuming the first local is to be viewed

        // Check if the local details are displayed
        $I->see('Local Details'); // Check for the local details title
        $I->see('Test Local'); // Check if the local name is displayed
        $I->see('123 Test Street'); // Check if the address is displayed
        $I->see('Test City'); // Check if the city is displayed
        $I->see('100'); // Check if the capacity is displayed
    }
}