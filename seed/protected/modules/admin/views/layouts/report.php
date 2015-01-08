<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php Global $v ?>
<link href="<?php echo Yii::app()->request->baseUrl; ?>/themes/basic-min.css?v=<?php echo $v; ?>" rel="stylesheet" type="text/css" media="screen" />
<link href="<?php echo Yii::app()->request->baseUrl; ?>/themes/page-min.css?v=<?php echo $v; ?>" rel="stylesheet" type="text/css" media="screen" />
<link href="<?php echo Yii::app()->request->baseUrl; ?>/themes/page_a-min.css?v=<?php echo $v; ?>" rel="stylesheet" type="text/css" media="screen" />
<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery-ui-1.7.1.custom.css?v=<?php echo $v; ?>" rel="stylesheet" type="text/css" media="screen" />
<title><?php echo CHtml::encode($this->pageTitle); ?></title>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/themes/js/jquery-1.5.2.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui-1.7.1.custom.min.js"></script>
<style>
body {
    font-size:12px; margin:0; padding:0; text-align:center; text-align:center; background-color: #FFFFFF
}
.block {
	width:950px; height:auto; margin:auto; clear:both;
}
fieldset {
	color:#666; margin-top:10px; display:block; text-align:left;
}
legend {
	font-weight:bold; color:#360; text-align:left;
}
.line {
	width:100%; height:10px; clear:both;
}
.dialogcontent {
	 overflow-y:scroll; border:solid 5px #ddd;
}
.btnMenu {
    BORDER-RIGHT: #7b9ebd 1px solid; PADDING-RIGHT: 2px; BORDER-TOP: #7b9ebd 1px solid; PADDING-LEFT: 2px; FONT-SIZE: 1px; FILTER: progid:DXImageTransform.Microsoft.Gradient(GradientType=0, StartColorStr=#ffffff, EndColorStr=#cecfde); BORDER-LEFT: #7b9ebd 1px solid; CURSOR: hand; COLOR: black; PADDING-TOP: 2px; BORDER-BOTTOM: #7b9ebd 1px solid
}
.titlePage {
	color: #660000; font-size:30px; font-weight: bold;
}
.titleMenu {
    font-size:16px; font-weight: bold;
}
a {
	padding:5px; color:blue; cotext-decoration:none;
}
table {
    border-width:1px; border-color:black; border-collapse:collapse; margin:auto;
}
th,td {
    border-width:1px; border-style:inset; border-color:black; valign="middle";
}
.btnAdmin1 {
    BORDER-RIGHT: #7b9ebd 1px solid; PADDING-RIGHT: 2px; BORDER-TOP: #7b9ebd 1px solid; PADDING-LEFT: 2px; FONT-SIZE: 12px; FILTER: progid:DXImageTransform.Microsoft.Gradient(GradientType=0, StartColorStr=#ffffff, EndColorStr=#cecfde); BORDER-LEFT: #7b9ebd 1px solid; CURSOR: hand; COLOR: black; PADDING-TOP: 2px; BORDER-BOTTOM: #7b9ebd 1px solid;
}
.t_report1 {
    width:750px; border:0px; background:#000000; text-align:left;
}
.t_report1 tr {
    background:#ffffff;
}
.t_report1 td {
    padding:5px;
}
.in_title {
    background:#cccccc;
    text-align:center;
    width:120px;
}
.su_title_2 {
    background:#cccccc;
    text-align:center;
}
.t_title{
    background:#cccccc;
}

</style>
</head>
<body <?php $background = ModuleUtil::loadconfig('admin', 'background'); if($background['SHOW_BACKGROUND_COLOR'] == 1){ ?> style="background-color:<?php echo $background['BACKGROUND_COLOR'] ?>" <?php } ?>>
<br>
<div class="block titlePage">
    <?php echo 'REPORT'; ?>
</div>
<hr>
<div class="block">
	<span class="titleMenu">
        <?php echo CHtml::link('支付report', array('/admin/report/payment')); ?>
        &nbsp;&nbsp;&nbsp;
        <?php //echo CHtml::link('道具report', array('/admin/report/goods')); ?>
