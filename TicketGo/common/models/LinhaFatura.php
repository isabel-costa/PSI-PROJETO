<?php

namespace common\models;

use common\models\Bilhete;
use common\models\Fatura;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Json;

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
class LinhaFatura extends ActiveRecord
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
            [['fatura_id'], 'exist', 'skipOnError' => true, 'targetClass' => Fatura::class, 'targetAttribute' => ['fatura_id' => 'id']],
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
        return $this->hasMany(Bilhete::class, ['linhafatura_id' => 'id']);
    }

    /**
     * Gets query for [[Fatura]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFatura()
    {
        return $this->hasOne(Fatura::class, ['id' => 'fatura_id']);
    }
}
