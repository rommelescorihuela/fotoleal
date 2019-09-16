<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rol".
 *
 * @property string $id_rol
 * @property string $rol
 *
 * @property Usuario[] $usuarios
 */
class Rol extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rol';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rol'], 'required'],
            [['rol'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_rol' => 'Id Rol',
            'rol' => 'Rol',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarios()
    {
        return $this->hasMany(Usuario::className(), ['id_rol' => 'id_rol']);
    }

    /**
     * {@inheritdoc}
     * @return RolQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new RolQuery(get_called_class());
    }
}
