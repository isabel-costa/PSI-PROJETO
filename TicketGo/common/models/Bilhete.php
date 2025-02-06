<?php


namespace common\models;

use common\models\Evento;
use common\models\LinhaCarrinho;
use common\models\LinhaFatura;
use common\models\Zona;

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
            [['evento_id', 'zona_id', 'precounitario'], 'required'],
        [['precounitario'], 'number'],
            [['data'], 'safe'],
            [['codigobilhete'], 'string'],
            [['evento_id'], 'exist', 'skipOnError' => true, 'targetClass' => Evento::class, 'targetAttribute' => ['evento_id' => 'id']],
            [['zona_id'], 'exist', 'skipOnError' => true, 'targetClass' => Zona::class, 'targetAttribute' => ['zona_id' => 'id']],
            [['linhafatura_id'], 'exist', 'skipOnError' => true, 'targetClass' => LinhaFatura::class, 'targetAttribute' => ['linhafatura_id' => 'id']],
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
        return $this->hasOne(Evento::class, ['id' => 'evento_id']);
    }

    /**
     * Gets query for [[Linhafatura]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLinhafatura()
    {
        return $this->hasOne(LinhaFatura::class, ['id' => 'linhafatura_id']);
    }

    /**
     * Gets query for [[LinhasCarrinhos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLinhasCarrinhos()
    {
        return $this->hasMany(LinhaCarrinho::class, ['bilhete_id' => 'id']);
    }

    /**
     * Gets query for [[Zona]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getZona()
    {
        return $this->hasOne(Zona::class, ['id' => 'zona_id']);
    }
}
