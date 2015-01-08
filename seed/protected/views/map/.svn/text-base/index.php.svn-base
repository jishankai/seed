<style>
.common_diaglog { padding-top:70px; }
.place {z-index:2;}
.placeLeft {left:0;}
</style>
<div class="b_bg01" style="background: none;">
    <div class="header" style="z-index:9000;">
		<p class="header01">
			<i class="header_exp">
				<em id="LV" class="header_exptitle" style="font-size:22px;">lv <?php echo $playerinfo['level']?></em>				
				<em id="EXP" class="header_exptext"><?php echo $playerinfo['EXP']=='MAX'?$playerinfo['EXP']:'<span>'.$playerinfo['EXP'].'</span>/'.$playerinfo['NEXP'];?></em>
				<em id="EXP2" class="header_expbg"><span style="width:<?php echo $playerinfo['EXP2']?>%;"></span></em>
			</i>
			<i class="header_heart">
				<img src="themes/images/imgb/header_icon02.png" />
				<em id="HEART" style="width:<?php echo $playerinfo['AP2']?>%;"></em>
				<span id="HEART2"><?php echo $playerinfo['AP'].'/'.$playerinfo['APMAX']?></span>
				<a href="#" onclick="CommonMap.AP()"></a>
				<span class="time" id="time"></span>
			</i>
			<i class="header_money">
				<img src="themes/images/imgb/header_icon03.png" />
				<span id="money1"><?php echo $playerinfo['num1']?></span>
				<em id="money2"><?php echo $playerinfo['num2']?></em>
			</i>
		</p>
		<a href="#" class="home_icon1" onclick="NativeApi.callback({'musicoff':'1','close':'close'});"></a>
	</div>
	<div style="position:absolute;top:0px;">
	<section class="demo">
		<div id="wrapper" class="wrapper" style="height:640px;width:960px; background-color:#fff;">
            <!--  position:relative;left:-<?php echo $info['map'][$placeId-1]['x']-480;?>px;top:-<?php echo $info['map'][$placeId-1]['y']-320;?>px; -->
			<div class="flipsnap" id="mapImage" style="width:<?php echo $info['img']['width'];?>px; height:<?php echo $info['img']['height'];?>px; background:url(<?php echo $info['img']['url'].'?v='.SeedVersion::getVersion();?>) no-repeat left top; background-size:50%;">
            	<div style="width:<?php echo $info['img']['width'];?>px; height:<?php echo $info['img']['height'];?>px; background:url(<?php echo str_replace('.jpg','_1.jpg',$info['img']['url']).'?v='.SeedVersion::getVersion();?>) no-repeat right top; background-size:50%;"></div>
				<?php
					foreach ($info['map'] as $area) {
						echo '<div class="map_dot" style=" position:absolute; z-index:2; width:64px; height:64px; left:'.($area['x']+10).'px; top:'.($area['y']+90).'px;" onclick="CommonMap.Move('.$area['x'].','.$area['y'].','.$area['id'].')"><i style="margin-top:20px;"></i></div>';
					}
				?>
				<span id="figure" style="width:75px; height:100px; position:absolute; left:<?php echo $info['map'][$placeId-1]['x'];?>px; top:<?php echo $info['map'][$placeId-1]['y'];?>px; z-index:3; padding-top:60px; padding-left:2px;" value="<?php echo $info['map'][$placeId-1]['id'];?>" /></span>
				<script type="text/javascript">
				<?php 
				if ($seed==null) {
				?>
					$('#figure').html('<img src="themes/img/pic_03.png" />');
					$('#figure').css('padding-top','40px');
				<?php
				}else {
					echo 'SeedUnit("figure",'.$seed->getDisplayData().');';
				}
				?>
				</script>
			</div>
		</div>
	</section>
	</div>
	<input type="hidden" id="mapId" value="<?php echo $mapId?>" />
	<?php if ($mapId>1) {?>
	<div class="b_icon01 b_icon01left">
		<a href="#" onclick="CommonMap.Previouse()" id="leftButton"></a>
		<p class="b_icon01text" id="leftText"><span>「<?php echo $info['Pname']?>」へ</span></p>
	</div>
	<?php }?>
	<?php if ($mapId<5) {?>
	<div class="b_icon01">
		<a href="#" onclick="CommonMap.Next()" id="rightButton"></a>
		<p class="b_icon01text" id="rightText"><span>「<?php echo $info['Nname']?>」へ</span></p>
	</div>
	<?php }?>
	<div class="footer" style="z-index:10;">		
		<div class="b_frame03">
			<h4><?php echo $info['name']?></h4>
			<p id="message" style="line-height:20px;white-space:nowrap;overflow:hidden;">
			</p>
			<a href="#" class="b_btn53" onclick="CommonMap.Log()"></a>
	    </div>
		<a href="#" class="b_btn_07" onclick="CommonMap.Explore()" id="exploreButton">
            <span style=" width:100%;"></span>
            <div class="pr">
                <em><?php echo Yii::t('Map', 'map_01')?><span class="heart color_red">-<?php echo $info['actionPoint']?></span></em>
            </div>        
        </a> 
	</div>
	<input type="hidden" id="APtype" value="1">
