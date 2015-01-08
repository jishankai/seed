<?php
#Author: Tang.Hongbo
#Email: tang.hongbo@sh.adways.net
#Time: 2012-07-25
class ApnStupid_Message {

    const MAX_PAYLOAD_LENGTH = 256;

    protected $_identifier;
    protected $_expiry;
    protected $_deviceToken;
    protected $_payload;

    public function __construct() {
    }

    /**
     * Set the token to send notification
     * throw ApnStupid_Exception if the device token is invalid
     */
    public function setToken($deviceToken = null) {
        if (!isset($deviceToken)) {
            throw new ApnStupid_Exception(
                "ERROR: Invalid device token ''"
            );
        }
        if (!preg_match('~^[a-f0-9]{64}$~i', $deviceToken)) {
            throw new ApnStupid_Exception(
                "ERROR: Invalid device token '$deviceToken'"
            );
        }
        $this->_deviceToken = $deviceToken;
        return true;
    }

    /**
     * Format the alert and avoid the error
     * return the formated alert
     */
    protected function _formatAlert($alert) {
# TODO find other errors in alert and throw out(or correct it).
        $alert = addslashes($alert);
        $alert = str_replace("\n", '\n', $alert);
        $alert = str_replace("\r", '\r', $alert);
        $alert = str_replace("\t", '\t', $alert);
        $alert = str_replace("\f", '\f', $alert);
        $alert = str_replace("\\'", '\'', $alert);
        return $alert;
    }

    /**
     * Set how long from sending to expiry time
     * throw ApnStupid_Exception if the $expiry is not a integer
     */
    public function setExpiry($expiry) {
# TODO do something if the $expiry <= 0
        if (!is_int($expiry)) {
            throw new ApnStupid_Exception(
                "ERROR: Invalid expiry value '{$expiry}'"
            );
        }
        $this->_expiry = $expiry;
        return true;
    }

    /**
     * Set the identifier to send
     */
    public function setIdentifier($identifier) {
# TODO to confirm if the identifier is useful
        $this->_identifier = $identifier;
        return true;
    }
    
    /**
     * Set the payload to send
     * throw ApnStupid_Exception if no alert setted
     */
    public function setPayload($payload) {
        if (!isset($payload['aps']['alert'])) {
            throw new ApnStupid_Exception(
                "ERROR: There is no 'aps' => 'alert' in the payload."
            );
        } else {
            $payload['aps']['alert'] = $this->_formatAlert($payload['aps']['alert']);
            $this->_payload = $payload;
        }
        return true;
    }

    /**
     * Get the payload by jsonencode
     * throw ApnStupid_Exception if the payload is too long
     * return the payload formatted by json_encode
     */
    public function getPayload() {
        $jsonPayload = $this->_jsonEncodeEnhanced('UTF-8', 'UTF-8', $this->_payload);
        //$jsonPayload = str_replace('"aps":[]', '"aps":{}', $jsonPayload);
        $lengthOfPayload = strlen($jsonPayload);
        if (self::MAX_PAYLOAD_LENGTH < $lengthOfPayload) {
            throw new ApnStupid_Exception(
                "ERROR: Payload is too long: $lengthOfPayload bytes. " .
                "Maximum length is " . self::MAX_PAYLOAD_LENGTH . " bytes."
            );
        }
        return $jsonPayload;
    }

    /**
     * convert the payload to a string by json_encode (chinese and japanese supported)
     * return the converted string 
     */
    protected function _jsonEncodeEnhanced($inCharSet, $outCharSet, $in) {
        $out = $this->_urlencodeIconvEnhanced($inCharSet, 'UTF-8', $in);
        $out = json_encode($out);
        $out = urldecode($out);
        $out = iconv('UTF-8', $outCharSet, $out);
        return $out;
    }

    /**
     * convert the charset of the payload
     * return the urlencode format of payload
     */
    protected function _urlencodeIconvEnhanced($inCharSet, $outCharSet, $in) {
        if (is_string($in)) {
            $out = iconv($inCharSet, $outCharSet, $in);
            $out = urlencode($out);
        } elseif (is_array($in)) {
            foreach ($in as $key=>$value) {
                $out[$key] = $this->_urlencodeIconvEnhanced($inCharSet, $outCharSet, $value);
            }
        } elseif (is_object($in)) {
            foreach ($in as $key=>$value) {
                $out->$key = $this->_urlencodeIconvEnhanced($inCharSet, $outCharSet, $value);
            }
        } else {
            $out = $in;
        }
        return $out;
    }

    /**
     * package the message
     * return the packed message
     */
    public function getMessage() {
        $identifier = $this->_identifier;
        $expiry = $this->_expiry + time();
        $deviceToken = $this->_deviceToken;
        $payload = $this->getPayload();
        $message = chr(1) . pack("N", $identifier) . pack("N", $expiry) . pack("n", 32) . pack("H*", $deviceToken) . pack("n", strlen($payload)) . $payload;
        return $message;
    }

}
?>
