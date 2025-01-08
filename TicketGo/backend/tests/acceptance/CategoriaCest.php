<?php

namespace tests\acceptance;

use Codeception\Util\Locator;

class CategoriaCest
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

    public function viewCategorias(AcceptanceTester $I)
    {
        // Navigate to the index page of Categoria
        $I->amOnPage('/categoria/index');
        $I->see('Categorias'); // Check if the title is present
        $I->see('Create Categoria'); // Check if the create button is present
    }

    public function createCategoria(AcceptanceTester $I)
    {
        // Navigate to the create page
        $I->amOnPage('/categoria/create');
        $I->see('Create Categoria'); // Check if the title is present

        // Fill in the form
        $I->fillField('Categoria[nome]', 'Test Category');
        $I->fillField('Categoria[descricao]', 'This is a test category.');
        $I->click('Save'); // Submit the form

        // Check for success message
        $I->see('Categorias'); // Check if redirected to index
        $I->see('Test Category'); // Check if the new category is listed
    }

    public function updateCategoria(AcceptanceTester $I)
    {
        // Navigate to the index page
        $I->amOnPage('/categoria/index');
        $I->see('Categorias'); // Check if the title is present

        // Click on the update link for the first category
        $I->click('Update', Locator::first('a')); // Assuming the first category is to be updated

        // Fill in the form with new data
        $I->fillField('Categoria[nome]', 'Updated Test Category');
        $I->fillField('Categoria[descricao]', 'This is an updated test category.');
        $I->click('Save'); // Submit the form

        // Check for success message
        $I->see('Categorias'); // Check if redirected to index
        $I->see('Updated Test Category'); // Check if the updated category is listed
    }

    public function deleteCategoria(AcceptanceTester $I)
    {
        // Navigate to the index page
        $I->amOnPage('/categoria/index');
        $I->see('Categorias'); // Check if the title is present

        // Click on the delete link for the first category
        $I->click('Delete', Locator::first('a')); // Assuming the first category is to be deleted

        // Confirm the deletion
        $I->acceptPopup(); // Accept the confirmation dialog

        // Check for success message
        $I->see('Categorias'); // Check if redirected to index
        $I->dontSee('Updated Test Category'); // Check if the deleted category is no longer listed
    }
}