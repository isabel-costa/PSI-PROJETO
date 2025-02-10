<?php

namespace tests\functional;
use common\models\Profile;
use Yii;

class ProfileCest
{
    protected function _before()
    {
        $user = $this->getMockBuilder(\common\models\User::class)
            ->setMethods(['getProfile'])
            ->getMock();
        $user->method('getProfile')->willReturn((object)['id' => 1]);

        Yii::$app->user->setIdentity($user);
    }

    public function testProfileView()
    {
        $profile = new Profile(['id' => 1, 'nome' => 'John Doe']);
        $profile->save();

        $this->assertEquals('John Doe', $profile->nome);
    }

    public function testProfileUpdate()
    {
        $profile = new Profile(['id' => 1, 'nome' => 'John Doe']);
        $profile->save();

        $profile->nome = 'Jane Doe';
        $profile->save();

        $updatedProfile = Profile::findOne(1);
        $this->assertEquals('Jane Doe', $updatedProfile->nome, 'Profile name should be updated to Jane Doe.');
    }

    public function testProfileUpdateValidation()
    {
        $profile = new Profile(['id' => 1, 'nome' => 'John Doe']);
        $profile->save();

        $profile->nome = '';
        $this->assertFalse($profile->validate(), 'Profile should not be valid with an empty name.');

        $this->assertArrayHasKey('nome', $profile->getErrors(), 'Profile should have validation errors for nome.');
    }

    public function testProfileNotFound()
    {
        $this->expectException(\yii\web\NotFoundHttpException::class);
        Profile::findOne(999);
    }
}