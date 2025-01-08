<?php

namespace tests\acceptance;

use Codeception\Util\Locator;

class ProfileCest
{
    public function _before(AcceptanceTester $I)
    {
        // Log in before each test
        $I->amOnPage('/site/login');
        $I->fillField('LoginForm[username]', 'your_username'); // Replace with a valid username
        $I->fillField('LoginForm[password]', 'your_password'); // Replace with a valid password
        $I->click('login-button');
        $I->see('Logout'); // Check if login was successful
    }

    public function viewProfile(AcceptanceTester $I)
    {
        // Navigate to the profile page
        $I->amOnPage('/site/profile');
        $I->see('Account Details'); // Check if the account details section is present
        $I->see('John Doe'); // Replace with the expected name
    }

    public function updateProfile(AcceptanceTester $I)
    {
        // Navigate to the profile page
        $I->amOnPage('/site/profile');
        $I->see('Account Details'); // Check if the account details section is present

        // Update the profile information
        $I->fillField('Profile[nome]', 'Jane Doe'); // Update name
        $I->fillField('Profile[datanascimento]', '1990-01-01'); // Update date of birth
        $I->fillField('Profile[nif]', '123456789'); // Update NIF
        $I->fillField('Profile[morada]', '456 Another St'); // Update address
        $I->click('Update Account'); // Click the update button

        // Check for success message
        $I->see('Profile updated successfully.'); // Check for success message
        $I->see('Jane Doe'); // Verify that the updated name is displayed
    }

    public function updateProfileWithInvalidData(AcceptanceTester $I)
    {
        // Navigate to the profile page
        $I->amOnPage('/site/profile');
        $I->see('Account Details'); // Check if the account details section is present

        // Attempt to update the profile with invalid data
        $I->fillField('Profile[nome]', ''); // Leave name empty
        $I->click('Update Account'); // Click the update button

        // Check for validation error message
        $I->see('Profile should not be valid with an empty name.'); // Check for error message
    }
}