<?php

namespace common\models;

use common\models\Faturas;
use Yii;

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
}
