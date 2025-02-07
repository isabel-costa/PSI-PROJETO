<?php

namespace common\models;

use Yii;
use yii\helpers\Json;

/**
 * This is the model class for table "Categorias".
 *
 * @property int $id
 * @property string|null $nome
 * @property string|null $descricao
 *
 * @property Eventos[] $eventos
 */
class Categoria extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Categorias';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descricao'], 'string'],
            [['nome'], 'string', 'max' => 100],
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
            'descricao' => 'Descricao',
        ];
    }

    /**
     * Gets query for [[Eventos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEventos()
    {
        return $this->hasMany(Eventos::class, ['categoria_id' => 'id']);
    }
    public function afterSave($insert, $changedAttributes)
    {

        parent::afterSave($insert, $changedAttributes);

        $idCategoria = $this->id;
        $topico = "ticketgo/categoria/{$idCategoria}/save";


        $jsonAttributes = Json::encode($this->attributes);

        $mensagem= 'A Categoria foi criada ou modificada';

        mqttPublisher::publish($topico,$jsonAttributes);
        mqttPublisher::publish($topico,$mensagem);
    }
}
