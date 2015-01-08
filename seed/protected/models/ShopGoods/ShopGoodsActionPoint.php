<?php 

class ShopGoodsActionPoint extends CBehavior {

    public function parseEffect( $player,$number=1 ) {
            $maxPoint = $player->getPlayerPoint('actionPoint')->getMax();
            $addPoint = $maxPoint * $this->getOwner()->getOwner()->effect;
            $player->getPlayerPoint('actionPoint')->addValue($addPoint);
    }

    public function parseSendEffect( $fromPlayer,$toPlayer,$number=1 ) {
    }

    public function getMyName() {
        return '';
    }

}
