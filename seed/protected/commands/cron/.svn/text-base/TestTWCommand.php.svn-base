<?php

class TestTWCommand extends CConsoleCommand {

    private function usage() {
        echo "Usage: BackupSeed start\n";
    }

    private function start($numfrom, $numto) {
        //$file = dirname(__FILE__) . '/c.txt';
        //touch($file);
        //$num = intval(file_get_contents($file));
        //file_put_contents($file, ++$num);
        //$i = 0 ;
        //while( true ) {
        //$num = intval(file_get_contents($file));
        //	$i ++ ;
        //	echo "index : $count ; $i\n";
        //	sleep(1);
        //}

        require dirname(__FILE__) . '/../../extensions/share/tmhOAuth.php';
        require dirname(__FILE__) . '/../../extensions/share/tmhUtilities.php';

        $count = 1;

        while ($count < 20000) {

            $this->testTwitter($count, $numfrom, $numto);

            $count++;

            sleep(5);
        }
    }

    public function run($args) {
        if (isset($args[0]) && $args[0] == 'start') {
            $this->start($args[1], $args[2]);
        } else {
            return $this->usage();
        }
    }

    public function testTwitter($count, $numfrom, $numto) {
        $num = rand($numfrom, $numto);
//        function outputError2($tmhOAuth) {
//            echo 'Error: ' . $tmhOAuth->response['response'] . PHP_EOL;
//            tmhUtilities::pr($tmhOAuth);
//        }

        $tmhOAuth = new tmhOAuth(array(
                    'consumer_key' => 'qolcPqW8LH9fI4rknQH8ew',
                    'consumer_secret' => 'knNcDcZmr7XloA4SrPf7db3bBmURMOz8iKXnZTxFJ1U',
                ));

        $userModel = new UserModel('UserTwitter');
        $tokenArray = $userModel->getTwitterTokenDB($num);
        $tmhOAuth->config['user_token'] = $tokenArray['userToken'];
        $tmhOAuth->config['user_secret'] = $tokenArray['userSecret'];

        $image = 'c:\Water lilies.png';

        $code = $tmhOAuth->request(
                'POST', 'https://upload.twitter.com/1/statuses/update_with_media.json', array(
            'media[]' => "@{$image};type=image/jpeg;filename={$image}",
            'status' => 'Picture time',
                ), true, // use auth
                true  // multipart
        );

        if ($code == 200) {
            //tmhUtilities::pr(json_decode($tmhOAuth->response['response']));
            //outputError2($tmhOAuth);

            $body = $tmhOAuth->response['response'];
            $path = dirname(__FILE__) . '/log/num' . $num . '-succeededlog-' . date('Y-m-d H-i-s', time()) . '.txt';
            $this->writefile($body, $path);
            echo 'num:' . $num . 'count:' . $count . "succeeded\n";
        } else {
            tmhUtilities::pr(htmlentities($tmhOAuth->response['response']));
            //outputError2($tmhOAuth);

            $body = $tmhOAuth->response['response'];
            $path = dirname(__FILE__) . '/log/num' . $num . '-errorlog-' . date('Y-m-d H-i-s', time()) . '.txt';
            $this->writefile($body, $path);
            echo 'num:' . $num . 'count:' . $count . "error\n";
            echo $body . "\n\n\n\n\n\n";
        }

        $tmhOAuth = null;
    }

    public function writefile($body, $path) {
        touch($path);
        file_put_contents($path, $body);
        return 1;
    }

}

?>