<?php


namespace common\models;

use common\models\Bilhetes;
use common\models\Categorias;
use common\models\Favoritos;
use common\models\Imagens;
use common\models\Locais;
use common\models\Zonas;

use Yii;

/**
 * This is the model class for table "Eventos".
 *
 * @property int $id
 * @property string|null $titulo
 * @property string|null $descricao
 * @property string|null $datainicio
 * @property string|null $datafim
 * @property int|null $local_id
 * @property int|null $categoria_id
 *
 * @property Bilhetes[] $bilhetes
 * @property Categorias $categoria
 * @property Favoritos[] $favoritos
 * @property Imagens $imagens
 * @property Locais $local
 * @property Zonas[] $zonas
 */
class Evento extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Eventos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descricao'], 'string'],
            [['datainicio', 'datafim'], 'safe'],
            [['local_id', 'categoria_id'], 'integer'],
            [['titulo'], 'string', 'max' => 100],
            [['local_id'], 'exist', 'skipOnError' => true, 'targetClass' => Locais::class, 'targetAttribute' => ['local_id' => 'id']],
            [['categoria_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categorias::class, 'targetAttribute' => ['categoria_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'titulo' => 'Titulo',
            'descricao' => 'Descricao',
            'datainicio' => 'Datainicio',
            'datafim' => 'Datafim',
            'local_id' => 'Local ID',
            'categoria_id' => 'Categoria ID',
        ];
    }

    /**
     * Gets query for [[Bilhetes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBilhetes()
    {
        return $this->hasMany(Bilhetes::class, ['evento_id' => 'id']);
    }

    /**
     * Gets query for [[Categoria]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategoria()
    {
        return $this->hasOne(Categorias::class, ['id' => 'categoria_id']);
    }

    /**
     * Gets query for [[Favoritos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFavoritos()
    {
        return $this->hasMany(Favoritos::class, ['evento_id' => 'id']);
    }

    /**
     * Gets query for [[Imagens]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getImagens()
    {
        return $this->hasOne(Imagens::class, ['evento_id' => 'id']);
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

    /**
     * Gets query for [[Zonas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getZonas()
    {
        return $this->hasMany(Zonas::class, ['evento_id' => 'id']);
    }
}
