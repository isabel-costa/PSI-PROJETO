<?php

namespace tests\unit;

use Codeception\Test\Unit;
use common\models\Profile;

class ProfileTest extends Unit
{
    public function testValidationRules()
    {
        $profile = new Profile();

        $this->assertFalse($profile->validate(), 'Profile should not be valid without required fields.');

        $profile->nome = 'John Doe';
        $profile->datanascimento = '1990-01-01';
        $profile->nif = 123456789;
        $profile->morada = '123 Main St';
        $profile->dataregisto = date('Y-m-d H:i:s');

        $this->assertTrue($profile->validate(), 'Profile should be valid with all required fields.');
    }

    public function testAttributeLabels()
    {
        $profile = new Profile();
        $this->assertEquals('ID', $profile->attributeLabels()['id']);
        $this->assertEquals('Nome', $profile->attributeLabels()['nome']);
        $this->assertEquals('Datanascimento', $profile->attributeLabels()['datanascimento']);
        $this->assertEquals('Nif', $profile->attributeLabels()['nif']);
        $this->assertEquals('Morada', $profile->attributeLabels()['morada']);
        $this->assertEquals('Dataregisto', $profile->attributeLabels()['dataregisto']);
    }

    public function testProfileRelationships()
    {
        $profile = new Profile();

        $this->assertInstanceOf(\yii\db\ActiveQuery::class, $profile->getCarrinhos());
        $this->assertInstanceOf(\yii\db\ActiveQuery::class, $profile->getFaturas());
        $this->assertInstanceOf(\yii\db\ActiveQuery::class, $profile->getFavoritos());
    }
}