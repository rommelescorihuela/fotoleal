<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pago_foto".
 *
 * @property int $id_pago_foto
 * @property int $numero_referencia
 * @property double $monto
 * @property int $id_cliente
 * @property string $observaciones
 * @property int $id_usuario
 * @property string $tipo_pago
 * @property string $fecha_pago
 * @property string $paquete
 * @property string $ref_payco
 * @property string $confirmacion
 * @property string $factura
 * @property string $ref_payco_corto
 * @property int $id_foto_adicional
 *
 * @property FotoAdicional $fotoAdicional
 */
class PagoFoto extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pago_foto';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['numero_referencia', 'monto', 'id_cliente', 'observaciones', 'id_usuario', 'tipo_pago', 'fecha_pago', 'paquete', 'ref_payco', 'confirmacion', 'factura', 'id_foto_adicional'], 'required'],
            [['numero_referencia', 'id_cliente', 'id_usuario', 'id_foto_adicional'], 'integer'],
            [['monto'], 'number'],
            [['observaciones'], 'string', 'max' => 50],
            [['tipo_pago'], 'string', 'max' => 30],
            [['fecha_pago'], 'string', 'max' => 20],
            [['paquete', 'ref_payco', 'confirmacion', 'factura', 'ref_payco_corto'], 'string', 'max' => 255],
            [['id_foto_adicional'], 'exist', 'skipOnError' => true, 'targetClass' => FotoAdicional::className(), 'targetAttribute' => ['id_foto_adicional' => 'id_foto']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_pago_foto' => 'Id Pago Foto',
            'numero_referencia' => 'Numero Referencia',
            'monto' => 'Monto',
            'id_cliente' => 'Id Cliente',
            'observaciones' => 'Observaciones',
            'id_usuario' => 'Id Usuario',
            'tipo_pago' => 'Tipo Pago',
            'fecha_pago' => 'Fecha Pago',
            'paquete' => 'Paquete',
            'ref_payco' => 'Ref Payco',
            'confirmacion' => 'Confirmacion',
            'factura' => 'Factura',
            'ref_payco_corto' => 'Ref Payco Corto',
            'id_foto_adicional' => 'Id Foto Adicional',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFotoAdicional()
    {
        return $this->hasOne(FotoAdicional::className(), ['id_foto' => 'id_foto_adicional']);
    }

    /**
     * {@inheritdoc}
     * @return PagoFotoQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PagoFotoQuery(get_called_class());
    }
}
