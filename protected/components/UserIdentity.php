<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
    private $_id;

    /**
     * Authenticates a user.
     * The example implementation makes sure if the username and password
     * are both 'demo'.
     * In practical applications, this should be changed to authenticate
     * against some persistent user identity storage (e.g. database).
     * @return boolean whether authentication succeeds.
     */
    public function authenticate()
    {
        $user = User::model()->find('LOWER(username) = :username', array(':username' => $this->username));

        if ($user === null) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } elseif (md5($this->password) !== $user->password) {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        } else {
            $this->processAuthenticate($user);
        }

        return $this->errorCode == self::ERROR_NONE;
    }

    public function processAuthenticate(User $user)
    {
        $this->_id = $user->id;
        $this->errorCode = self::ERROR_NONE;

        foreach ($user as $k => $v) {
            $this->setState($k, $v);
        }
    }

    public function getId()
    {
        return $this->_id;
    }
}