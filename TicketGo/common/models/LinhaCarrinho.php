<?php

namespace common\models;

use common\models\Bilhete;
use common\models\Carrinho;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Json;

/**
 * This is the model class for table "LinhasCarrinho".
 *
 * @property int $id
 * @property int|null $carrinho_id
 * @property int|null $bilhete_id
 * @property int|null $quantidade
 * @property float|null $precounitario
 * @property float|null $valortotal
 *
 * @property Bilhetes $bilhete
 * @property Carrinhos $carrinho
 */
class LinhaCarrinho extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'LinhasCarrinho';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['carrinho_id', 'bilhete_id', 'quantidade'], 'integer'],
            [['precounitario', 'valortotal'], 'number'],
            [['carrinho_id'], 'exist', 'skipOnError' => true, 'targetClass' => Carrinhos::class, 'targetAttribute' => ['carrinho_id' => 'id']],
            [['bilhete_id'], 'exist', 'skipOnError' => true, 'targetClass' => Bilhetes::class, 'targetAttribute' => ['bilhete_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'carrinho_id' => 'Carrinho ID',
            'bilhete_id' => 'Bilhete ID',
            'quantidade' => 'Quantidade',
            'precounitario' => 'Precounitario',
            'valortotal' => 'Valortotal',
        ];
    }

    /**
     * Gets query for [[Bilhete]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBilhete()
    {
        return $this->hasOne(Bilhete::class, ['id' => 'bilhete_id']);
    }

    /**
     * Gets query for [[Carrinho]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCarrinho()
    {
        return $this->hasOne(Carrinho::class, ['id' => 'carrinho_id']);
    }
}