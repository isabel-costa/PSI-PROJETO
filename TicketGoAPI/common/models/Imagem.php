<?php

namespace common\models;

use Yii;
use yii\helpers\Json;

/**
 * This is the model class for table "Imagens".
 *
 * @property int $id
 * @property string|null $nome
 * @property int|null $evento_id
 *
 * @property Eventos $evento
 */
class Imagem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Imagens';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['evento_id'], 'integer'],
            [['nome'], 'string', 'max' => 50],
            [['evento_id'], 'unique'],
            [['evento_id'], 'exist', 'skipOnError' => true, 'targetClass' => Eventos::class, 'targetAttribute' => ['evento_id' => 'id']],
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
            'evento_id' => 'Evento ID',
        ];
    }

    /**
     * Gets query for [[Evento]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEvento()
    {
        return $this->hasOne(Eventos::class, ['id' => 'evento_id']);
    }
    public function afterSave($insert, $changedAttributes)
    {

        parent::afterSave($insert, $changedAttributes);

        $idImagem = $this->id;
        $topico = "ticketgo/imagem/{$idImagem}/save";


        $jsonAttributes = Json::encode($this->attributes);

        $mensagem= 'A Imagem foi criada ou modificada';

        mqttPublisher::publish($topico,$jsonAttributes);
        mqttPublisher::publish($topico,$mensagem);
    }
}