<!--        &nbsp;&nbsp;&nbsp;-->
        <?php echo CHtml::link('活跃用户report', array('/admin/report/activeUser')); ?>
<!--        &nbsp;&nbsp;&nbsp;-->
        <?php // echo CHtml::link('玩家分布report', array('/admin/report/playerInfo')); ?>
<!--	    &nbsp;&nbsp;&nbsp;-->
        <?php // echo CHtml::link('剩余SP统计report', array('/admin/report/sp')); ?>
    </span>
</div>
<hr>
<div class="block">
<?php echo $content; ?>
</div>

<script language="javascript">
$(document).ready(function(){
	$("fieldset").mouseover(function(){
		$(this).addClass('checked');
	}).mouseout(function(){
		$(this).removeClass('checked');
	});	
	
	
	//$("input").focus(function(){$(this).blur();});
	$("a").focus(function(){$(this).blur();});
});
window.dealReturnData = function(data){
	var dataId = 'retunDataArea';
	SevenDialog.create(dataId);
	$('#'+dataId).hide();
	
	SevenDialog.alert(data,'','关闭窗口',dataId);
	//var top = Math.max(Math.floor( ($(document).height()-$('#retunDataArea>.data').height())/2-100 ),20);
	var top = 50;
	$('#retunDataArea>.data').css('width','800px').css('margin-top',top+'px');
	$('#retunDataArea>.data>.content').css('width','90%').css('height','600px').css('margin-top','3px').addClass('dialogcontent');
	$('#'+dataId).show();
}

window.Seven = {
	
	/** list all variables from a element **/
	dump:function(elem){
		var str = '';
		if( typeof(elem)=='object' )
			for( t in elem ) str+='['+t+'] => '+elem[t]+';\n';
		else 
			str = elem ;

		SevenDialog.alert(str);
	} ,

	error:function(message) {
		SevenDialog.alert(message);
	} ,
	
	run:function(string,encode) {
		if( encode ) string = this.decode(string);
		try {
			eval(string);
		}
		catch(e){
			this.dump(e);
			//SevenDialog.alert(string);
		}	
	},
	encode:function(string){
		return encodeURI(string);
	},
	decode:function(string){
		return decodeURI(string);	
	},
	isset:function(element) {
		return typeof(element)!='undefined';
	},
	empty:function(element) {
		return typeof(element)=='undefined'||element==false||element==null;
	}
	
}

/**
 * Seven common dialog actions.
 * such as alert use "SevenDialog.alert('Mission complete!')"
 */
window.SevenDialog = {
	elements:[],
	defaultElement :'SevenDialogMain',
	zIndex: 10 ,
	confirmValue:'OK',
	cancelValue:'Cancel',
	showCover : true ,
	alert:function( message,action,confirmValue,elemId ){
		if( !Seven.empty(confirmValue) ) this.confirmValue = confirmValue ;
		if( Seven.empty(elemId) ) elemId = this.defaultElement ;
		this.create(elemId);
		this.setContent(message,elemId,action);
		//alert(message);
	},
	
	close:function(elemId){
		if( Seven.isset(elemId) ){
			this.remove(elemId);
		}
		else {
			for( k in this.elements ) {
				if( !this.elements[k] ) continue ;
				this.remove(this.elements[k]);
			}	
		}
	},
	setContent:function(content,elemId,action){
		var actionData = 'SevenDialog.close(\''+elemId+'\'); Seven.run(\''+Seven.encode(action)+'\',true);';
		var htmlData = '';
		if( this.showCover ) {
			htmlData += '<div style="position:absolute; width:100%; height:100%; top:0; left:0; background-color:#000000; opacity:0.5;"></div>';
		}
		htmlData += '<div class="data" style="position:relative; width:280px; min-height:80px; height:auto;  background-color:#FFF; border:solid 2px #06F; margin:auto; margin-top:20%;">' +
				'<div class="content" style="width:80%; margin:auto; height:auto; line-height:18px; margin-top:10px; clear:both ; text-align:left; color:#333; " >'+content+'</div>' +
				'<div class="buttons" style="width:100%; height:20px; line-height:20px; margin-top:15px; margin-bottom:10px; clear:both; text-align:center;"><input type="button" value="'+this.confirmValue+'" onclick="'+actionData+'"></div>' +
				//'<div class="close" style="position:absolute; right:3px; top:3px; border:solid 1px #ccc; width:12px; height:12px; cursor:pointer; font-size:10px;" onclick="SevenDialog.close(\''+elemId+'\');">X</div>' +
			'</div>';
		$('#'+elemId).html(htmlData);
	},
	create:function(elemId,content) {
		if( $('#'+elemId).attr('id')==elemId ) {
			$('#'+elemId).css('z-index',this.zIndex++);
			return true ;
		}
		var element = document.createElement("div");
		$(element).attr('id',elemId).attr('style','position:absolute; top:0; width:100%; height:100%; left:0px; text-align:center; display:block; z-index:'+(this.zIndex++)+';');
		//if( !this.showCover )$(element).css('height','1px');
		if( !Seven.empty(content) ) $(element).html(content);
		$('body').append(element);
		this.elements[elemId] = elemId ; 
	},
	remove:function(elemId){
		if( !$('#'+elemId) ) return true ;
		$('#'+elemId).remove();
		this.elements[elemId] = false ;  
	}

}


