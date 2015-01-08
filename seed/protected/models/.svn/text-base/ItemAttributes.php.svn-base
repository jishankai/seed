<?php

class ItemAttributes extends CBehavior {

    public function getDecoData($id) {
        $arrayInfo = Util::loadconfig("DecoItem");
        $info = array();
        foreach ($arrayInfo as $i => $arr) {
            if ($i == $id) {
                $info['type'] = $arr['type'];
                $info['sizeX'] = $arr['sizeX'];
                $info['sizeY'] = $arr['sizeY'];
                $info['name'] = $arr['name'];
                $info['grow'] = $arr['grow'];
                $info['image'] = $arr['image'];
                $info['price'] = $arr['price'];
                $info['sellPrice'] = $arr['sellPrice'];
                $info['category'] = $arr['category'];
                $info['description'] = $arr['description'];
                $info['moneyType'] = $arr['moneyType'];
                $info['level'] = $arr['level'];
                $info['canSell'] = $arr['canSell'];
                
                break;
            }
        }
        return $info;
    }

    public function getUseItemData($id) {
        $arrayInfo = Util::loadconfig("UseItem");
        $info = array();
        foreach ($arrayInfo as $i => $arr) {
            if ($i == $id) {
                $info['name'] = $arr['name'];
                $info['description'] = $arr['description'];
                $info['category'] = $arr['category'];
                $info['moneyType'] = $arr['moneyType'];
                $info['type'] = $arr['type'];
                $info['price'] = $arr['price'];
                $info['sellPrice'] = $arr['sellPrice'];
                $info['image'] = $arr['image'];
                $info['effect'] = $arr['effect'];
                $info['canSell'] = $arr['canSell'];
                
                break;
            }
        }
        return $info;
    }

}

?>