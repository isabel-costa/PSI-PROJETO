<?php

namespace tests\functional;

use common\models\User;
use Yii;

class UserCest
{
    protected function _before()
    {
        $user = new User([
            'username' => 'TestUser ',
            'email' => 'testuser@example.com',
            'password_hash' => Yii::$app->security->generatePasswordHash('password123'),
            'status' => User::STATUS_ACTIVE,
        ]);
        $user->save();
    }

    public function testIndex()
    {
        $response = Yii::$app->runAction('user/index');

        $this->assertNotNull($response);
        $this->assertContains('Users', $response);
    }

    public function testCreateUser ()
    {
        $response = Yii::$app->runAction('user/create', [
            'User ' => [
                'username' => 'NewTestUser ',
                'email' => 'newtestuser@example.com',
                'password_hash' => 'newpassword123',
                'role' => 'admin',
            ],
        ]);

        // Check for success message
        $this->assertContains('Utilizador criado com sucesso.', $response);
        $this->assertContains('NewTestUser ', $response);
    }

    public function testUpdateUser ()
    {
        $user = User::find()->one();

        $response = Yii::$app->runAction('user/update', [
            'id' => $user->id,
            'User ' => [
                'username' => 'UpdatedTestUser ',
                'email' => 'updateduser@example.com',
                'password_hash' => 'updatedpassword123',
                'role' => 'admin',
            ],
        ]);

        $this->assertContains('Utilizador atualizado com sucesso!', $response);
        $this->assertContains('UpdatedTestUser ', $response);
    }

    public function testDeleteUser ()
    {
        $user = User::find()->one();

        Yii::$app->runAction('user/delete', ['id' => $user->id]);

        $this->assertNull(User::findOne($user->id), 'The user should be deleted.');
    }

    public function testViewUser Details()
    {
        $user = User::find()->one();

        $response = Yii::$app->runAction('user/view', ['id' => $user->id]);

        $this->assertContains('User  Details', $response);
        $this->assertContains($user->username, $response);
        $this->assertContains($user->email, $response);
    }
}