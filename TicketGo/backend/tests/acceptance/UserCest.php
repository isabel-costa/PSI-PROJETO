<?php

namespace tests\acceptance;

use Codeception\Util\Locator;

class UserCest
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

    public function viewUsers(AcceptanceTester $I)
    {
        // Navigate to the index page of Users
        $I->amOnPage('/user/index');
        $I->see('Users'); // Check if the title is present
        $I->see('Create User'); // Check if the create button is present
    }

    public function createUser(AcceptanceTester $I)
    {
        // Navigate to the create page
        $I->amOnPage('/user/create');
        $I->see('Create User'); // Check if the title is present

        // Fill in the form
        $I->fillField('User [username]', 'TestUser ');
        $I->fillField('User [email]', 'testuser@example.com');
        $I->fillField('User [password_hash]', 'password123'); // Assuming this is the password field
        $I->selectOption('User [role]', 'admin'); // Assuming 'admin' is a valid role
        $I->click('Create'); // Submit the form

        // Check for success message
        $I->see('Utilizador criado com sucesso.'); // Check for success message
        $I->see('TestUser '); // Check if the new user is listed
    }

    public function updateUser(AcceptanceTester $I)
    {
        // Navigate to the index page
        $I->amOnPage('/user/index');
        $I->see('Users'); // Check if the title is present

        // Click on the update link for the first user
        $I->click('Update', Locator::first('a')); // Assuming the first user is to be updated

        // Fill in the form with new data
        $I->fillField('User [username]', 'UpdatedTestUser ');
        $I->fillField('User [email]', 'updateduser@example.com');
        $I->click('Update'); // Submit the form

        // Check for success message
        $I->see('Utilizador atualizado com sucesso!'); // Check for success message
        $I->see('UpdatedTestUser '); // Check if the updated user is listed
    }

    public function deleteUser(AcceptanceTester $I)
    {
        // Navigate to the index page
        $I->amOnPage('/user/index');
        $I->see('Users'); // Check if the title is present

        // Click on the delete link for the first user
        $I->click('Delete', Locator::first('a')); // Assuming the first user is to be deleted

        // Confirm the deletion
        $I->acceptPopup(); // Accept the confirmation dialog

        // Check for success message
        $I->see('Utilizador eliminado com sucesso!'); // Check for success message
        $I->dontSee('UpdatedTestUser '); // Check if the deleted user is no longer listed
    }

    public function viewUser Details(AcceptanceTester $I)
    {
        // Navigate to the index page
        $I->amOnPage('/user/index');
        $I->see('Users'); // Check if the title is present

        // Click on the view link for the first user
        $I->click('View', Locator::first('a')); // Assuming the first user is to be viewed

        // Check if the user details are displayed
        $I->see('User  Details'); // Check for the user details title
        $I->see('TestUser '); // Check if the username is displayed
        $I->see('testuser@example.com'); // Check if the email is displayed
        // Add any other fields you want to verify
    }
}
