<?php

namespace common\models;

use common\models\mqttPublisher;
use Yii;
use yii\helpers\Json;

/**
 * This is the model class for table "Locais".
 *
 * @property int $id
 * @property string|null $nome
 * @property string|null $morada
 * @property string|null $cidade
 * @property int|null $capacidade
 *
 * @property Eventos[] $eventos
 * @property Zonas[] $zonas
 */
class Local extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Locais';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['capacidade'], 'integer'],
            [['nome', 'cidade'], 'string', 'max' => 100],
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
            'morada' => 'Morada',
            'cidade' => 'Cidade',
            'capacidade' => 'Capacidade',
        ];
    }

    /**
     * Gets query for [[Eventos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEventos()
    {
        return $this->hasMany(Eventos::class, ['local_id' => 'id']);
    }

    /**
     * Gets query for [[Zonas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getZonas()
    {
        return $this->hasMany(Zonas::class, ['local_id' => 'id']);
    }
    public function afterSave($insert, $changedAttributes)
    {

        parent::afterSave($insert, $changedAttributes);

        $idlocal = $this->id;
        $topico = "ticketgo/local/{ $idlocal}/save";


        $jsonAttributes = Json::encode($this->attributes);

        $mensagem= 'O localfoi criado ou modificado';

        mqttPublisher::publish($topico,$jsonAttributes);
        mqttPublisher::publish($topico,$mensagem);
    }
}
