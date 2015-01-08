<?php if (isset($_REQUEST['noticeId']) && $_REQUEST['noticeId'] != 0) { ?>
    <?php $mailNotice = Yii::app()->objectLoader->load('MailNotice', $_REQUEST['noticeId']); ?>   
    <p style="height:360px;">
        <?php if (strlen($mailNotice->notice) > 500) { ?>
            <a href="#" id="upButton"></a>
            <a href="#" id="downButton"></a>
        <?php } ?>
        <span style="width:360px; height:360px; float:left; overflow:hidden; ">
            <span style="float:left;" id="df">
                <span id="testContent">
                    <?php echo $mailNotice->notice; ?>
                </span>
            </span>
        </span>
    </p>
    <div class="tx01 pl10">
    </div>
<?php } ?>
<?php if (strlen($mailNotice->notice) > 500) { ?>
    <script>
        $(document).ready(function(){
            var spanHeight = new Flipsnap('#df', { position: 'y',distance:Math.ceil($('#df').height()-360)/10,maxPoint:10,callback:function(){
                    if(spanHeight.currentY <= spanHeight.maxY){
                        $('#upButton').hide();
                    }else{
                        $('#upButton').show();
                    }
                    
                    if(spanHeight.currentY == 0){
                        $('#downButton').hide();
                    }else{
                        $('#downButton').show();
                    }
                } });
        });
    </script>
    <script>
        scrollElement = function( elementId,speed ){
            self = this ;
            self.lineHeight = 20 ;
            self.speed = 300;
            self.step = 2 ;
            self.elementId = elementId ;
            self.element = $('#'+elementId);
            self.timer = null ;
            self.position = 1 ;

            self.toBottom = function(){
                self.position = -1 ;
                self.timer = setInterval(self.toHeight,self.speed/self.lineHeight*self.step);
            }

            self.toTop = function() {
                self.position = 1 ;
                self.timer = setInterval(self.toHeight,self.speed/self.lineHeight*self.step);
            } 
            self.toHeight = function(){
                var newTop = Math.min(10,self.element.offset().top+self.position*self.step ) ;
                newTop = Math.max(newTop,-self.element.height());
                self.element.offset({top:newTop});
                //console.log(self.element.offset().top);
            }
            self.stop = function(){
                clearInterval( self.timer );
            }


        }

        var scroller = new scrollElement('testContent');
        $('#upButton').mousedown(function(){
            if($('#testContent').height() > 360)
            {
                scroller.toBottom();
            }
        }).mouseup( function(){
            scroller.stop();
        } );
        $('#downButton').mousedown(function(){
            if($('#testContent').height() > 360)
            {
                scroller.toTop();
            }
        }).mouseup( function(){
            scroller.stop();
        } );
    </script>
<?php } ?>
<script>
    function hoverClass(){
        addHover(".b_list_03 a");
        addHover(".b_con_11 .b_btn_07");
        addHover(".b_con_13 .squer");
        addHover(".b_btn_02");
        addHover(".b_btn_03");
        addHover(".b_btn_04");
        addHover(".b_btn_05");
        addHover(".b_btn_07");
        addHover(".b_btn_08");
        addHover(".b_ico");
        addHover(".next_page");

        addHover(".next_page_left");
        addHover(".b_fsalediv .btn");
        addHover(".b_btn53");
        addHover(".b_bg01 .b_icon01");
        addHover(".b_bg01 .b_icon01.b_icon01left");
        addHover(".a");
        addHover(".a_btn1");

        addHover(".a_btn03");
        addHover(".a_btn09");
        addHover(".a_btn13");
        addHover(".a_btn14");
        addHover(".a_btn18 a");
	
        addHover(".a_btn19_1");
        addHover(".a_btn19_2");
        addHover(".a_btn19_3");
	
        addHover(".a_btn19_4");
        addHover(".a_btn20");
        addHover(".a_btn21");
        addHover(".a_btn22");

	
        addHover(".a_btn23");
        addHover(".home_icon1");
        addHover(".help_icon");
        addHover(".menu_icon1");
        addHover(".a_bar2 a.hover");
        addHover(".new_btn");
	
	
        addHover(".a_list1 ul li h3 .num01");
        addHover(".a_list1 ul li h3 .num02");
	
        addHover(".a_list1 ul li h3 .num03");
        addHover(".a_list1 ul li h3 .num04");
        addHover(".a_list1 ul li h3 .num05");
        addHover(".a_list1 ul li h3 .num06");
        addHover(".a_list1 ul li h3 .num07");
        addHover(".a_list1 ul li h3 .num08");
        addHover(".a_list1 ul li h3 .num09");
        addHover(".a_list1 ul li h3 .num10");
	
        addHover(".a_btn11");
        addHover(".a_btn04");
        addHover(".a_btn05 a");
        addHover(".a_bar3 img");
	
        addHover(".a_button01");
        addHover(".b_con_10.a_con_02 .tx02 em");
        addHover(".b_btn_07.a_btn_blue");
        addHover(".a_bg_01 .b_icobg .b_ico");
        addHover(".a_con_f01 .a_abtn1");
	
	
        addHover(".bg_001 .this_nav li");

    }

    function addHover(element){
        $(element).bind("touchstart",function(){
            $(this).addClass("hover");
        });
        $(element).bind("touchend",function(){
            $(this).removeClass("hover");
        });
    }
</script>