</div>
<script type="text/javascript">
var time=SeedTimer.getMyTime();
$(document).ready(function() {
	if (<?php echo $playerinfo['AP']?><<?php echo $playerinfo['APMAX']?>){
		var time1=<?php echo $playerinfo['RT']?>;
		time=SeedTimer.getMyTime();
		SeedTimer.start('time',time1,Change,1);
	}else {
		$("#time").hide();
	}
});

function Change() {
	var time1=time;
	var time2=SeedTimer.getMyTime();
	var time3=Math.ceil((time2-time1)/120);
	var HEART=document.getElementById("HEART2").innerText;
	var AP=(HEART.split("/"))[0];
	var APMAX=(HEART.split("/"))[1];
	AP=parseInt(AP);
	APMAX=parseInt(APMAX);
	AP=AP+time3;
	if (AP>=APMAX) {
		AP=APMAX;
		document.getElementById("HEART2").innerHTML=AP+"/"+APMAX;
		document.getElementById("HEART").style.width="100%";
		$("#time").hide();
	}else {
		document.getElementById("HEART2").innerHTML=AP+"/"+APMAX;
		document.getElementById("HEART").style.width=(AP/APMAX)*100+"%";
		time=time2;
		setTimeout( function(){ SeedTimer.start('time',120,Change,1) },10 );
	}
}

    var mapScroll ;
    <?php if ($this->actionType == REQUEST_TYPE_AJAX) { ?>
    	/*
    	var map = new   Flipsnap('#mapImage', {
        	position : 'xy' ,
        	maxPoint : 1  
    	});
    	map.maxX = $('#mapImage').width()-960 ;
    	map.maxY = $('#mapImage').height()-640 ;
    	map._setXY(Math.max(-map.maxX,-<?php echo $info['map'][$placeId-1]['x']-480;?>),Math.max(-map.maxY,-<?php echo $info['map'][$placeId-1]['y']-320;?>));
    	*/
    	mapScroll = new iScroll('wrapper', { zoom: false,overMove:false, hScrollbar: false,vScrollbar: false});
    	mapScroll._pos(0,-693);
    <?php } else { ?>
    	LoadAction.push(function(){
        	/*
        	var map = new   Flipsnap('#mapImage', {
            	position : 'xy' ,
            	maxPoint : 1  
	        });
    	    map.maxX = $('#mapImage').width()-960 ;
        	map.maxY = $('#mapImage').height()-640 ;
        	map._setXY(Math.max(-map.maxX,-<?php echo $info['map'][$placeId-1]['x']-480;?>),Math.max(-map.maxY,-<?php echo $info['map'][$placeId-1]['y']-320;?>));
	        */
    	    mapScroll = new iScroll('wrapper', { zoom: false,overMove:false, hScrollbar: false,vScrollbar: false});
        	mapScroll._pos(0,-693);
    	});
    <?php } ?>
	

	function loaded(){
		<?php if ($notice==1) {?>
			AjaxLoader.get('<?php echo $this->createUrl('map/Item') ?>')
		<?php }?>
		var Pmessage = '';
		<?php if (!empty($messageOld)) {?>
		Pmessage+='<div><span class="heart color_red">-<?php echo $messageOld['actionPoint'];?></span>';
		var type = '<?php echo $messageOld['type']?>';
		switch (type){
			case "gold":
				Pmessage+='<span class="cao">+'+<?php echo $messageOld['id'];?>+'</span>';
				break;
			case "res":
            case "deco":
                Pmessage+='<span><img align="absmiddle" style="width:35px; height:35px; margin-top:-3px;" src="<?php echo $messageOld['data']?>" />+1</span>';
                break;
			case "seed":
				Pmessage+='<span style="height:30px;"><div id="seed'+<?php echo $messageOld['id'];?>+'" style=" position: relative; left: 25px; top: 30px; width: 30px; height: 30px; "></div><div style=" position: relative; left: -45px; top: -30px; width: 45px; height: 20px; ">+1</div></span>';
                delayCreateSeed.add("seed"+<?php echo $messageOld['id'];?>,<?php echo json_encode($messageOld['data']);?>);
				break;
			default:
				break;
		}
		Pmessage+='<span class="exp">+'+<?php echo $messageOld['Exp'];?>+'</span></div>';
		<?php }?>
        $("#message").append(Pmessage);
        delayCreateSeed.action();
	}

	window.CommonMap = {
		Move : function(id1,id2,id3) {
            if( window.mapExploring ) return ;
			var pic=document.getElementById("figure"); 
			pic.setAttribute("value",id3);
			
			picW = pic.width,//获得图片的宽度
			picH = pic.height,//获得图片的高度
			mLeft = id1,//目标的X坐标
			mTop = id2;//目标的Y坐标
			var left = parseInt(pic.style.left),//图片的原始LEFT坐标位置
			top = parseInt(pic.style.top),//图片的TOP坐标位置
			xpos,ypos;
			move = function() {
				if(top == mTop && left == mLeft) return false;//    如果图片的LEFT与TOP与目标坐标相等，则返回
				if(mTop>top){
					ypos = Math.ceil((mTop-top)/10);//一次移动距离的10分之一
					top = top+ypos;//图片的坐标在原来的基础上加上移动距离
				}else {
					ypos = Math.ceil((top - mTop)/10);
					top = top-ypos;
				}
				if(mLeft>left){
					xpos = Math.ceil((mLeft - left)/10);
					left = left+xpos;
				}else {
					xpos = Math.ceil((left - mLeft)/10);
					left = left-xpos;
				}
				pic.style.left = left+"px";//设置图片的LEFT坐标
				pic.style.top = top+"px"; 
			}
			setInterval(move,10);
		},

		Log : function () {
			logShow.url = '<?php echo $this->createUrl('map/log') ?>'
			logShow.show();
		},

		Next : function () {
			var mapId = document.getElementById("mapId").value;
			mapId++;
			AjaxLoader.get('<?php echo $this->createUrl('map/AjaxShow') ?>'+"&mapId="+mapId,CommonMap.AjaxCB);
		},

		Previouse : function () {
			var mapId = document.getElementById("mapId").value;
			mapId--;
			AjaxLoader.get('<?php echo $this->createUrl('map/AjaxShow') ?>'+"&mapId="+mapId,CommonMap.AjaxCB);
		},

		AjaxCB : function (data) {
			setTimeout(function() {
			$("#page").html(data);
				$("#rightButton").hover(function() {
					$("#rightText").addClass("place");
					$("#rightText").css("display","table-cell");
				},function() {
					$("#rightText").removeClass("place");
					$("#rightText").css("display","none");
				});
				$("#leftButton").hover(function() {
					$("#leftText").addClass("place");
					$("#leftText").addClass("placeLeft");
					$("#leftText").css("display","table-cell");
				},function() {
					$("#leftText").removeClass("place");
					$("#leftText").removeClass("placeLeft");
					$("#leftText").css("display","none");
				});
			},1000);
		},

		AP : function () {
			AjaxLoader.get('<?php echo $this->createUrl('map/Ap') ?>')
		},

		Explore : function () {
            if( window.mapExploring ) return ;
            window.mapExploring = true ;
            var spendTime = 1200 ;
            var step = 2 ;
            var currentCount = 0 ;

            $('#exploreButton').addClass("kong");
            var timer = setInterval(function(){ 
                if( currentCount>=100 ) {
                	$('#exploreButton').removeClass("kong");
                    clearInterval(timer);
                    var Id = $("span[id='figure']").attr("value");
                    var url = '<?php echo $this->createUrl('map/explore') ?>&Id='+Id;
                    AjaxLoader.get(url,CommonMap.ExploreCB);
                    if( window.clearStateTimer ) clearTimeout(window.clearStateTimer);
                    window.clearStateTimer = setTimeout( 'clearExploringState()',3000 );
                }
                currentCount = Math.min(100, currentCount+step) ;
                //console.log(currentCount);
                $('#exploreButton>span').css('width',currentCount+'%');
            },spendTime/100*step);
		},

		ExploreCB : function (data) {
            var message = data.message;
            var messageOld = data.messageOld;
            if (messageOld == false ) {
                var Pmessage = '<div>';
                Pmessage+=getExploreMessage(message);
                /*
                var type = message['type'];
                switch (type){
                    case "gold":
                        Pmessage+='<span class="cao">+'+message['id']+'</span>';
                        break;
                    case "res":
                    case "deco":
                        Pmessage+='<span><img align="absmiddle" style="width:35px; height:35px; margin-top:-3px;" src="'+message['data']+'" />+1</span>';
                        break;
                    case "seed":
                        Pmessage+='<span>seed:'+message['id']+'</span>';
                        break;
                    default:
                        break;
                }*/
                Pmessage+='</div>';
                $("#message").html(Pmessage);
                delayCreateSeed.action();
            }else {
                var Pmessage = '<div id="1">';
                Pmessage+=getExploreMessage(messageOld);
                /*
                var type = messageOld['type'];
                switch (type){
                    case "gold":
                        Pmessage+='<span class="cao">+'+messageOld['id']+'</span>';
                        break;
                    case "res":
                    case "deco":
                        Pmessage+='<span><img align="absmiddle" style="width:35px; height:35px; margin-top:-3px;" src="'+messageOld['data']+'" />+1</span>';
                        break;
                    case "seed":
                        Pmessage+='<span>seed:'+messageOld['id']+'</span>';
                        break;
                    default:
                        break;
                }*/
                Pmessage+='</div>';
                Pmessage+='<div id="2">';
                Pmessage+=getExploreMessage(message);
                Pmessage+='</div>';
                $("#message").html(Pmessage);
                delayCreateSeed.action();
                var o=document.getElementById('message');
                scrollup(o,40,0);
            }

			var info=data.playerinfo;
			document.getElementById("LV").innerHTML='lv '+info['level'];
            if( info['EXP']=='MAX' ) 
                document.getElementById("EXP").innerHTML=info['NEXP'];
            else 
                document.getElementById("EXP").innerHTML='<span>'+info['EXP']+'</span>/'+info['NEXP'];
            if( info['EXP2']>=100&&info['EXP']<info['NEXP'] ) info['EXP2'] = 0 ;
			document.getElementById("EXP2").innerHTML='<span style="width:'+info['EXP2']+'%;"></span>';
			document.getElementById("HEART").style.width=info['AP2']+'%';
			document.getElementById("HEART2").innerHTML=info['AP']+'/'+info['APMAX'];
			document.getElementById("money1").innerHTML=info['num1'];
			document.getElementById("money2").innerHTML=info['num2'];

            clearExploringState();

            var HEART=document.getElementById("HEART2").innerText;
        	var AP=(HEART.split("/"))[0];
        	var APMAX=(HEART.split("/"))[1];
        	AP=parseInt(AP);
        	APMAX=parseInt(APMAX);
        	if (AP>=APMAX) {
        		$("#time").hide();
        		SeedTimer.clear2('time');
        		document.getElementById("time").innerHTML="";
            }else {
            	var timeCD=document.getElementById("time").innerText;
            	$("#time").show();
            	if (timeCD=="" || timeCD=="00:00") {
            		SeedTimer.start('time',120,Change,1);
            	}
            }

            var notice=data.notice;
            if (notice == 1) {
            	AjaxLoader.get('<?php echo $this->createUrl('map/Item') ?>')
            }
		},
	}

    function clearExploringState(){
        window.mapExploring = false ;
        //$('#exploreButton').removeClass('op04');
        //$('.jingdutiao').addClass('op04');
        if( window.clearStateTimer ) clearTimeout(window.clearStateTimer);
    }

	function scrollup(o,d,c){
		if(d==c){
			var t=o.firstChild.cloneNode(true);
			o.removeChild(o.firstChild);
			o.appendChild(t);
			t.style.marginTop=o.firstChild.style.marginTop='0px';
		}
		else{
			var s=3,c=c+s,l=(c>=d?c-d:0);
			o.firstChild.style.marginTop=-c+l+'px';
			window.setTimeout(function(){scrollup(o,d,c-l)},100);
		}
	}

    function getExploreMessage( message ) {console.log(message['type']);
        var Pmessage = '<span class="heart color_red">-'+message['actionPoint']+'</span>';
        switch (message['type']){
            case "gold":
                Pmessage+='<span class="cao">+'+message['id']+'</span>';
                break;
            case "res":
            case "deco":
                Pmessage+='<span><img align="absmiddle" style="width:35px; height:35px; margin-top:-3px;" src="'+message['data']+'" />+1</span>';
                break;
            case "seed":
                Pmessage+='<span style="height:30px;"><div id="seed'+message['id']+'" style=" position: relative; left: 25px; top: 30px; width: 30px; height: 30px; "></div><div style=" position: relative; left: -45px; top: -30px; width: 45px; height: 20px; ">+1</div></span>';
                delayCreateSeed.add("seed"+message['id'],message['data']);
                break;
            default:
                break;
        }
        Pmessage += '<span class="exp">+'+message['Exp']+'</span>';
        return Pmessage ;
    }

