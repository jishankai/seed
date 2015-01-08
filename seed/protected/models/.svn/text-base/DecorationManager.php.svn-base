<?php

class DecorationManager extends CBehavior {

    //从商店中买、添加到花园里
    public function addDecorationGarden($gardenId, $decoration, $x, $y, $dir) {
        $garden = Yii::app()->objectLoader->load('Garden', $gardenId);
        $garden->checkOwner($this->getOwner()->playerId);
        $garden->addDecoration($decoration, $x, $y, $dir);
        $garden->updateDecoExtraGrow($decoration, 'add');
    }

    //从花园中出售
    public function removeBuildingGarden($gardenId, $decoration, $x, $y) {
        $garden = Yii::app()->objectLoader->load('Garden', $gardenId);
        $garden->checkOwner($this->getOwner()->playerId);
        $garden->removeDecoration($x, $y);
        $garden->updateDecoExtraGrow($decoration, 'remove');
    }

    //从仓库移动装饰到花园
    public function MoveDecorationToGarden($playerId, $gardenId, $decoration, $x, $y, $dir) {
        $item = Yii::app()->objectLoader->load('Item', $playerId);
        $item->checkOwner($this->getOwner()->playerId);
        $garden = Yii::app()->objectLoader->load('Garden', $gardenId);
        $garden->checkOwner($this->getOwner()->playerId);
        $garden->addDecoration($decoration, $x, $y, $dir);
        $item->useItem($decoration, 'Move Decoration');
        $garden->updateDecoExtraGrow($decoration, 'add');
    }

    //在花园中移动装饰
    public function MoveDecorationAtGarden($gardenId, $decoration, $fromx, $fromy, $tox, $toy, $dir) {
        $garden = Yii::app()->objectLoader->load('Garden', $gardenId);
        $garden->checkOwner($this->getOwner()->playerId);
        $garden->moveDecoration($decoration, $fromx, $fromy, $tox, $toy, $dir);
    }

    //从花园移动装饰到仓库
    public function MoveDecorationToItem($playerId, $gardenId, $decoration, $x, $y) {
        $garden = Yii::app()->objectLoader->load('Garden', $gardenId);
        $garden->checkOwner($this->getOwner()->playerId);
        $garden->removeDecoration($x, $y);
        $item = Yii::app()->objectLoader->load('Item', $playerId);
        $item->checkOwner($this->getOwner()->playerId);
        $item->addItem($decoration,'Move To Item');
        $garden->updateDecoExtraGrow($decoration, 'remove');
    }

    //添加装饰到仓库中
    public function addDecorationItem($playerId, $decoration) {
        $item = Yii::app()->objectLoader->load('Item', $playerId);
        $item->checkOwner($this->getOwner()->playerId);
        $item->addItem($decoration,'Add To Item');
    }

    //从仓库中出售装饰
    public function removeDecorationItem($playerId, $decoration) {
        $item = Yii::app()->objectLoader->load('Item', $playerId);
        $item->checkOwner($this->getOwner()->playerId);
        $item->useItem($decoration, 'Remove Decoration');
    }

}