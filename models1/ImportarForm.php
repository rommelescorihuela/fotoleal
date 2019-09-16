<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class importarForm extends Model
{
    public $archivo;



    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            ['archivo', 'file', 'skipOnEmpty' => false, 'extensions' => 'xls'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'archivo' => 'Archivo',
        ];
    }
   
}
