<?php
#Author: Tang.Hongbo
#Email: tang.hongbo@sh.adways.net
#Time: 2012-08-06
class ApnStupid_Base {

    protected $_apnsHost;
    protected $_apnsPort;
    protected $_pushUrl;
    protected $_sslPem;
    protected $_entrustPem;
    protected $_passPhrase;
    protected $_sandboxMode = true;
    protected $_apnsConnection;
    protected $_retryTime = 3;
    protected $_retryInterval = 1000;
    protected $_readTimeout = 2;

    /**
     * set the sandbox mode and the push url
     * throw ApnStupid_Exception if the value of sandbox mode is not a boolean
     */
    public function __construct($sandboxMode = true) {
        if (!is_bool($sandboxMode)) {
            throw new ApnStupid_Exception(
                "ERROR: The value of sandboxMode should be a boolean"
            );
        }
        $this->_sandboxMode = $sandboxMode;
        if (!is_array($this->_apnsHost)) {
            throw new ApnStupid_Exception(
                "ERROR: No host setted in your class"
            );
        }
        if (!isset($this->_apnsPort)) {
            throw new ApnStupid_Exception(
                "ERROR: No port setted in your class"
            );
        }
        $this->_pushUrl = ($this->_sandboxMode ? $this->_apnsHost[1] : $this->_apnsHost[0]) . ':' . $this->_apnsPort;
    }

    /**
     * Set the passphrase of the pem file
     */
    public function setPassPhrase($passPhrase) {
        $this->_passPhrase = $passPhrase;
        return true;
    }

    /**
     * Set the retry times to reconnect the apns
     * throw ApnStupid_Exception if the value is not a integer
     */
    public function setRetryTime($retryTime) {
        if (!is_int($retryTime)) {
            throw new ApnStupid_Exception(
                "ERROR: Invalid value of retryTime: '$retryTime'"
            );
        }
        $this->_retryTime = $retryTime;
        return true;
    }

    /**
     * Set the retry interval to reconnect the apns
     * throw ApnStupid_Exception if the value is not a integer
     */
    public function setRetryInterval($retryInterval) {
        if (!is_int($retryInterval)) {
            throw new ApnStupid_Exception(
                "ERROR: Invalid value of retryInterval: '$retryInterval'"
            );
        }
        $this->_retryInterval = $retryInterval;
        return true;
    }

    /**
     * Set the cert pem file
     * throw ApnStupid_Exception if the file is unreadable
     */
    public function setSslPem($sslPem) {
        if (!is_readable($sslPem)) {
            throw new ApnStupid_Exception(
                "ERROR: Unalbe to read the file: '$sslPem'"
            );
        }
        $this->_sslPem = $sslPem;
        return true;
    }

    /**
     * Set the root pem file
     * throw ApnStupid_Exception if the file is unreadable
     */
    public function setEntrustPem($entrustPem) {
        if (!is_readable($entrustPem)) {
            throw new ApnStupid_Exception(
                "ERROR: Unalbe to read the file: '$entrustPem'"
            );
        }
        
        $this->_entrustPem = $entrustPem;
        return true;
    }

    /**
     * Try to connect the apns
     * throw ApnStupid_Exception if catch if from the function _connect()
     * return true if connected to the apns; return false if failed to connect to the apns
     */
    public function apnsConnect() {
        $retryTime = 0;
        $connected = false;
        while (!$connected) {
            try {
                $connected = $this->_connect();
            } catch (ApnStupid_Exception $e) {
                if ($retryTime > $this->_retryTime) {
                    throw $e;
                } else {
                    usleep($this->_retryInterval);
                }
            }
            $retryTime++;
        }
        return $connected ? true : false;
    }

    /**
     * create a stream and connect to the apns
     * throw ApnStupid_Exception if no ssl pem file setted, or other reason lead to fail to connect to the apns
     */
    protected function _connect() {
        $apnsUrl = $this->_pushUrl;
        $streamContext = stream_context_create();
        if (isset($this->_entrustPem)) {
            stream_context_set_option($streamContext, 'ssl', 'verify_peer', isset($this->_entrustPem));
            stream_context_set_option($streamContext, 'ssl', 'cafile', $this->_entrustPem);
        }
        if (isset($this->_sslPem)) {
            stream_context_set_option($streamContext, 'ssl', 'local_cert', $this->_sslPem);
        } else {
            throw new ApnStupid_Exception(
                "ERROR: No local cert pem file setted"
            );
        }
        if (isset($this->_passPhrase)) {
            stream_context_set_option($streamContext, 'ssl', 'passphrase', $this->_passPhrase);
        }

        $this->_apnsConnection = @stream_socket_client($apnsUrl, $error, $errorString, 60, STREAM_CLIENT_CONNECT, $streamContext);
        if (!$this->_apnsConnection) {
            throw new ApnStupid_Exception(
                "ERROR: Unable to connect to '$apnsUrl': $error ($errorString)"
            );
        }
        return true;
    }

    /**
     * Set the blocking mode of the stream
     * throw ApnStupid_Exception if the value of the mode is invalid
     */
    public function setBlocking($mode) {
        if (($mode !== 1) && ($mode !== 0)) {
            throw new ApnStupid_Exception(
                "ERROR: Invalid value of mode: '$mode'"
            );
        }
        stream_set_blocking($this->_apnsConnection, $mode);
        return true;
    }

    /**
     * Set the value of timeout when use the method of stream_select
     */
    public function setReadTimeout($timeout) {
        if (!is_int($timeout)) {
            throw new ApnStupid_Exception(
                "ERROR: The value of timeout should be an integer"
            );
        }
        $this->_readTimeout = $timeout;
        return true;
    }
 
    /**
     * Close the connection to apns
     */
    public function apnsClose() {
        fclose($this->_apnsConnection);     
    }

}
?>
