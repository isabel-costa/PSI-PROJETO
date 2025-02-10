<?php

namespace app\models;

use common\models\mqttPublisher;
use Yii;
use yii\helpers\Json;

/**
 * This is the model class for table "MetodosPagamento".
 *
 * @property int $id
 * @property string|null $nome
 *
 * @property Faturas[] $faturas
 */
class MetodoPagamento extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'MetodosPagamento';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nome'], 'string', 'max' => 50],
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
        ];
    }

    /**
     * Gets query for [[Faturas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFaturas()
    {
        return $this->hasMany(Faturas::class, ['metodopagamento_id' => 'id']);
    }
    public function afterSave($insert, $changedAttributes)
    {

        parent::afterSave($insert, $changedAttributes);

        $idmetodopagamento = $this->id;
        $topico = "ticketgo/metodopagamento/{ $idmetodopagamento}/save";


        $jsonAttributes = Json::encode($this->attributes);

        $mensagem= 'O Metodo de pagamento foi criado ou modificado';

        mqttPublisher::publish($topico,$jsonAttributes);
        mqttPublisher::publish($topico,$mensagem);
    }
}
