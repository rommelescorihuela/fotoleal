<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "enlace".
 *
 * @property string $id_enlace
 * @property string $id_cliente
 * @property string $enlace
 * @property int $enviado
 * @property Cliente $cliente
 */
class Enlace extends \yii\db\ActiveRecord
{   
    public $accion;
    //public $accion=array();
    public $valor=array();
    public $link=array();
    public $id=array();
    public $nombre=array();
    public $apellido=array();
    public $correo=array();
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'enlace';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_cliente', 'enlace', 'enviado'], 'required'],
            [['id_cliente', 'enviado'], 'integer'],
            [['enlace'], 'string', 'max' => 200],
            [['id_cliente'], 'exist', 'skipOnError' => true, 'targetClass' => Cliente::className(), 'targetAttribute' => ['id_cliente' => 'id_cliente']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_enlace' => 'Id Enlace',
            'id_cliente' => 'Id Cliente',
            'enlace' => 'Enlace',
            'enviado' => 'Enviado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCliente()
    {
        return $this->hasOne(Cliente::className(), ['id_cliente' => 'id_cliente']);
    }

    /**
     * {@inheritdoc}
     * @return EnlaceQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new EnlaceQuery(get_called_class());
    }
}
