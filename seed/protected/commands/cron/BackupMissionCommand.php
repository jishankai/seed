<?php
class BackupSeedCommand extends CConsoleCommand {
    private function usage() {
        echo "Usage: BackupSeed start\n";
    }
    
    private function start() {
        MissionRecord::backup();
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
