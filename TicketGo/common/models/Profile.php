<?php

namespace common\models;

use common\models\Carrinho;
use common\models\Fatura;
use common\models\Favorito;
use common\models\MqttPublisher;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Json;


/**
 * This is the model class for table "Profiles".
 *
 * @property int $id
 * @property string|null $nome
 * @property string|null $datanascimento
 * @property int|null $nif
 * @property string|null $morada
 * @property string|null $dataregisto
 *
 * @property Carrinhos $carrinhos
 * @property Faturas[] $faturas
 * @property Favoritos[] $favoritos
 */
class Profile extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Profiles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['datanascimento', 'dataregisto'], 'safe'],
            [['nif'], 'integer'],
            [['nome'], 'string', 'max' => 50],
            [['morada'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nome' => 'Nome',
            'datanascimento' => 'Datanascimento',
            'nif' => 'Nif',
            'morada' => 'Morada',
            'dataregisto' => 'Dataregisto',
        ];
    }

    /**
     * Gets query for [[Carrinhos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCarrinhos()
    {
        return $this->hasOne(Carrinho::class, ['profile_id' => 'id']);
    }

    /**
     * Gets query for [[Faturas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFaturas()
    {
        return $this->hasMany(Fatura::class, ['profile_id' => 'id']);
    }
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Gets query for [[Favoritos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFavoritos()
    {
        return $this->hasMany(Favorito::class, ['profile_id' => 'id']);
    }


    /*
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $topico = "ticketgo/profile/update-profile/{$this->id}";

        $jsonAttributes = Json::encode($this->attributes);
        
        $mensagem = "O perfil com ID {$this->id} foi atualizado.";

        mqttPublisher::publish($topico, $mensagem);
        mqttPublisher::publish($topico, $jsonAttributes);
    }
    */
}