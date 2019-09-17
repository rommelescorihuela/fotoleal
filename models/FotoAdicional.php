<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "foto_adicional".
 *
 * @property string $id_foto
 * @property string $cantidad_foto
 * @property string $monto
 * @property string $total
 * @property string $id_usuario
 * @property string $id_cliente
 *
 * @property Usuario $usuario
 * @property Cliente $cliente
 * @property PagoFoto[] $pagoFotos
 */
class FotoAdicional extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $cedula;
    public static function tableName()
    {
        return 'foto_adicional';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cantidad_foto', 'monto', 'total', 'id_usuario', 'id_cliente'], 'required'],
            [['cantidad_foto', 'monto', 'total', 'id_usuario', 'id_cliente','cedula'], 'integer'],
            [['id_usuario'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::className(), 'targetAttribute' => ['id_usuario' => 'id_usuario']],
            [['id_cliente'], 'exist', 'skipOnError' => true, 'targetClass' => Cliente::className(), 'targetAttribute' => ['id_cliente' => 'id_cliente']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_foto' => 'Id Foto',
            'cantidad_foto' => 'Cantidad Foto',
            'monto' => 'Monto',
            'total' => 'Total',
            'id_usuario' => 'Id Usuario',
            'id_cliente' => 'Id Cliente',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(Usuario::className(), ['id_usuario' => 'id_usuario']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCliente()
    {
        return $this->hasOne(Cliente::className(), ['id_cliente' => 'id_cliente']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPagoFotos()
    {
        return $this->hasMany(PagoFoto::className(), ['id_foto_adicional' => 'id_foto']);
    }

    /**
     * {@inheritdoc}
     * @return FotoAdicionalQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FotoAdicionalQuery(get_called_class());
    }
}
