<?php

namespace tests\acceptance;

use Codeception\Util\Locator;

class MetodoPagamentoCest
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

    public function viewPaymentMethods(AcceptanceTester $I)
    {
        // Navigate to the index page of MetodoPagamento
        $I->amOnPage('/metodo-pagamento/index');
        $I->see('Metodo Pagamentos'); // Check if the title is present
        $I->see('Create Metodo Pagamento'); // Check if the create button is present
    }

    public function createPaymentMethod(AcceptanceTester $I)
    {
        // Navigate to the create page
        $I->amOnPage('/metodo-pagamento/create');
        $I->see('Create Metodo Pagamento'); // Check if the title is present

        // Fill in the form
        $I->fillField('MetodoPagamento[nome]', 'Test Payment Method');
        $I->click('Save'); // Submit the form

        // Check for success message
        $I->see('Metodo Pagamento created successfully!'); // Check for success message
        $I->see('Test Payment Method'); // Check if the new payment method is listed
    }

    public function updatePaymentMethod(AcceptanceTester $I)
    {
        // Navigate to the index page
        $I->amOnPage('/metodo-pagamento/index');
        $I->see('Metodo Pagamentos'); // Check if the title is present

        // Click on the update link for the first payment method
        $I->click('Update', Locator::first('a')); // Assuming the first payment method is to be updated

        // Fill in the form with new data
        $I->fillField('MetodoPagamento[nome]', 'Updated Test Payment Method');
        $I->click('Save'); // Submit the form

        // Check for success message
        $I->see('Metodo Pagamento updated successfully!'); // Check for success message
        $I->see('Updated Test Payment Method'); // Check if the updated payment method is listed
    }

    public function deletePaymentMethod(AcceptanceTester $I)
    {
        // Navigate to the index page
        $I->amOnPage('/metodo-pagamento/index');
        $I->see('Metodo Pagamentos'); // Check if the title is present

        // Click on the delete link for the first payment method
        $I->click('Delete', Locator::first('a')); // Assuming the first payment method is to be deleted

        // Confirm the deletion
        $I->acceptPopup(); // Accept the confirmation dialog

        // Check for success message
        $I->see('Metodo Pagamento deleted successfully!'); // Check for success message
        $I->dontSee('Updated Test Payment Method'); // Check if the deleted payment method is no longer listed
    }

    public function viewPaymentMethodDetails(AcceptanceTester $I)
    {
        // Navigate to the index page
        $I->amOnPage('/metodo-pagamento/index');
        $I->see('Metodo Pagamentos'); // Check if the title is present

        // Click on the view link for the first payment method
        $I->click('View', Locator::first('a')); // Assuming the first payment method is to be viewed

        // Check if the payment method details are displayed
        $I->see('Metodo Pagamento Details'); // Check for the payment method details title
        $I->see('Test Payment Method'); // Check if the payment method name is displayed
        // Add any other fields you want to verify
    }
}