window.SevenLoader = {
	dataType : 'json' ,
	callBackFunc : '' ,
	dataelemId: '' ,
	get:function(url,callback){
		if( !Seven.empty(callback) ) {
			this.callBackFunc = callback;
		}
		else {
			this.callBackFunc = '';	
		}
		url += '&isAjax=1&t='+Math.floor(Math.random()*10000000);

		$.get(url,'',this.commonCallBack,this.dataType) ;
	},
	show : function(url,elemId){
		if( elemId ) {
			this.dataelemId = elemId;
		}
		else {
			this.dataelemId = '';
		}
		this.get(url);
	},

	/** Ajax callback function. **/
	commonCallBack : function(ret){
		if( ret.stateCode == 1 ) {
			if( SevenLoader.callBackFunc ) {
				try {
					eval(SevenLoader.callBackFunc+'(ret.data);');
				}
				catch(e) {
					SevenDialog.alert('Return data error.');
				}
			}
			else {
				SevenLoader.commonDeal( ret.data );
			}
		}
		else if( ret.stateCode == 2 ) {
			SevenDialog.alert(ret.message,ret.data,false,'DialogRequestSuccess');
		}
		else {
			SevenDialog.alert(ret.stateCode+':'+ret.message);
		}
	} , 

	/** data show function **/
	commonDeal : function(data) {
		if( SevenLoader.dataelemId!='' ) {
			$('#'+SevenLoader.dataelemId).html(data);
		}
		else {
			SevenDialog.alert(data);
		}
	}

}

window.SetDate = {
    setDay : function(i) {
        var today = new Date();
    	var new_day = new Date();
    	new_day.setDate(today.getDate() - i);
    	var year = new_day.getYear();
    	var month = new_day.getMonth() + 1;
    	var day = new_day.getDate();
    	if (year < 2000) { year += 1900; }
    	if (month < 10) { month = "0" + month; }
    	if (day < 10) { day = "0" + day; }
    	document.getElementById('reportForm').getElementsByClassName('startDate')[0].value = year+'/'+month+'/'+day;
        document.getElementById('reportForm').getElementsByClassName('endDate')[0].value = year+'/'+month+'/'+day; 
    },
    setMonth : function(i) {
    	var today = new Date();
    	var new_month = new Date();
    	var day = today.getDate();
        var j = 0;
        for (j=0;j<i;j++){
    	    new_month.setDate(0);
        }
    	var year = new_month.getYear();
    	var month = new_month.getMonth() + 1;
    	var day = new_month.getDate();
    	if (year < 2000) { year += 1900; }
    	if (month < 10) { month = "0" + month; }
    	if (day < 10) { day = "0" + day; }
    	document.getElementById('reportForm').getElementsByClassName('startDate')[0].value = year+'/'+month+'/'+'01';
        document.getElementById('reportForm').getElementsByClassName('endDate')[0].value = year+'/'+month+'/'+day;
    },
}
</script>
</body>
</html>