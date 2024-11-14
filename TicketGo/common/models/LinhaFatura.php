<?php

namespace common\models;

use common\models\Bilhetes;
use common\models\Faturas;
use Yii;

/**
 * This is the model class for table "LinhasFatura".
 *
 * @property int $id
 * @property int|null $fatura_id
 * @property string|null $descricao
 * @property int|null $quantidade
 * @property float|null $precounitario
 * @property float|null $valortotal
 *
 * @property Bilhetes[] $bilhetes
 * @property Faturas $fatura
 */
class LinhaFatura extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'LinhasFatura';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fatura_id', 'quantidade'], 'integer'],
            [['precounitario', 'valortotal'], 'number'],
            [['descricao'], 'string', 'max' => 200],
            [['fatura_id'], 'exist', 'skipOnError' => true, 'targetClass' => Faturas::class, 'targetAttribute' => ['fatura_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fatura_id' => 'Fatura ID',
            'descricao' => 'Descricao',
            'quantidade' => 'Quantidade',
            'precounitario' => 'Precounitario',
            'valortotal' => 'Valortotal',
        ];
    }

    /**
     * Gets query for [[Bilhetes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBilhetes()
    {
        return $this->hasMany(Bilhetes::class, ['linhafatura_id' => 'id']);
    }

    /**
     * Gets query for [[Fatura]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFatura()
    {
        return $this->hasOne(Faturas::class, ['id' => 'fatura_id']);
    }
}
