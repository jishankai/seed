<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
    public $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

	public function authenticate()
	{
	    $command = Yii::app()->db->createCommand("SELECT playerId FROM player WHERE userId = :userId");
        $exist = $command->bindValue(':userId', $this->userId)->queryScalar();
		
		if(!$exist)
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		else
			$this->errorCode=self::ERROR_NONE;
		return !$this->errorCode;
	}
}
