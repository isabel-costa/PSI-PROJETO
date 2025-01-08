<?php

namespace tests\functional;

use common\models\Local;
use Yii;

class LocalCest
{
    protected function _before()
    {
        $local = new Local(['id' => 1, 'nome' => 'Test Local', 'morada' => '123 Test Street', 'cidade' => 'Test City', 'capacidade' => 100]);
        $local->save();
    }

    public function testIndex()
    {
        $response = Yii::$app->runAction('local/index');

        // Check if the response is valid
        $this->assertNotNull($response);
        $this->assertContains('Locals', $response); // Check if the title is present
    }

    public function testCreateLocal()
    {
        // Simulate a request to the create action
        $response = Yii::$app->runAction('local/create', [
            'Local' => [
                'nome' => 'New Test Local',
                'morada' => '456 New Street',
                'cidade' => 'New City',
                'capacidade' => 150,
            ],
        ]);

        // Check for success message
        $this->assertContains('Local created successfully!', $response); // Check for success message
        $this->assertContains('New Test Local', $response); // Check if the new local is listed
    }

    public function testUpdateLocal()
    {
        // Get the first local
        $local = Local::find()->one(); // Get the first local

        // Simulate a request to the update action
        $response = Yii::$app->runAction('local/update', [
            'id' => $local->id,
            'Local' => [
                'nome' => 'Updated Test Local',
                'morada' => '789 Updated Street',
                'cidade' => 'Updated City',
                'capacidade' => 200,
            ],
        ]);

        // Check for success message
        $this->assertContains('Local updated successfully!', $response); // Check for success message
        $this->assertContains('Updated Test Local', $response); // Check if the updated local is listed
    }

    public function testDeleteLocal()
    {
        // Get the first local
        $local = Local::find()->one(); // Get the first local

        // Simulate a request to the delete action
        Yii::$app->runAction('local/delete', ['id' => $local->id]);

        // Check that the local no longer exists
        $this->assertNull(Local::findOne($local->id), 'The local should be deleted.');
    }

    public function testViewLocalDetails()
    {
        // Get the first local
        $local = Local::find()->one(); // Get the first local

        // Simulate a request to the view action
        $response = Yii::$app->runAction('local/view', ['id' => $local->id]);

        // Check if the local details are displayed
        $this->assertContains('Local Details', $response); // Check for the local details title
        $this->assertContains($local->nome, $response); // Check if the local name is displayed
        $this->assertContains($local->morada, $response); // Check if the address is displayed
        $this->assertContains($local->cidade, $response); // Check if the city is displayed
        $this->assertContains($local->capacidade, $response); // Check if the capacity is displayed
    }
}