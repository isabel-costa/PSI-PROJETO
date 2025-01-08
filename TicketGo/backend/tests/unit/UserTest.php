<?php

namespace tests\unit;

use Codeception\Test\Unit;
use common\models\User;
use Yii;

class UserTest extends Unit
{
    protected function _before()
    {

    }

    public function testUser Creation()
    {
        $user = new User([
            'username' => 'TestUser ',
            'email' => 'testuser@example.com',
            'password_hash' => Yii::$app->security->generatePasswordHash('password123'),
            'status' => User::STATUS_ACTIVE,
        ]);

        $this->assertTrue($user->validate(), 'User  should be valid.');

        $this->assertTrue($user->save(), 'User  should be saved successfully.');
    }

public function testUser ValidationRules()
    {
        $user = new User();

        $this->assertFalse($user->validate(), 'User  should not be valid without required fields.');

        $user->username = 'ValidUser ';
        $user->email = 'validuser@example.com';
        $user->password_hash = Yii::$app->security->generatePasswordHash('password123');

        $this->assertTrue($user->validate(), 'User  should be valid with all required fields.');
    }

    public function testUser AttributeLabels()
    {
        $user = new User();
        $this->assertEquals('ID', $user->attributeLabels()['id']);
        $this->assertEquals('Username', $user->attributeLabels()['username']);
        $this->assertEquals('Email', $user->attributeLabels()['email']);
        $this->assertEquals('Auth Key', $user->attributeLabels()['auth_key']);
        $this->assertEquals('Password Hash', $user->attributeLabels()['password_hash']);
        $this->assertEquals('Status', $user->attributeLabels()['status']);
        $this->assertEquals('Created At', $user->attributeLabels()['created_at']);
        $this->assertEquals('Updated At', $user->attributeLabels()['updated_at']);
    }

    public function testPasswordHashing()
    {
        $user = new User();
        $user->setPassword('my_secure_password');

        $this->assertNotEmpty($user->password_hash, 'Password hash should not be empty.');

        $this->assertTrue($user->validatePassword('my_secure_password'), 'Password should be valid.');
        $this->assertFalse($user->validatePassword('wrong_password'), 'Password should be invalid.');
    }
}