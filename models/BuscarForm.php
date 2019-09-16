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
class BuscarForm extends Model
{
    public $fecha1;
    public $fecha2;
    public $evento;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [

            [['fecha1', 'fecha2'], 'safe'],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'fecha1' => 'fecha1',
            'fecha2' => 'fecha2',
        ];
    }

}
