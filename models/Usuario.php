<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "usuario".
 *
 * @property string $id_usuario
 * @property string $usuario
 * @property string $clave
 * @property string $id_rol
 * @property string $nombre
 * @property string $apellido
 * @property int $cedula
 * @property string $telefono
 * @property string $celular
 * @property string $foto
 *
 * @property Pago[] $pagos
 * @property Rol $rol
 */
class Usuario extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usuario';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
           [['usuario', 'clave', 'id_rol', 'nombre', 'apellido', 'cedula', 'telefono', 'celular'], 'required'],
                   [['id_rol', 'cedula'], 'integer'],
                   [['foto'], 'safe'],
                   [['usuario', 'nombre', 'apellido'], 'string', 'max' => 50],
                   [['clave', 'telefono', 'celular'], 'string', 'max' => 20],
                   [['foto'], 'string', 'max' => 100],
                   [['id_rol'], 'exist', 'skipOnError' => true, 'targetClass' => Rol::className(), 'targetAttribute' => ['id_rol' => 'id_rol']],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_usuario' => 'Id Usuario',
            'usuario' => 'Usuario',
           'clave' => 'Clave',
            'id_rol' => 'Id Rol',
            'nombre' => 'Nombre',
            'apellido' => 'Apellido',
            'cedula' => 'Cedula',
            'telefono' => 'Telefono',
            'celular' => 'Celular',
            'foto' => 'Foto',

        ];
    }


           /**
            * @return \yii\db\ActiveQuery
            */
           public function getPagos()
           {
               return $this->hasMany(Pago::className(), ['id_usuario' => 'id_usuario']);
           }

           /**
            * @return \yii\db\ActiveQuery
            */
           public function getRol()
           {
               return $this->hasOne(Rol::className(), ['id_rol' => 'id_rol']);
           }


    /**
     * {@inheritdoc}
     * @return UsuarioQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UsuarioQuery(get_called_class());
    }

    /////////////////////////////////////////////////////////////////////
    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        /*foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;*/
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public function findByUsername($username)
    {
        /*foreach (self::$users as $user) {
            if (strcasecmp($user['username'], $username) === 0) {
                return new static($user);
            }
                return null;
            }*/
            $usuario= Usuario::find()->where(['usuario'=>$username])->one();
            if($usuario)
            {
                //var_dump($usuario->usuario);
                //exit;
                return $usuario;
            }
            return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id_usuario;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        //return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        //return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($user,$password)
    {
        $usuario= Usuario::find()->where(['usuario'=>$user->usuario])
        ->andwhere(['clave'=>$password])
        ->one();
        //var_dump($usuario);
        //exit;
        if($usuario)
        {
            return $password;
        }

    }
    ///////////////////////////////////////////////////////////////////////////////////////////
}
