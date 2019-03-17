<?php

namespace api\models;

use common\models\Token;
use common\models\User;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;

    private $_user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * @return Token|null
     */
    public function auth()
    {
        if ($this->validate()) {
            if($token = $this->getUser()->getValidToken()) {
//echo"EXISTING token<pre>"; print_r($token->expired_at .' > '. time()); echo"</pre>"; //DEBUG
            } else {
                $_token = new Token();
                $_token->user_id = $this->getUser()->id;
                $_token->generateToken(time() + 60 + 3);   //3600 * 24
                $token = $_token->save() ? $_token : null;
//echo"NEW token<pre>"; print_r($token); echo"</pre>";    //DEBUG
            }

            // удаляю старые токены юзера
            // DELETE FROM `token` WHERE (`user_id`=1) AND (`expired_at` < 1552852209)
            Token::deleteAll([
                'AND',
                ['user_id' => $this->getUser()->id],
                ['<', 'expired_at', time()]
            ]);

            return $token;
        } else {
            return null;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}
