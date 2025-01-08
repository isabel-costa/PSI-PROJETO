<?php

namespace tests\functional;
use common\models\Profile;
use Yii;

class ProfileCest
{
    protected function _before()
    {
        // Create a mock user and log them in
        $user = $this->getMockBuilder(\common\models\User::class)
            ->setMethods(['getProfile'])
            ->getMock();
        $user->method('getProfile')->willReturn((object)['id' => 1]);

        Yii::$app->user->setIdentity($user);
    }

    public function testProfileView()
    {
        // Create a mock profile
        $profile = new Profile(['id' => 1, 'nome' => 'John Doe']);
        $profile->save();

        // Simulate accessing the profile view
        $this->assertEquals('John Doe', $profile->nome);
        // You can add more assertions to check the profile data
    }

    public function testProfileUpdate()
    {
        // Create a mock profile
        $profile = new Profile(['id' => 1, 'nome' => 'John Doe']);
        $profile->save();

        // Simulate updating the profile
        $profile->nome = 'Jane Doe';
        $profile->save();

        // Check if the profile was updated
        $updatedProfile = Profile::findOne(1);
        $this->assertEquals('Jane Doe', $updatedProfile->nome, 'Profile name should be updated to Jane Doe.');
    }

    public function testProfileUpdateValidation()
    {
        // Create a mock profile
        $profile = new Profile(['id' => 1, 'nome' => 'John Doe']);
        $profile->save();

        // Simulate invalid update (e.g., empty name)
        $profile->nome = ''; // Invalid name
        $this->assertFalse($profile->validate(), 'Profile should not be valid with an empty name.');

        // Check that the error message is set
        $this->assertArrayHasKey('nome', $profile->getErrors(), 'Profile should have validation errors for nome.');
    }

    public function testProfileNotFound()
    {
        // Simulate trying to find a non-existent profile
        $this->expectException(\yii\web\NotFoundHttpException::class);
        Profile::findOne(999); // Assuming 999 does not exist
    }
}