var delayCreateSeed = {
    seeds : [] ,
    add : function( id,data ) {
        var self = this ;
        self.seeds[id] = data ;
    },
    action : function(){
        var self = this ;
        console.log(self.seeds);
        for( id in self.seeds ) {
            SeedUnit( id,self.seeds[id],0.25 );
        }
    }
}

<?php if ($this->actionType == REQUEST_TYPE_AJAX) { ?>
loaded();
<?php } else { ?>
LoadAction.push(function(){loaded();});
<?php } ?>
</script>
<?php if ($isGuide) {?>
<script>
//新手引导
var isOver = false;
var ExploreGuide = {
    step: 0,
    zIndex: 9002,
    nextStep: function(){
        this.step++;
        switch (this.step) {
            case 1:
                $('.long #exploreMessage').hide().html('<?php echo Yii::t('GuideMessage', 'exploremessage_1')?>').fadeIn().css({top:0, left:0});
                $('#page').append('<em class="new_hand_03" style="position:absolute; z-index:9003"><span class="new_hand1"></span><span class="new_hand2"></span></em>');
                $('#page').append('<div id="star" style="position:absolute;z-index:9003;width:20%"></div>"');
                $('#star').append('<em class="star"></em>');
                $('#star').append('<em class="star"></em>');
                $('#page').append('<em class="fangxiang new_bottom" style="position:absolute;z-index:9003"></em>"');
                $('.new_hand_03').css({top:520, left:780});
                $('.fangxiang.new_bottom').css({top:440, left:810});
                $('#star').css({top:550, left:756}).click(function(){
                    CommonMap.Explore();
                    setTimeout("ExploreGuide.nextStep()", 1000);
                    $(this).unbind('click');
                });
                break;
            case 2:
                $('.b_text06').hide();
                $('#star').hide();
                $('.fangxiang.new_bottom').hide();
                $('.new_hand_03').hide();
                $('#page').append('<div style="width:216px; height:533px; overflow:hidden; position:absolute;bottom:0px;right:0px;z-index:9004"><div class="b_text06 b_text06_1" style="float:left"><i class="text06pic" style="left:50px;"></i><div class="height"><div id="text"><p id="exploreMessage"></p></div><em class="b_text05goon"><span class="b_text05gnp"></span><span class="b_text05gnp1"></span></em></div></div></div>');
                $('div.height #exploreMessage').html('<?php echo Yii::t('GuideMessage', 'exploremessage_2')?>').fadeIn();
                $('.b_text06_1').click(function(){
                    ExploreGuide.nextStep();
                });
                $('.footer').append('<div id="star" style="position:absolute;top:10px;z-index:9003"></div>');
                $('#star').append('<em class="star"></em>');
                $('#star').append('<em class="star"></em>');
                $('#star').append('<em class="star"></em>');
                $('#star').append('<em class="star"></em>');
                $('#star').append('<em class="star"></em>');
                $('#star').append('<em class="star"></em>');
                $('#star').append('<em class="star"></em>');
                $('#star').append('<em class="star"></em>');
                break;
            case 3:
                $('.footer #star').remove();
                $('.b_text06').fadeIn();
                $('.b_text06_1').hide();
                $('#page').append('<em class="star"  style="position:absolute;z-index:9004;"></em>');
                $('.star').css({top:560, left:600}).click(function(){
                    CommonMap.Log();
                    setTimeout("ExploreGuide.nextStep()", 2000);
                    $(this).unbind('click');
                });
                $('.fangxiang.new_bottom').css({top:440, left:606}).fadeIn();
                $('.long #exploreMessage').html('<?php echo Yii::t('GuideMessage', 'exploremessage_3')?>').fadeIn();
                $('.new_hand_03').css({top:528, left:572}).fadeIn(); 
                break;
            case 4:
                $('.new_bottom').css({top:0, left:890});
                $('.star').css({top:100, left:880}).unbind('click').click(function(){
                    CommonDialog.close();
                    ExploreGuide.nextStep();
                    $(this).unbind('click');
                });
                $('.long #exploreMessage').html('<?php echo Yii::t('GuideMessage', 'exploremessage_4')?>').fadeIn().css({top:260});
                $('.new_hand_03').css({top:80, left:854}); 
                break;
            case 5:
                $('.new_hand_03').hide();
                $('.star').css({top:150, left:690});
                $('.long #exploreMessage').html('<?php echo Yii::t('GuideMessage', 'exploremessage_5')?>').fadeIn();
                $('#page').append('<em class="new_hand" style="position:absolute; z-index:9004"><span class="new_hand1"></span><span class="new_hand2"></span></em>');
                $('.new_hand').css({top:160, left:710}).click(function(){
                    CommonMap.Move(690,730,104);
                    ExploreGuide.nextStep();
                }); 
                $('.new_bottom').css({top:30, left:696});
                break;
            case 6:
                $('.new_bottom').hide();
                $('.star').css({top:0, left:870});
                $('#exploreMessage').html('<?php echo Yii::t('GuideMessage', 'exploremessage_7')?><br /><img src="themes/images/imgb/4_2.png" style="width:26px; height:26px" /><?php echo Yii::t('GuideMessage', 'exploremessage_8')?>').fadeIn().css({left:0});
                $('.new_hand').css({top:20, left:890}).unbind('click').click(function(){
                    if (!isOver) {
                        ExploreGuide.over();
                        setTimeout("isOver = false", 2000);
                    }
                    $(this).unbind('click');
                }); 
                $('#page').append('<i class="fangxiang new_right" style="position:absolute; top:20px; left:740px; z-index:9004"></i>');
                $('#page').append('<em class="swapmap star" style="position:absolute; top:284px; left:860px; z-index:9004"></em>');
                break;
            default:
                // code...
                break;
        }
    },
    over: function(){
        NativeApi.delay(true);
        ajaxLoader.get('<?php echo $this->createUrl('guide/saveStatus&accessLevel=99')?>', function(){
            NativeApi.callback({'musicoff':'1','close':'close'}).doRequest();
        });
    }
}

$(document).ready(function(){
    $('.b_frame05').hide();
    ExploreGuide.nextStep();
});
LoadAction.push(function(){
    $('div.guide_main_cover').show().css({'z-index':9001,'height':640});
});
</script>

<div style="width:421px; height:337px; overflow:hidden; position:absolute;bottom:0px;left:0px;z-index:9003">
<div class="b_text06" style="float:right">
		<i class="text06pic"></i>
		<div class="long">
            <span id="exploreMessage"></span>
		</div>
	</div>
</div>

<?php
}
?>
