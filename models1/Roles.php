<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "roles".
 *
 * @property int $idrol
 * @property string $rol
 */
class Roles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'roles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rol'], 'required'],
            [['rol'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idrol' => 'Idrol',
            'rol' => 'Rol',
        ];
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
