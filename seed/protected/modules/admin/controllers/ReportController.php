<?php

class ReportController extends Controller {

//    public $layout='/layouts/report';

    public function actionPayment() {
        $data = array();
        $model = new SalesReportForm;
        $model->init();

        if (isset($_POST['SalesReportForm'])) {
            $model->attributes = $_POST['SalesReportForm'];
            if ($model->validate()) {
                $data['result'] = $model->reportResult();
                $data['total'] = $model->totalResult();
            }
        }

        $viewFile = 'payment';
        $params = array('model' => $model, 'data' => $data);
        $content = $this->renderPartial($viewFile, $params, true);
        $this->render('report', array('content' => $content));
    }

    public function actionGoods() {
        $data = array();
        $model = new GoodsReportForm;
        $model->init();

        if (isset($_POST['GoodsReportForm'])) {
            $model->attributes = $_POST['GoodsReportForm'];
            if ($model->validate()) {
                $data['result'] = $model->reportResult();
                $data['total'] = $model->totalResult();
            	foreach ($data['result'] as $line) {
    				if (($line['num'] == 0)) {
            			continue;
            		}
            		if (isset($line['linkId'])) {
            			$Goods=new ShopGoods($line['linkId']);
            			$data['name'][$line['linkId']]=$Goods->getName();
            		}
    			}
            }
        }
        $viewFile = 'goods';
        $params = array('model' => $model, 'data' => $data);
        $content = $this->renderPartial($viewFile, $params, true);
        $this->render('report', array('content' => $content));
    }

    public function actionActiveUser() {
        $data = array();
        $model = new ActiveUserReportForm;
        $model->init();

        if (isset($_POST['ActiveUserReportForm'])) {
            $model->attributes = $_POST['ActiveUserReportForm'];
            if ($model->validate()) {
                $data['report'] = $model->reportResult();
            }
        }

        $viewFile = 'activeUser';
        $params = array('model' => $model, 'data' => $data);
        $content = $this->renderPartial($viewFile, $params, true);
        $this->render('report', array('content' => $content));
    }

    public function actionPlayerInfo() {
        $data = array();
        $model = new PlayerInfoReportForm;
        $model->init();

        if (isset($_POST['PlayerInfoReportForm'])) {
            $model->attributes = $_POST['PlayerInfoReportForm'];
            if ($model->validate()) {
                $data['report'] = $model->reportResult();
            }
        }

        $viewFile = 'playerInfo';
        $params = array('model' => $model, 'data' => $data);
        $content = $this->renderPartial($viewFile, $params, true);
        $this->render('report', array('content' => $content));
    }

    public function actionSp() {
        $data = array();
        $model = new SpReportForm;
        $model->init();
        if (isset($_POST['SpReportForm'])) {
            $model->attributes = $_POST['SpReportForm'];
            if ($model->validate()) {
                $data['gold'] = $model->reportResult();
            }
        }

        $viewFile = 'sp';
        $params = array('model' => $model, 'data' => $data);
        $content = $this->renderPartial($viewFile, $params, true);
        $this->render('report', array('content' => $content));
    }

}