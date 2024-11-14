<?php

namespace common\models;

use common\models\Bilhetes;
use common\models\Eventos;
use common\models\Locais;
use Yii;

/**
 * This is the model class for table "Zonas".
 *
 * @property int $id
 * @property string|null $lugar
 * @property int|null $porta
 * @property int|null $local_id
 * @property int|null $evento_id
 * @property int|null $quantidadedisponivel
 *
 * @property Bilhetes[] $bilhetes
 * @property Eventos $evento
 * @property Locais $local
 */
class Zona extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Zonas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['porta', 'local_id', 'evento_id', 'quantidadedisponivel'], 'integer'],
            [['lugar'], 'string', 'max' => 100],
            [['local_id'], 'exist', 'skipOnError' => true, 'targetClass' => Locais::class, 'targetAttribute' => ['local_id' => 'id']],
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
            'lugar' => 'Lugar',
            'porta' => 'Porta',
            'local_id' => 'Local ID',
            'evento_id' => 'Evento ID',
            'quantidadedisponivel' => 'Quantidadedisponivel',
        ];
    }

    /**
     * Gets query for [[Bilhetes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBilhetes()
    {
        return $this->hasMany(Bilhetes::class, ['zona_id' => 'id']);
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

    /**
     * Gets query for [[Local]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLocal()
    {
        return $this->hasOne(Locais::class, ['id' => 'local_id']);
    }
}
