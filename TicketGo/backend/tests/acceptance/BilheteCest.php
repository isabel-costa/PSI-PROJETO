<?php

namespace tests\acceptance;

class BilheteCest
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

    public function viewBilhetes(AcceptanceTester $I)
    {
        // Navigate to the index page of Bilhete
        $I->amOnPage('/bilhete/index?evento_id=9'); // Replace with a valid evento_id
        $I->see('Bilhetes'); // Check if the title is present
        $I->see('Criar Bilhetes em Massa'); // Check if the create button is present
    }

    public function createBilhete(AcceptanceTester $I)
    {
        // Navigate to the create page
        $I->amOnPage('/bilhete/create?evento_id=9'); // Replace with a valid evento_id
        $I->see('Criação de Bilhetes'); // Check if the title is present

        // Fill in the form
        $I->selectOption('Bilhete[zona_id]', '1'); // Replace with a valid zone ID
        $I->fillField('Bilhete[precounitario]', '10.00'); // Set a price
        $I->click('Criar Bilhetes'); // Submit the form

        // Check for success message
        $I->see('Bilhetes criados com sucesso!'); // Check for success message
    }

    public function createBilheteWithInvalidData(AcceptanceTester $I)
    {
        // Navigate to the create page
        $I->amOnPage('/bilhete/create?evento_id=9'); // Replace with a valid evento_id
        $I->see('Criação de Bilhetes'); // Check if the title is present

        // Attempt to create a bilhete without selecting a zone
        $I->fillField('Bilhete[precounitario]', '10.00'); // Set a price
        $I->click('Criar Bilhetes'); // Submit the form

        // Check for error message
        $I->see('Zona selecionada não encontrada.'); // Check for error message
    }
}