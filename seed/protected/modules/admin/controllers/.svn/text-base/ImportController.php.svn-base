<?php
/**
 * Import
 *
 * @author Ji.Shankai
 * @version 1.0
 * @copyright Ji.Shankai, 2012-07-09
 * @package Seed
 **/
class ImportController extends Controller
{
    public function actionMission()
    {
        if (isset($_REQUEST['verifyCode'])) {
            $verifyModel = new VerifyCode($_REQUEST['verifyCode']);
            if ($verifyModel->validate()) {
                Yii::import('ext.phpexcelreader.JPhpExcelReader');
                $data=new JPhpExcelReader(YII::app()->basePath.'\data\import\Quest.xls');

                for ($i = 1; $i <= $data->colcount(); $i++) {
                    $headingsArray[$i] = $data->val(1, $i);
                }
                $r = 0;
                $namedDataArray = array();
                for ($i = 2; $i <= $data->rowCount(); $i++) {
                    foreach ($headingsArray as $columnKey=>$columnHeading) {
                        $namedDataArray[$r][$columnHeading] = $data->val($i, $columnKey);
                    }
                    $r++;
                }

                foreach ($namedDataArray as $insertArr) {
                    DbUtil::insert(Yii::app()->db, 'mission', $insertArr, false, true);
                }

                $this->render(array('isSuccess'=>1));
            } else {
                foreach ($model->getErrors() as $attribute=>$errors) {
                    $message[] = implode("<br />", $errors);
                }
                $this->error(implode("<br />", $message));
            }
        }

        $this->render('mission');
    }

    public function actionAchievement()
    {
        if (isset($_REQUEST['verifyCode'])) {
            $verifyModel = new VerifyCode($_REQUEST['verifyCode']);
            if ($verifyModel->validate()) {
                Yii::import('ext.phpexcelreader.JPhpExcelReader');
                $data=new JPhpExcelReader(YII::app()->basePath.'\data\import\Achievement.xls');

                for ($i = 1; $i <= $data->colcount(); $i++) {
                    $headingsArray[$i] = $data->val(1, $i);
                }
                $r = 0;
                $namedDataArray = array();
                for ($i = 2; $i <= $data->rowCount(); $i++) {
                    foreach ($headingsArray as $columnKey=>$columnHeading) {
                        $namedDataArray[$r][$columnHeading] = $data->val($i, $columnKey);
                    }
                    $r++;
                }

                foreach ($namedDataArray as $insertArr) {
                    DbUtil::insert(Yii::app()->db, 'achievement', $insertArr, false, true);
                }

                $this->render(array('isSuccess'=>1));
            } else {
                foreach ($model->getErrors() as $attribute=>$errors) {
                    $message[] = implode("<br />", $errors);
                }
                $this->error(implode("<br />", $message));
            }
        }
        $this->render('achievement');
    }
}
?>
