<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "programa".
 *
 * @property string $id_programa
 * @property string $nombre_programa
 * @property string $id_evento
 *
 * @property Cliente[] $clientes
 * @property Evento $evento
 */
class Programa extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'programa';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre_programa', 'id_evento'], 'required'],
            [['id_evento'], 'integer'],
            [['nombre_programa'], 'string', 'max' => 255],
            [['id_evento'], 'exist', 'skipOnError' => true, 'targetClass' => Evento::className(), 'targetAttribute' => ['id_evento' => 'id_evento']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_programa' => 'Id Programa',
            'nombre_programa' => 'Nombre Programa',
            'id_evento' => 'Id Evento',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClientes()
    {
        return $this->hasMany(Cliente::className(), ['id_programa' => 'id_programa']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvento()
    {
        return $this->hasOne(Evento::className(), ['id_evento' => 'id_evento']);
    }

    /**
     * {@inheritdoc}
     * @return ProgramaQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProgramaQuery(get_called_class());
    }
}
