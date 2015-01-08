<?php

class RewardAttributes extends CBehavior {

    public function getReward($index) {
        $arrayReward = Util::loadconfig("Reward");
        $reward = array();

        foreach ($arrayReward as $i => $arr) {
            if ($i == $index && $arr['type'] == 'gold') {
                $reward['id'] = $arr['id'];
                $reward['type'] = $arr['type'];
                if (isset($arr['gold']))
                    $reward['gold'] = $arr['gold'];
                if (isset($arr['rand']))
                    $reward['rand'] = $arr['rand'];
                if (isset($arr['levelGold']))
                    $reward['levelGold'] = $arr['levelGold'];
                break;
            } else if ($i == $index && ($arr['type'] == 'useItem' || $arr['type'] == 'resItem')) {
                $reward['id'] = $arr['id'];
                $reward['type'] = $arr['type'];
                $reward['num'] = $arr['num'];
                break;
            } else if ($i == $index && $arr['type'] == 'seed') {
                $reward['id'] = $arr['id'];
                $reward['type'] = $arr['type'];
                $reward['num'] = $arr['num'];
                break;
            } else if ($i == $index && $arr['type'] == 'money') {
                $reward['id'] = $arr['id'];
                $reward['type'] = $arr['type'];
                $reward['money'] = $arr['money'];
                break;
            }
        }
        return $reward;
    }

}

?>