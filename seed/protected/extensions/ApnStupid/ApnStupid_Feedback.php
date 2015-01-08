<?php
#Author: Tang.Hongbo
#Email: tang.hongbo@sh.adways.net
#Time: 2012-08-07
class ApnStupid_Feedback extends ApnStupid_Base {

    const TUPLELENGTH = 38;
    protected $_apnsHost = array(
        'ssl://feedback.push.apple.com',
        'ssl://feedback.sandbox.push.apple.com'
    );
    protected $_apnsPort = '2196';

    /**
     * Reading the stream written by the feedback service until there is no more data to read
     * Return the queue of the feedbacks
     */
    public function receive() {
        $feedbackQueue = array();
        $buffer = '';
        while (!feof($this->_apnsConnection)) {
            $buffer .= fread($this->_apnsConnection, 3762);
            $bufferLength = strlen($buffer);
            while ($bufferLength >= self::TUPLELENGTH) {
                $feedbackTuple = substr($buffer, 0, self::TUPLELENGTH);
                $buffer = substr($buffer, self::TUPLELENGTH);
                $bufferLength -= self::TUPLELENGTH;
                $feedback = unpack('Ntimestamp/ntokenLength/H*deviceToken', $feedbackTuple);
                $feedbackQueue[] = $feedback;
                print $feedback['timestamp'] .','. $feedback['tokenLength'] .','. $feedback['deviceToken'] . "\n";
            }

            $read = array($this->_apnsConnection);
            $null = NULL;
            $changedStreams = stream_select($read, $null, $null, $this->_readTimeout);
            if ($changedStreams === false) {
                throw new ApnStupid_Exception(
                    "WARNING: Unable to wait for a stream availability"
                );
            }
        }
        return $feedbackQueue;
    }

}
?>
