<?php
class PointbackController extends Controller
{    
    public function filters()
    {
        return array(
        );
    }

    //API for AppDriver
    public function actionAppdriver() {
        $ip = Util::getRealIp(); 
        $session = Yii::app()->session;
        $session->open();
        $now = time();
        if($ip == '59.106.111.156' || $ip == '59.106.111.152') {
            $id = AppDriverPoint::findIdByAchieveId($_REQUEST['achieve_id']);
            if(empty($id)) {
                //创建AppDriverPoint记录
                $achieve_id = empty($_REQUEST['achieve_id'])?NULL:$_REQUEST['achieve_id'];
                $identifier = empty($_REQUEST['identifier'])?NULL:$_REQUEST['identifier'];
                $point = empty($_REQUEST['point'])?NULL:$_REQUEST['point'];
                $payment = empty($_REQUEST['payment'])?NULL:$_REQUEST['payment'];
                $campaign_id = empty($_REQUEST['campaign_id'])?NULL:$_REQUEST['campaign_id'];
                $campaign_name = empty($_REQUEST['campaign_name'])?NULL:$_REQUEST['campaign_name'];
                $advertisement_id = empty($_REQUEST['advertisement_id'])?NULL:$_REQUEST['advertisement_id'];
                $advertisement_name = empty($_REQUEST['advertisement_name'])?NULL:$_REQUEST['advertisement_name'];
                $accepted_time = empty($_REQUEST['accepted_time'])?NULL:$_REQUEST['accepted_time'];
                $url =  $_SERVER['REQUEST_URI'];

                $pointCreateInfo = array(
                    'achieve_id' => $achieve_id,
                    'identifier' => $identifier,
                    'point' => $point,
                    'payment' => $payment,
                    'campaign_id' => $campaign_id,
                    'campaign_name' => $campaign_name,
                    'advertisement_id' => $advertisement_id,
                    'advertisement_name' => $advertisement_name,
                    'accepted_time' => $accepted_time,
                    'url' => $url,
                    'createTime' => $now
                );
                $id = AppDriverPoint::create($pointCreateInfo);
            }

            $appDriverPoint = Yii::app()->objectLoader->load('AppDriverPoint', $id);

            if(!empty($achieve_id) && !empty($identifier)) {
                if(empty($point)) {
                    echo 0;
                    $appDriverPoint->status = 0;
                    $appDriverPoint->saveAttributes(array('status'));
                    return;
                }
                if(empty($session['achieve_id']) || ($session['achieve_id'] != $achieve_id)) {					
                    $trasaction = Yii::app()->db->beginTransaction();
                    try{
                        //奖励
                        $userMoneyReward = new UserMoneyReward($point, 'APPDRIVER', '[%appDriverTitle%]'.$point);
                        $userMoneyReward->reward($identifier);

                        $trasaction->commit();
                    }
                    catch (CException $e) {
                        $trasaction->rollback();
                        echo 0;
                        $appDriverPoint->status = 0;
                        $appDriverPoint->saveAttributes(array('status'));
                        return;
                    }
                    $session['achieve_id'] = $achieve_id;

                    echo 1;
                    $appDriverPoint->status = 1;
                    $appDriverPoint->saveAttributes(array('status'));
                    return;
                }
                if($session['achieve_id'] == $achieve_id) {
                    echo 1;
                    $appDriverPoint->status = 1;
                    $appDriverPoint->saveAttributes(array('status'));
                    return;
                }				
            }
            else {
                echo 2;
                $appDriverPoint->status = 2;
                $appDriverPoint->saveAttributes(array('status'));
                return;
            }
        } else {
            echo 2;
            $id = AppDriverError::findIdByIP($ip);
            if (empty($id)) {
                $errorCreateInfo = array(
                    'ip' => $ip,
                    'url' => $_SERVER['REQUEST_URI']
                );
                AppDriverError::create($errorCreateInfo);
            } else {
                $appDriverError = Yii::app()->objectLoader->load('AppDriverError', $id);
                $appDriverError->count++;
                $appDriverError->saveAttributes(array('count'));
            }
            return ;
        }
    }
}
?>
