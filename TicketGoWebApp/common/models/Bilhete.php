<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Bilhetes".
 *
 * @property int $id
 * @property int|null $evento_id
 * @property int|null $zona_id
 * @property float|null $precounitario
 * @property int|null $vendido
 * @property string|null $data
 * @property string|null $codigobilhete
 * @property int|null $linhafatura_id
 *
 * @property Eventos $evento
 * @property LinhasFatura $linhafatura
 * @property LinhasCarrinho[] $linhasCarrinhos
 * @property Zonas $zona
 */
class Bilhete extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Bilhetes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['evento_id', 'zona_id', 'vendido', 'linhafatura_id'], 'integer'],
            [['precounitario'], 'number'],
            [['data'], 'safe'],
            [['codigobilhete'], 'string'],
            [['evento_id'], 'exist', 'skipOnError' => true, 'targetClass' => Eventos::class, 'targetAttribute' => ['evento_id' => 'id']],
            [['zona_id'], 'exist', 'skipOnError' => true, 'targetClass' => Zonas::class, 'targetAttribute' => ['zona_id' => 'id']],
            [['linhafatura_id'], 'exist', 'skipOnError' => true, 'targetClass' => LinhasFatura::class, 'targetAttribute' => ['linhafatura_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'evento_id' => 'Evento ID',
            'zona_id' => 'Zona ID',
            'precounitario' => 'Precounitario',
            'vendido' => 'Vendido',
            'data' => 'Data',
            'codigobilhete' => 'Codigobilhete',
            'linhafatura_id' => 'Linhafatura ID',
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

    /**
     * Gets query for [[Linhafatura]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLinhafatura()
    {
        return $this->hasOne(LinhasFatura::class, ['id' => 'linhafatura_id']);
    }

    /**
     * Gets query for [[LinhasCarrinhos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLinhasCarrinhos()
    {
        return $this->hasMany(LinhasCarrinho::class, ['bilhete_id' => 'id']);
    }

    /**
     * Gets query for [[Zona]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getZona()
    {
        return $this->hasOne(Zonas::class, ['id' => 'zona_id']);
    }
}
