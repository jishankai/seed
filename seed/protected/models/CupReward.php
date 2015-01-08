<?php

class CupReward extends Reward {
    public function __construct($id, $num = 1) {
        parent::__construct($id, $num);
    }

    public function reward($playerId) {
        $gardenModel = Yii::app()->objectLoader->load('GardenModel', $playerId);
        $gardenModel->changeCupInfo($this->getName());
    }

}

