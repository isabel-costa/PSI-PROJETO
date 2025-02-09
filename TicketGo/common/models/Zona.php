<?php

namespace common\models;

use common\models\Bilhete;
use common\models\Evento;
use common\models\Local;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Json;

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

class Zona extends ActiveRecord
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
            [['local_id'], 'exist', 'skipOnError' => true, 'targetClass' => Local::class, 'targetAttribute' => ['local_id' => 'id']],
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
        return $this->hasMany(Bilhete::class, ['zona_id' => 'id']);
    }

    /**
     * Gets query for [[Evento]].
     *
     * @return \yii\db\ActiveQuery
    * \
    public function getEvento()
    {
        return $this->hasOne(Evento::class, ['id' => 'evento_id']);
    }


    /**
     * Gets query for [[Local]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLocal()
    {
        return $this->hasOne(\common\models\Local::class, ['id' => 'local_id']);
    }
}
