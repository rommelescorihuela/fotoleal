<?php

namespace app\models;
use Yii;
use yii\base\model;


class FormSearch extends model{

	public $q;
	//public $id_programa;

	 public function rules()
    {
        return [
          //  [['id_programa'], 'exist', 'skipOnError' => true, 'targetClass' => Programa::className(), 'targetAttribute' => ['id_programa' => 'id_programa']],
        	["q", "match", "pattern" => "/^[0-9a-záéíóúñ\s]+$/i", "message" => "Sólo se aceptan letras y números"]
        ];
    }

    /* public function attributeLabels()
    {
        return [
            'id_programa' => 'Id Programa:',
        ];
    }*/

    public function attributeLabels()
    {
        return [
            'q' => "Buscar:",
        ];
    }


}