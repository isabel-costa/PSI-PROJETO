<?php

namespace tests\functional;

use common\models\Categoria;
use Yii;

class CategoriaCest
{
    protected function _before()
    {
        // Set up necessary data for tests
        $categoria = new Categoria(['nome' => 'Test Category', 'descricao' => 'This is a test category.']);
        $categoria->save();
    }

    public function testIndex()
    {
        // Simulate a request to the index action
        $response = Yii::$app->runAction('categoria/index');

        // Check if the response is valid
        $this->assertNotNull($response);
        $this->assertContains('Categorias', $response); // Check if the title is present
    }

    public function testCreateCategoria()
    {
        // Simulate a request to the create action
        $response = Yii::$app->runAction('categoria/create', [
            'Categoria' => [
                'nome' => 'New Test Category',
                'descricao' => 'This is a new test category.',
            ],
        ]);

        // Check for success message
        $this->assertContains('Categorias', $response); // Check if redirected to index
        $this->assertContains('New Test Category', $response); // Check if the new category is listed
    }

    public function testUpdateCategoria()
    {
        // Simulate a request to the update action
        $categoria = Categoria::find()->one(); // Get the first category
        $response = Yii::$app->runAction('categoria/update', [
            'id' => $categoria->id,
            'Categoria' => [
                'nome' => 'Updated Test Category',
                'descricao' => 'This is an updated test category.',
            ],
        ]);

        // Check for success message
        $this->assertContains('Categorias', $response); // Check if redirected to index
        $this->assertContains('Updated Test Category', $response); // Check if the updated category is listed
    }

    public function testDeleteCategoria()
    {
        // Simulate a request to the delete action
        $categoria = Categoria::find()->one(); // Get the first category
        Yii::$app->runAction('categoria/delete', ['id' => $categoria->id]);

        // Check that the category no longer exists
        $this->assertNull(Categoria::findOne($categoria->id), 'The category should be deleted.');
    }
}