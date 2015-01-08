<?php
/**
 * Verify Code Validate
 *
 * @author Ji.Shankai
 * @version 1.0
 * @copyright Ji.Shankai, 2012-07-09
 * @package Seed
 **/
class VerifyCode extends CModel
{
    public $verifyCode;

    function __construct($verifyCode)
    {
        $this->verifyCode = $verifyCode;
    }

    public function attributeNames()
    {
        return array('verifyCode');
    }

    public function rules()
    {
        return array(
	        array('verifyCode', 'captcha'),
	    );
    }
}
?>
