<?php
#Author: Tang.Hongbo
#Email: tang.hongbo@sh.adways.net
#Time: 2012-08-06
class ApnStupid_Push extends ApnStupid_Base {

    const ERROR_SIZE = 6;
    protected $_apnsHost = array(
        'ssl://gateway.push.apple.com',
        'ssl://gateway.sandbox.push.apple.com'
    );
    protected $_apnsPort = '2195';

    /**
     * Send a message to apns
     * throw ApnStupid_Exception if the apns is disconnect
     * throw ApnStupid_Exception if send unmatch chars to the apns
     * return the number of chars sended
     */
    public function apnsSend($message) {
        $sendSum = (int)@fwrite($this->_apnsConnection, $message);
        $msgLength = strlen($message);
        if ($sendSum <> $msgLength) {
            throw new ApnStupid_Exception(
                "WARNING: Send $sendSum chars insteded of $msgLength chars."
            );
        }
        return $sendSum;
    }

    /**
     * Read the error response after sended all message
     * throw ApnStupid_Exception if unable to wait for a stream availability
     * return the error response
     */
    public function readLastError() {
        $aError = false;
        $read = array($this->_apnsConnection);
        $null = NULL;
        $changedStreams = @stream_select($read, $null, $null, $this->_readTimeout);
        
        if ($changedStreams === false) {
            throw new ApnStupid_Exception(
                "WARNING: Unable to wait for a stream availability"
            );
        } else if ($changedStreams > 0) {
            $aError = $this->readError();
        }
        return $aError;
    }

    /**
     * Read the error from apns
     * return false if it's unable to read an error
     * return the unpacked error response if there is an error from apns
     */
    public function readError() {
        $sError = false;
        $sError = @fread($this->_apnsConnection, self::ERROR_SIZE);
        if ($sError === false || strlen($sError) != self::ERROR_SIZE) {
            return false;
        } else {
            $aError = unpack('Ccommand/Cstatus/Nidentifier', $sError);
            print "INFO: Error status: ".$aError['status']."  Error identifier:".$aError['identifier']."\n";
            return $aError;
        }
    }
 
}
?>
