<?php

class ItemStampUse extends CBehavior {

    public function parseEffect($itemMeta, $item, $mailId) {
        $mail = Yii::app()->objectLoader->load('Mail', $mailId);
        $getDays = $mail->getDays;
        //check point
        $item->useItem($itemMeta, 'Use Stamp');
        $itemObject = $itemMeta->itemObject;
        if (isset($itemObject->effect['reduceTime'])) {
            $reduce = $itemObject->effect['reduceTime'] * 3600;
        } else {
            throw new CException(Yii::t('Item', 'this is not a stamp'));
        }
        //MAIL_DEFAULTVALUE IS 0
        if ($getDays < $reduce || $reduce == MAIL_DEFAULTVALUE) {
            $newDays = time();

            //快速投递成就检查
            $achieveEvent = new AchievementEvent($mail->playerId, ACHIEVEEVENT_STAMP);
            $achieveEvent->onAchieveComplete();
        } else {
            $newDays = $getDays - $reduce;
        }
        $mail->getDays = $newDays;
        $mail->saveAttributes(array('getDays'));
        GlobalMessage::addMailGift($mailId);
    }

}