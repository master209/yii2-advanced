<?php
namespace api\models;

use yii\base\Model;
use common\models\User;
use common\models\Token;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Данный логин уже используется'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Данный email уже используется'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->status = User::STATUS_ACTIVE;
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();

/*  $user
(
    [status] => 10
    [username] => user1
    [password_hash] => $2y$13$uOOZiZPjp5ETklD
    [auth_key] => vBVZmzgNGank9eS5hvekj07S
)
*/

//        return $user->save() ? $user : null;

        if($user->save()) {
            $token = new Token();
            $token->user_id = $user->id;
            $token->generateToken(time() + 3600 * 24 * 365);   //60 + 3
            if($token->save()) {
//echo"signup() token<pre>"; print_r($token->attributes); echo"</pre>";    die();     //DEBUG
                return $token;
            }
        } else {
            echo"signup() save user ERROR:<pre>"; print_r($user->errors); echo"</pre>";    die();     //DEBUG
        }
        return null;
    }
}
