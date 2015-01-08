<?php
class BackupMailCommand extends CConsoleCommand {
    private function usage() {
        echo "Usage: BackupMail start\n";
    }
    
    private function start() {
		MailModel::mailToHistory();
    }
    
    public function run($args) {
        if(isset($args[0]) && $args[0] == 'start'){
            $this->start();
        }else{
            return $this->usage();
        }
    }
    
}
?>