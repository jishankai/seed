/**
 * Common javascript actions.
 *
 */
window.Common = {
    isNative:0 ,
    closingNative:0 ,
    ajaxLoadingDelay:0 ,
    ajaxTimeout:10000,
    isDebug:0,
    ajaxErrorMessage:'an error accoured',
    /** list all variables from a element **/
    dump:function(elem){
        var str = '';
        if( typeof(elem)=='object' )
            for( t in elem ) str+='['+t+'] => '+elem[t]+';\n';
        else 
            str = elem ;

        CommonDialog.alert(str);
    } ,
    
    refreshCurrentPage:function(){
        var urls = window.location.href.split('#');
        Common.goUrl(urls[0]);
    } ,

    getLenth:function( obj ){
        var size = 0 ;
        for( k in obj ) {
            size ++ ;
        }
        return size ;
    },
    log:function(ob){
        if(this.isDebug) {
            console.log(ob);
        }
    },
    run:function(string,encode) {
        if( encode ) string = this.decode(string);
        try {
            if( typeof(string)=="string" ) {
                eval(string);
            }
            else {
                this._func = string ;
                this._func() ;
            }
        }
        catch(e){
            this.log(e);
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
    },
    blockAHref:function(){/*
        $('a').click( function(){
            $(this).blur();
            var url = $(this).attr('href') ;
            if( !Common.empty(url)&&url.indexOf('#')!=0 ) {
                window.location.href = url;
                return false ;
            }
        } ).focus(function(){$(this).blur();});*/
    } ,
    goUrl:function(url) {
        if( this.closingNative ) {
            return false ;
        }
        var t = Math.ceil(Math.random()*50)+10;
        setTimeout('window.location.href="'+url+'"',t);
    } ,
    doClick:function( val ){
        this.run(val);
    }
}

window.ajaxLoading = function(){
    var self = this ;
    self.timer = null ;
    self.show = function(){
        var delay = Common.ajaxLoadingDelay || 0 ;
        if( delay>0 ) {
            self.timer = setTimeout( self.start,delay );
        }
        else {
            self.start();
        }
    }
    self.start = function(){
        if( !$('#loading_bg').attr('id') ) {
            $('body').append('<div id="loading_bg" style="position:absolute; top:50%; left:50%; margin:0 0 0 -65px; z-index:3333; display:none;"><div id="loading"></div></div>');
        }
        $('#loading_bg').fadeIn(200);
    }
    self.stop = function(){
        if( self.timer ) {
            clearTimeout(self.timer);
        }
        $('#loading_bg').fadeOut(200);
    }
}

/**
 * deal the ajax request and ajax callback
 *
 */
window.ajaxLoader = {
    dataType:'json' ,
    callBackFunc:[] ,
    errorFunc:[] ,
    dataelemId: [] ,
    request:[] ,
    count:0 ,
    get:function(url,callback,error){
        var self = this ;
        if( !Common.empty(callback) ) {
            self.callBackFunc = callback;
        }
        else {
            self.callBackFunc = '';    
        }
        if( !Common.empty(error) ) {
            self.errorFunc = error;
        }
        else {
            self.errorFunc = '';    
        }
        
        self.request[self.count++] = new ajaxRequest(url,callback,self.dataType);
        
    },
    show:function(url,elemId){
        if( elemId ) {
            this.dataelemId = elemId;
        }
        else {
            this.dataelemId = '';
        }
        this.get(url);
    },

    /** data show function **/
    commonDeal:function(data) {
        var self = this ;
        if( self.dataelemId!='' ) {
            $('#'+self.dataelemId).html(data);
            self.dataelemId='';
        }
        else {
            Common.dump(data);
        }
    }
}
window.AjaxLoader = ajaxLoader ;

window.ajaxRequest = function(url,callback,dataType){
    var self = this ;
    var randKey = Math.floor(Math.random()*10000000);
    url += '&isAjax=1&t='+randKey;
    self.callBackFunc = callback ;

    self.loading = new ajaxLoading();
    self.loading.show();
    
    /** Ajax callback function. **/
    self.commonCallBack = function(ret){
        self.loading.stop();

        if( !Common.empty(ret.callback) ) {
            NativeApi.callback(ret.callback);
        }
        if( ret.stateCode == 1 ) {
            if( self.callBackFunc ) {
                try {
                    if( typeof(self.callBackFunc)=='string' ) {
                        eval(self.callBackFunc+'(ret.data);');
                    }
                    else {
                        self.callBackFunc(ret.data);    
                    }
                    
                }
                catch(e) {
                    Common.dump(e);
                //CommonDialog.alert('Return data error.');
                }
            }
            else {
                ajaxLoader.commonDeal( ret.data );
            }
        }
        else if( ret.stateCode == 999 ) {
            window.location.href = ret.data.url ;
        }
        else if( ret.stateCode < 0 ) {
            switch ( ret.stateCode )
            {
                case -2 :
                    CommonDialog.create('itemFullDialog',ret.message);
                    break;
                case -3 :
                    CommonDialog.create('EXCEPTION_TYPE_AP_NOT_ENOUGH',ret.message);
                    break;
                case -4 :
                    CommonDialog.create('EXCEPTION_TYPE_GOLD_NOT_ENOUGH',ret.message);
                    break;
                case -5 :
                    CommonDialog.create('EXCEPTION_TYPE_MONEY_NOT_ENOUGH',ret.message);
                    break;
                case -6 :
                    CommonDialog.create('breedCDTimeDialog',ret.message);
                    break;
                case -9 :
                    CommonDialog.alertDisappear(ret.message);
                    break;
                default :
                    //ret.data
                    CommonDialog.alert(ret.message);
            }
            NativeApi.delay(false);
        }
        else {
            CommonDialog.alert(ret.message);
        }

        if( !Common.empty(ret.globalMessage) ) {
            GlobalMessage.init( ret.globalMessage );
        }
    }

    self.commonError = function(){
        self.loading.stop();
        CommonDialog.alert( Common.ajaxErrorMessage );
    }

    //$.get(url,'',self.commonCallBack,dataType) ;
    $.ajax({
        type:'GET' ,
        url:url ,
        dataType:dataType ,
        success:self.commonCallBack ,
        error:self.commonError ,
        timeout:Common.ajaxTimeout 
    });
    
}


window.CommonDialog = {
    elements:[],
    defaultElement :'CommonDialogMain',
    zIndex: 1000 ,
    confirmValue:'',
    cancelValue:'',
    defaultConfirmValue:'OK',
    defaultCancelValue:'Cancel',
    actions:[] ,
    lastElementId:'' ,
    jumpOutEffect:true,
    alert:function( message,action,confirmValue,elemId ){
        if( !Common.empty(confirmValue) ) {
            this.confirmValue = confirmValue ;
        }
        else {
            this.confirmValue = this.defaultConfirmValue ;
        }
        if( Common.empty(elemId) ) {
            elemId = this.defaultElement + this.zIndex ;
        }
        this.actions[elemId] = action ;

        this.create(elemId);
        //this.setContent(message,elemId,action);
        var content = ' <div class="a_frame03 a_frame10 frame_alert"><em>'+message+'</em> <div class="b_btnlist02"><a href="###" class="b_btn_07 a_btn_02" onclick="CommonDialog.runAction(\''+elemId+'\');CommonDialog.close(\''+elemId+'\');">'+this.confirmValue+'</a></div> </div>';
        $('#'+elemId).hide().html(content).fadeIn(200);
        Common.blockAHref() ;
        addAllHoverState();
    },

    alertDisappear:function( message,action ){
        var self = this ;
        self.alert( message,action );
        var lastId = this.lastElementId ;
        setTimeout( function(){
            self.close(lastId);
        },2000 );
    },
    
    confirm:function( message,action,confirmValue,cancelValue,cancelAction,elemId ){
        if( !Common.empty(confirmValue) ) {
            this.confirmValue = confirmValue ;
        }
        else {
            this.confirmValue = 'YES' ;
        }
        if( !Common.empty(cancelValue) ) {
            this.cancelValue = cancelValue ;
        }
        else {
            this.cancelValue = 'NO' ;
        }
        if( Common.empty(elemId) ) {
            elemId = this.defaultElement + this.zIndex ;
        }
        this.actions[elemId] = action ;

        this.create(elemId);
        //this.setContent(message,elemId);
        //$('#'+elemId).children('.dialog_buttons').append('<input class="ac w100 h20" type="button" value="'+this.cancelValue+' " onclick="CommonDialog.close(\''+elemId+'\')">');
        var content = '<div class="a_frame03 frame_confirm"> <em>'+message+'</em> <div class="b_btnlist02"><a href="###" class="b_btn_08 a_btn_01" onclick="CommonDialog.close(\''+elemId+'\');">'+this.cancelValue+'</a><a href="#" class="b_btn_07 a_btn_02" onclick="CommonDialog.runAction(\''+elemId+'\');CommonDialog.close(\''+elemId+'\')">'+this.confirmValue+'</a></div> </div>';
        $('#'+elemId).hide().html(content).fadeIn(200);
        Common.blockAHref() ;
        addAllHoverState();
    },

    close:function(elemId){
        if( Common.isset(elemId) ){
            this.remove(elemId);
        }
        else {
            for( k in this.elements ) {
                if( !this.elements[k] ) continue ;
                this.remove(this.elements[k]);
            }    
        }
    },
    setContent:function(content,elemId){
        var buttonId = 'DialogConfirmButton'+elemId;
        var actionData = 'CommonDialog.runAction(\''+elemId+'\');CommonDialog.close(\''+elemId+'\');';
        content = '<div class="main"><span class="zflag">Dialog:'+this.zIndex+'</span><div class="close" onclick="'+actionData+'">X</div>'+content+'<div class="ac mt10 dialog_buttons"><input class="ac w100 h20" id="'+buttonId+'" type="button" value="'+this.confirmValue+' " onclick="'+actionData+'"></div></div>';
        $('#'+elemId).hide().fadeIn(200);
        $('#'+elemId).html(content);
        $('#'+buttonId).focus();
        Common.blockAHref() ;
        addAllHoverState();
    },
    create:function(elemId,content) {
        if( $('#'+elemId).attr('id')==elemId ) {
            $('#'+elemId).css('z-index',this.zIndex++);
        }
        else {
            var element = document.createElement("div");
            $(element).addClass('common_diaglog').attr('id',elemId).css('z-index',this.zIndex++) ;
            if( this.jumpOutEffect ) {
                $(element).addClass('jump_out').one('webkitAnimationEnd',function(){
                    $(this).removeClass('jump_out');
                });
            }
            $('#page').append(element);
        }
        
        if( !Common.empty(content) ) {
            //content = '<span class="zflag">Dialog:'+this.zIndex+'</span><div class="close" onclick="CommonDialog.close(\''+elemId+'\');">X</div>'+content+'<div style="width:100%;clear:both;"></div>';

            $('#'+elemId).html(content);
            //this.setContent( content,elemId );
            Common.blockAHref() ;
            addAllHoverState();
        }
        this.elements[elemId] = elemId ; 
        this.lastElementId = elemId ;
    },
    remove:function(elemId){
        if( !$('#'+elemId) ) return true ;
        if( $('#'+elemId).attr('noremove')=='1' ){
            $('#'+elemId).hide().empty();
        }
        else if( $('#'+elemId).attr('remove')=='1' ){
            $('#'+elemId).hide().remove();
        }
        else {
            $('#'+elemId).fadeOut(200,function(){
                $(this).remove();
            }) ;;
        }
        this.elements[elemId] = null
        delete this.elements[elemId] ;
        
    },
    runAction:function(elemId){
        if( !Common.empty(this.actions[elemId]) ) {
            if( typeof(this.actions[elemId])=='string' )
                Common.run(Common.encode(this.actions[elemId]),true);
            else 
                Common.run(this.actions[elemId]);
        }
    }
    
}

window.GlobalMessage = {
    types:{
    } ,
    init:function(data) {
        var self = this ;
        if( Common.empty(data) ) {
            return ;
        }
        CommonDialog.zIndex += 100 ;
        for( tp in data ) {
            switch( parseInt(tp) ) { 
                case 7 :
                    var size = 0 ;
                    for( id in data[tp] ){
                        var elementId = 'achievementNotice'+size ;
                        CommonDialog.create(elementId,data[tp][id]);
                        $('#'+elementId).css({
                            'width':'auto',
                            'height':'auto',
                            'left':'auto',
                            'top':0,
                            'right':0
                        });
                        size ++ ;
                    }
                    window.removeAchievementNotice = function(i){
                        $('#achievementNotice'+i).fadeOut(200,function(){
                            $(this).remove();
                        });
                    }
                    for( i=size;i>0;i-- ) {
                        setTimeout( 'removeAchievementNotice('+(i-1)+')',(size-i+1)*3000);
                    }
                    break ;
                case 9 :
                    CommonDialog.create('playerLevelUpDialog',data[tp]);
                    break ;
                case 11 :
                    CommonDialog.create('itemFullDialog',data[tp]);
                    break ;
                default :
                    if( tp>=2&&tp<=11 ) {
                        var size = 0 ;
                        for( id in data[tp] ){
                            var elementId = 'NormalNotice'+size ;
                            CommonDialog.create(elementId,data[tp][id]);
                            $('#'+elementId).css({
                                'width':'auto',
                                'height':'auto',
                                'left':'auto',
                                'top':0,
                                'right':0
                            });
                            size ++ ;
                        }
                        window.removeNoticeDefault = function(index){
                            $('#NormalNotice'+index).fadeOut(200,function(){
                                $(this).remove();
                            });
                        }
                        for( i=size;i>0;i-- ) {
                            setTimeout( 'removeNoticeDefault('+(i-1)+')',(size-i+1)*3000);
                        }

                    }
                    else {
                        for( i in data[tp] ) {
                            CommonDialog.alert( data[tp][i] );
                        }
                    }
            }
        }
        CommonDialog.zIndex -= 100 ;
    } 
}

window.SeedTimer = {
    times :[] ,
    timeId: [] ,
    clockId:[] ,
    actions:[] ,
    startTimes:[] ,
    basicTimes:[] ,
    currentTime:[],
    types:[],
    start:function(elemId,time,action,type) {
        this.types[elemId] = type;
        if(time<=0) return false;
        this.timeId[elemId] = elemId ;
        this.times[elemId] = time ;
        this.startTimes[elemId] = this.getMyTime() ;
        if( !Common.empty(action) ) {
            this.actions[elemId] = action ;
        }

        this.play(elemId);
    } ,
    clock :function(elemId){
        var elem = $('#'+this.timeId[elemId]) ;
        var timeStr = this.getTime(elemId) ;
        if( elem.attr('value') ) {
            elem.attr('value',timeStr);
        }
        else {
            elem.html(timeStr);
        }
    } ,
    getTime:function(elemId){
        if( !this.times[elemId]){
            return this.types[elemId]==1||this.types[elemId]==2?'00:00':'00:00:00';
        }
        var currentTime = this.times[elemId]-(this.getMyTime()-this.startTimes[elemId]);
        this.currentTime[elemId] = currentTime;
        if( currentTime<=0 && (typeof this.actions[elemId] == "undefined" || this.actions[elemId] != false)) {
            this.stop(elemId);
            return this.types[elemId]==1||this.types[elemId]==2?'00:00':'00:00:00';
        }
        currentTime = currentTime < 0 ? 0:currentTime;
        switch(this.types[elemId]) {
            case 1:
                return this.getTimeStr2(currentTime);
            case 2:
                return this.getTimeStr3(currentTime);
            default:
                return this.getTimeStr(currentTime);
        }
    },
    getTimeStr:function(currentTime){
        var hour = Math.floor(currentTime/3600);
        var min  = Math.floor((currentTime%3600)/60);
        var sec  = Math.floor(currentTime%60%60);
        return (hour<10?'0':'')+hour+':'+(min<10?'0':'')+min+':'+(sec<10?'0':'')+sec ;
    },
    getTimeStr2:function(currentTime){
        var min  = Math.floor((currentTime%3600)/60);
        var sec  = Math.floor(currentTime%60%60);
        return (min<10?'0':'')+min+':'+(sec<10?'0':'')+sec ;
    },
    getTimeStr3:function(currentTime){
        var hour = Math.floor(currentTime/3600);
        var min  = Math.floor((currentTime%3600)/60);
        return (hour<10?'0':'')+hour+':'+(min<10?'0':'')+min ;
    },
    stop:function(elemId){
        if( elemId ) {
            this.clear(elemId);
        }
        else {
            for( k in this.timeId ) {
                this.clear(k);
            }     
        }
    },
    clear:function(elemId){
        if( this.timeId[elemId]&&this.clockId[elemId] ) {
            clearInterval(this.clockId[elemId]);
            this.timeId[elemId] = false ;
            if( this.actions[elemId] ) {
                if( typeof(this.actions[elemId])=="string" ) {
                    Common.run(this.actions[elemId]);
                }
                else {
                    this._func = this.actions[elemId] ;
                    this._func() ;
                }
                this.actions[elemId] = false ;
            }
        }
    },
    clear2:function(elemId){
        if( this.timeId[elemId]&&this.clockId[elemId] ) {
            clearInterval(this.clockId[elemId]);
            this.timeId[elemId] = false ;
        }
    },
    play:function(elemId){
        this.clockId[elemId] = setInterval('SeedTimer.clock(\''+elemId+'\')',1000);
    },
    getMyTime:function(){
        var date = new Date();
        return Math.floor( date.getTime()/1000 );
    }
}

window.SeedCreator = {
    leftOffset:0 ,
    topOffset :0 ,
    createCanvas:function( seedData,scale ){
        var self = this ;
        var newSeed = document.createElement("canvas");
        newSeed.width = 130 ; 
        newSeed.height = 200 ; 
        //$('#seedArea').append( newSeed );
        var context = newSeed.getContext("2d");
        for( index in seedData ) {
            var image = new Image();
            image.src = seedData[index]['source'] ;

            var newBlock = document.createElement("canvas");
            var blockContext = newBlock.getContext("2d");

            var sx = seedData[index]['frame'][0][0] ;
            var sy = seedData[index]['frame'][0][1] ;
            if( seedData[index]['rotated'] ){
                var sw = seedData[index]['frame'][1][1] ;
                var sh = seedData[index]['frame'][1][0] ;
                var dw = seedData[index]['sourceColorRect'][1][1] ;
                var dh = seedData[index]['sourceColorRect'][1][0] ;
                var dx = (seedData[index]['sourceColorRect'][0][1]+self.topOffset+sw)*-1 ;
                var dy = seedData[index]['sourceColorRect'][0][0]+self.leftOffset ;

                blockContext.rotate( -90*Math.PI/180 );
            }
            else {
                var sw = seedData[index]['frame'][1][0] ;
                var sh = seedData[index]['frame'][1][1] ;
                var dw = seedData[index]['sourceColorRect'][1][0] ;
                var dh = seedData[index]['sourceColorRect'][1][1] ;
                var dx = seedData[index]['sourceColorRect'][0][0]+self.leftOffset ;
                var dy = seedData[index]['sourceColorRect'][0][1]+self.topOffset ;
            }
            blockContext.drawImage(image,sx,sy,sw,sh,dx,dy,dw,dh);
            context.drawImage(newBlock,0,0);
        }
        return newSeed ;
    } ,

    createDiv:function( seedData,scale,elementId ) {
        var self = this ;
        var elementType = "strong";
        var newContainer = document.createElement(elementType);
        var newSeed = document.createElement(elementType);
        $(newSeed).addClass('seedMain');
        for( index in seedData ) {
            var imageSrc = seedData[index]['source'] ;

            var newBlock = document.createElement(elementType);
            var scaleString = ' scale('+scale+') ';
            var style = [] ;
            style["z-index"] = seedData[index]['index'] ;
            style["background"] = 'url('+imageSrc+') no-repeat -'+seedData[index]['frame'][0][0]+'px -'+seedData[index]['frame'][0][1]+'px' ;

            style["left"] = seedData[index]['sourceColorRect'][0][0]+self.leftOffset ;
            style["top"] = seedData[index]['sourceColorRect'][0][1]+self.topOffset ;
            
            if( seedData[index]['rotated'] ) {
                style["-webkit-transform"] = "rotate(-90deg) "; 
                //style["transform"] = "rotate(-90deg)"; 
                style["width"] = seedData[index]['frame'][1][1]; 
                style["height"] = seedData[index]['frame'][1][0];
                style["left"] = Math.ceil(style["left"]+style["height"]/2-style["width"]/2) ;
                style["top"] = Math.ceil(style["top"]+style["width"]/2-style["height"]/2) ;
            } else {
                style["width"] = seedData[index]['frame'][1][0]; 
                style["height"] = seedData[index]['frame'][1][1];
            }
            $(newBlock).addClass('block').css(style);

            $(newSeed).css("-webkit-transform",scaleString).append( newBlock );
        }
        $(newContainer).addClass('seedContainer').append(newSeed);
        return newContainer ;
    }

}


var SeedUnit = function(elemId,SeedData,scale,type){
    var self = this ;
    self.data = SeedData ;
    self.type = type ;
    self.elemId = elemId ;
    self.loadImages = false ;
    self.timer = null ;
    self.container = $('#'+self.elemId) ;
    self.element ;
    self.scale = scale || 0.6 ;
    self.seedCount = 0 ;

    var init = function(){
        
    }

    //if( !type ) type = 'canvas';
    if( type=='canvas' )
        self.element = SeedCreator.createCanvas( SeedData );
    else 
        self.element = SeedCreator.createDiv( SeedData,self.scale );

    self.container.append(self.element);
}


var ShakeObject = function(obj,range,period){
    var self = this ;
    if( Common.empty(range) ) {
        self.range = 10 ;
    }
    else {
        self.range = Math.min(90,range);
    }
    
    if( Common.empty(period) ) {
        period = 150 ;
    }
    self.step = 2 ;
    self.gapTime = period/(self.range/self.step);
    self.position = 1 ;
    self.angle = 0 ;
    self.obj = obj ;
    self.doShake = function() {
        self.angle += self.step*self.position ;
        if( self.angle >= self.range||self.angle <= -self.range ) {
            self.position *= -1 ;
        }
        $(self.obj).css('-webkit-transform','rotate('+self.angle+'deg)');
    } 
    self.stop = function(){
        clearInterval(self.timer);
        $(self.obj).css('-webkit-transform','rotate(0deg)');
    }
    self.timer = setInterval( function(){
        self.doShake()
    },self.gapTime );
}

window.selectLittleGardenList = {
    mailId:'',
    seedId:'',
    showtype:'',
    url:'',
    dialogId:'selectLittleGardenContainer',
    selectId:0,
    callback:'',
    selectType:'garden',
    isNative:0,
    canDo:true,
    show:function(callback,showtype) {
        if(!Common.empty(callback)) {
            this.callback = callback ;
        }
        else {
            this.callback = '' ;
        }
        if(!Common.empty(showtype)) {
            this.showtype = showtype ;
        }
        else {
            this.showtype = 'window' ;
        }
        this.page();
    } ,
    showSeed:function(callback,showtype) {
        this.selectType = 'seed';
        this.show(callback,showtype);
    } ,
    page:function(){
        var self = this ;
        if(self.showtype=='window')
        {
            var url = this.url+'&selectType='+this.selectType;
            ajaxLoader.get(url,function(data){
                CommonDialog.create(selectLittleGardenList.dialogId,data);
                $('#'+selectLittleGardenList.dialogId).css({
                    'bottom':0,
                    'height':562,
                    'top':'auto'
                });
            });
        }
        else {
            Common.goUrl(this.url+'&selectType='+this.selectType+'&showtype=url&callbackUrl='+encodeURIComponent(this.callback));
        }
    } ,
    close:function(){
        CommonDialog.close(this.dialogId);
    } ,
    over:function(selectId){
        var self = this ;
        if( !self.canDo ) {
            //Common.log('do nothing');
            return ;
        }
        //Common.log('do callback');
        self.canDo = false;
        setTimeout(function(){
            self.canDo=true; 
        }, 3000);
        if( self.showtype=='window' ) {
            self.selectId = selectId ;
            if(!Common.empty(self.callback)) {
                Common.run(self.callback);
            }
        }
        else {
            Common.goUrl( this.callback+'&selectId='+selectId );
        }
    }
}

var JumpObj = function(elem, range, startFunc, endFunc) {   
    var curMax = range = range || 6;   
    startFunc = startFunc || function(){};   
    endFunc = endFunc || function(){};   
    var drct = 0;
    var step = 1;
      
    init();   
      
    function init() {
        elem.style.position = 'relative';
        active()
    }   
    function active() {
        elem.onmouseover = function(e) {
            if(!drct)jump()
        }
    }   
    function deactive() {
        elem.onmouseover = null
    }   
      
    function jump() {   
        var t = parseInt(elem.style.top);   
        if (!drct) motionStart();   
        else {   
            var nextTop = t - step * drct;   
            if (nextTop >= -curMax && nextTop <= 0) {
                elem.style.top = nextTop + 'px';   
            }
            else if(nextTop < -curMax) {
                drct = -1;   
            }
            else {   
                var nextMax = curMax / 2;   
                if (nextMax < 1) {
                    motionOver();
                    return;
                }   
                curMax = nextMax;   
                drct = 1;   
            }   
        }   
        setTimeout(function(){
            jump()
        }, 150 / (curMax+3) + drct * 3);   
    }   
    function motionStart() {   
        startFunc.apply(this);   
        elem.style.top='0';   
        drct = 1;   
    }   
    function motionOver() {
        endFunc.apply(this);
        curMax = range;   
        drct = 0;   
        elem.style.top = '0';
    }   
      
    this.jump = jump;   
    this.active = active;   
    this.deactive = deactive;   
}

window.NativeApi = {
    isDelay:false ,
    requestString:'' ,
    isPc:0 ,
    callback:function( key,value ) {
        var strs = [] ;
        var index = 0 ;
        if( typeof(key)=='string' ){
            if( typeof(value)=='undefined' ) {
                strs[index] = key ;
            }
            else {
                strs[index] = key+'='+value;
            }
        }
        else {
            for( i in key ) {
                if( typeof(key[i])=='undefined' ) {
                    strs[index] = i ;
                }
                else {
                    strs[index] = i+'='+key[i];
                }
                index ++ ;
            }
        }
        //CommonDialog.alert('native://'+strs.join('&'));
        this.requestString += ((!this.requestString)?'':'&')+strs.join('&');
        if( !this.isDelay ) {
            this.doRequest();
        }
        return this ;
    } ,
    close:function(){
        Common.log(this.isDelay);
        this.callback('close');
        Common.closingNative = 1 ;
        return this ;
    } ,
    delay:function( bool ) {
        this.isDelay = Common.empty(bool)?false:true ;
        return this ;
    } ,
    doRequest:function(){
        try {
            Common.log(this.requestString);
            window.location.href = (this.isPc?'#':'')+'native://'+this.requestString ;
            this.requestString = '';
            this.isDelay = false ;
        }
        catch (e) {
        //do nothing 
        }
        return this ;
    } ,
    playSound:function(key){
        
    }
}

window.SeedAction = {
    infoUrl:'' ,
    showInfo:function( seedId ){
        //window.location.href = this.infoUrl+'&seedId='+seedId ;
        ajaxLoader.get(this.infoUrl+'&seedId='+seedId,function(data){
            CommonDialog.create('SeedInfoContainer',data);
        });
    },
    closeInfo:function( url ) {
        if( !url ) {
            NativeApi.close();
        }
        else {
            window.location.href = url ;
        }
    }
}

window.SeedGrowing = function( seedGrowData,callback,seedScale,elementId ){
    var self = this ;
    self.data = seedGrowData ;
    self.startTime = SeedTimer.getMyTime();
    self.callback = callback ;
    self.currentElement = $('#SeedCurrentGrowValue') ;
    self.priceElement = $('#SeedCurrentPrice') ;
    self.scale = seedScale ;
    self.timer = null ;
    self.elementId = elementId || 'SeedImageArea';
    self.isGrowRequested = false ;
    self.run = function(){
        var s = Math.max(1,SeedTimer.getMyTime() - self.startTime) ;

        var newValue = self.data['current']+self.data['speed']*s;
        
        if( newValue>=self.data['max'] ){
            self.stop();
        }
        self.currentElement.html( Math.floor(newValue) );
        self.priceElement.html( Math.floor(newValue/self.data['max']*self.data['maxPrice']) );
        
        if( self.data['growPeriod']<2&&newValue/self.data['max']>0.1&&self.data['url'] ) {
            if( self.isGrowRequested ) {
                return ;
            }
            self.isGrowRequested = true ;
            ajaxLoader.get( self.data['url'],function(data){
                self.restart(data);
            } );
        }
    }
    self.stop = function(){
        clearInterval(self.timer);
        Common.run( self.callback );
    }
    self.clear = function(){
        clearInterval(self.timer);
    }
    self.start = function(){
        self.timer = setInterval(self.run,1000);
        if( self.data['parts'] ) {
            $('#'+self.elementId).empty();
            SeedUnit(self.elementId,self.data['parts'],self.scale);
        }
    }
    self.restart = function( newData ) {
        self.data = newData ;
        if( self.timer ) {
            clearInterval(self.timer);
        }
        Common.log(self.data);
        self.start();
    }
    self.start();
}


var SeedAnimation = function( elementId,animationData,scale,repeat ){
    var self = this ;
    self.elementId = elementId ;
    self.animationData = animationData ; 
    self.scale = scale || 0.6 ;
    self.repeat = repeat || 2 ;
    self.playFrame = 0 ;
    self.maxSize = self.animationData.length ;
    self.play = function(){
        if( self.playFrame>self.repeat*self.maxSize ) return false ;
        var index = self.playFrame%self.maxSize ;
        $('#'+self.elementId).empty();
        SeedUnit( self.elementId,self.animationData[index],self.scale );
        self.playFrame ++ ;
        setTimeout( self.play,80 );
    }
    self.play();
}

window.LoadAction = {
    funcs:[] ,
    index:0 ,
    push:function( func ) {
        this.funcs[this.index++] = func ; 
    } ,
    run:function() {
        for( i in this.funcs ) {
            if( !this.funcs[i] ) {
                continue ;
            }
            Common.run( this.funcs[i] );
        }
    }
}

window.selectFriend = {
    url:'' ,
    dialogId:'selectFriendContainer' ,
    selectId:0 ,
    callback:'' ,
    noVisual:1 ,
    show:function( callback,visualKey ) {
        if( !Common.empty( callback ) ) {
            this.callback = callback ;
        }
        else {
            this.callback = '' ;
        }
        if( typeof(visualKey)!='undefined' ) {
            this.noVisual = visualKey ;
        }
        else {
            this.noVisual = 1 ;
        }
        this.page(1);
    } ,
    page:function( page ){
        var self = this ;
        if( isNaN(page)||!page ) $page = 1 ;
        var url = this.url +'&noVisual='+parseInt(self.noVisual)+'&page='+page ;
        ajaxLoader.get(url,function(data){
            CommonDialog.create(selectFriend.dialogId,data);
        });
    } ,
    Search:function( Id ){
        var self = this ;
        var url = this.url + '&noVisual='+parseInt(self.noVisual) + '&searchId='+Id ;
        ajaxLoader.get(url,function(data){
            CommonDialog.create(selectFriend.dialogId,data);
        });
    } ,
    over:function( selectId ){
        var self = this ;
        self.selectId = selectId ;
        CommonDialog.close( self.dialogId );
        if( !Common.empty(self.callback) ) {
            Common.run( self.callback );
        }
    }
}

window.logShow = {
    url:'' ,
    dialogId:'logContainer',
    show:function() {
        var self = this ;
        var url = this.url;
        ajaxLoader.get(url,function(data){
            CommonDialog.create(logShow.dialogId,data);
        })
    }
}

window.noticeShow = {
    url:'' ,
    dialogId:'noticeContainer',
    show:function() {
        var self = this ;
        var url = this.url;
        ajaxLoader.get(url,function(data){
            CommonDialog.create(noticeShow.dialogId,data);
        })
    }
}

window.stampShow = {
    playerMailInfo:'',
    getDaysView:'',
    mailId:'',
    url:'' ,
    dialogId:'stampContainer',
    callback:'',
    show:function(callback) {
        var self = this ;
        var url = this.url;
        if(!Common.empty(callback)) {
            this.callback = callback ;
        }
        else {
            this.callback = '' ;
        }
        ajaxLoader.get(url,function(data){
            CommonDialog.create(stampShow.dialogId,data);
        })
    },
    over:function(){
        var self = this ;
        CommonDialog.close( self.dialogId );
        if( !Common.empty(self.callback) ) {
            Common.run( self.callback );
        }
    }
}

window.sellItemShow = {
    data:'',
    url:'' ,
    dialogId:'itemSellContainer',
    callback:'',
    show:function(callback) {
        var self = this ;
        var url = this.url;
        if(!Common.empty(callback)) {
            this.callback = callback ;
        }
        else {
            this.callback = '' ;
        }
        ajaxLoader.get(url,function(data){
            CommonDialog.create(sellItemShow.dialogId,data);
        })
    },
    over:function(){
        var self = this ;
        CommonDialog.close( self.dialogId );
        if( !Common.empty(self.callback) ) {
            Common.run( self.callback );
        }
    }
}

window.getUseItemShow = {
    data:'',
    dialogId:'useItemContainer',
    callback:'',
    show:function(callback) {
        var self = this ;
        this.callback = callback;
        CommonDialog.create(getUseItemShow.dialogId,getUseItemShow.data);
    },
    over:function(){
        var self = this ;
        CommonDialog.close( self.dialogId );
        if( !Common.empty(self.callback) ) {
            Common.run( self.callback );
        }
    }
}

window.addAllHoverState = function(){
    function addHover(element){
        Common.log($(element).attr('class'));
        $(element).bind("touchstart",function(){
            $(this).addClass("hover");
        });
        $(element).bind("touchend",function(){
            $(this).removeClass("hover");
        });
    }


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

}

