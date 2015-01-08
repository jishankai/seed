<?php
class ItemCost extends Cost
{
    private $_provider;

    private function getProvider($playerId)
    {
        if(!isset($this->_provider)){
            $this->_provider = Yii::app()->objectLoader->load('ItemManager', $playerId);
        }
        return $this->_provider;
    }

    public function getName()
    {
        $id = parent::getName();
        $item = ItemManager::makeItem($id);
        return $item->itemName;
    }

    public function getItem()
    {
        $id = parent::getName();
        $item = ItemManager::makeItem($id);
        return $item;
    }

    public function pay($playerId)
    {
        $provider = $this->getProvider($playerId);
        $id = parent::getName();
        $provider->useMItem($id, $this->value);
    }

    public function isAffordable($playerId)
    {
        $provider = $this->getProvider($playerId);

        $id = parent::getName();
        if($provider->getItemNum($id) >= $this->value){
            return true;
        }else{
            return false;
        }
    }
}

?>
