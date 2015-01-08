<?php 
    class FriendPage extends CWidget{
        public $pageNum;
        public $currentPage;
        public $id;
        public function run(){
            if(!isset($this->pageNum)){
                $this->pageNum = 1;
            }
            
            if(!isset($this->id)){
                $this->id = '';
            }
            
            $this->render('FriendPage', array('pageNum'=>$this->pageNum, 'currentPage'=>$this->currentPage, 'id'=>$this->id));
        }
        
    }
?>