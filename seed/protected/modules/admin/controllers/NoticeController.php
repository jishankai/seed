<?php

class NoticeController extends Controller {

    //public $layout='//layouts/column1';

    public function actionIndex() {
        $notice = new Notice();

        $this->render('index', array('notice' => $notice));
    }

    public function actionEdit() {
        if (isset($_GET['noticeId']) and (preg_match("/^\d+$/", $_GET['noticeId']))) {
            $noticeId = $_GET['noticeId'];
        } else {
            throw new SException('noticeId error');
        }
        $noticeObj = new Notice();
        $notice = $noticeObj->findByPk($noticeId);
        if (isset($notice)) {
            $model = new EditNoticeForm;
            //判断传回的是否存在Form是否存在
            if (isset($_POST['EditNoticeForm'])) {
                $model->attributes = $_POST['EditNoticeForm'];
                if ($model->validate()) {
                    $model->updateData($_GET['noticeId']);
                }
                foreach ($_POST['EditNoticeForm'] as $name => $value) {
                    //循环FORM找到startTime的元素
                    if ($name == 'startTime') {
                        //获取缓存中记录的值
                        $noticeStartTime = Yii::app()->cache->get('noticeStartTime');
                        //startTime转换成unixTime格式
                        $value = strtotime($value);
//                        if ($noticeStartTime != '') {
//                            //如果缓存不为空，判断当中的值的时间小于当前时间的去掉
//                            foreach ($noticeStartTime as $index => $arr) {
//                                if ($noticeStartTime[$index] < time()) {
//                                    unset($noticeStartTime[$index]);
//                                }
//                            }
//                        }
                        //并且把更新的时间值，写入缓存中
                        if ($value > time()) {
                            $noticeStartTime[$_GET['noticeId']] = $value;
                        }
                        //更新缓存
                        Yii::app()->cache->set('noticeStartTime', $noticeStartTime);
                        break;
                    }
                }
            } else {
                $model->fillData($notice);
            }

            $this->render('edit', array('notice' => $notice, 'model' => $model));
        } else {
            throw new SException('noticeId is not exists');
        }
    }

    public function actionAdd() {
        $model = new EditNoticeForm;
        if (isset($_POST['EditNoticeForm'])) {
            $model->attributes = $_POST['EditNoticeForm'];
            if ($model->validate()) {
                $insertId = $model->addData();
                //循环FORM找到startTime的元素
                foreach ($_POST['EditNoticeForm'] as $name => $value) {
                    if ($name == 'startTime') {
                        //获取缓存中记录的值
                        $noticeStartTime = Yii::app()->cache->get('noticeStartTime');
                        //startTime转换成unixTime格式
                        $value = strtotime($value);
//                        if ($noticeStartTime != '') {
//                            //如果缓存不为空，判断当中的值的时间小于当前时间的去掉
//                            foreach ($noticeStartTime as $index => $arr) {
//                                if ($noticeStartTime[$index] < time()) {
//                                    unset($noticeStartTime[$index]);
//                                }
//                            }
//                        }
                        //插入新加的值，如果比当前值小写入也不影响，不显示NEW，下次添加清除
                        if ($value > time()) {
                            $noticeStartTime[$insertId] = $value;
                        }
                        //写入缓存
                        Yii::app()->cache->set('noticeStartTime', $noticeStartTime);
                        break;
                    }
                    $this->redirect(array('index'));
                }
            }
        }
        $this->render('add', array('model' => $model));
    }

}