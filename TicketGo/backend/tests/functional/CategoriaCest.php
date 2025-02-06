<?php

namespace tests\functional;

use common\models\Categoria;
use Yii;

class CategoriaCest
{
    protected function _before()
    {

        $categoria = new Categoria(['nome' => 'Test Category', 'descricao' => 'This is a test category.']);
        $categoria->save();
    }

    public function testIndex()
    {

        $response = Yii::$app->runAction('categoria/index');


        $this->assertNotNull($response);
        $this->assertContains('Categorias', $response);
    }

    public function testCreateCategoria()
    {

        $response = Yii::$app->runAction('categoria/create', [
            'Categoria' => [
                'nome' => 'New Test Category',
                'descricao' => 'This is a new test category.',
            ],
        ]);


        $this->assertContains('Categorias', $response);
        $this->assertContains('New Test Category', $response);
    }

    public function testUpdateCategoria()
    {

        $categoria = Categoria::find()->one();
        $response = Yii::$app->runAction('categoria/update', [
            'id' => $categoria->id,
            'Categoria' => [
                'nome' => 'Updated Test Category',
                'descricao' => 'This is an updated test category.',
            ],
        ]);

        $this->assertContains('Categorias', $response);
        $this->assertContains('Updated Test Category', $response);
    }

    public function testDeleteCategoria()
    {
        $categoria = Categoria::find()->one();
        Yii::$app->runAction('categoria/delete', ['id' => $categoria->id]);

        $this->assertNull(Categoria::findOne($categoria->id), 'The category should be deleted.');
    }
}