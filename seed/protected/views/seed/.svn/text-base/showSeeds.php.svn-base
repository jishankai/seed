<style>
.seedMain {
    width:75px; height:100px; border:solid 0px; position:relative; float:left ; margin:6px; 
}
.seedMain .block{
    position:absolute ; 
}
#seedArea canvas{
    border:solid 0px; float:left; margin:5px;
}
</style>
<div id=imageShow></div>
<!--div class=seedMain style="">
    <div id="t1" class="block" style="left:10px; top:10px; border:solid 1px red; opacity:0.5; width:80px; height:20px;"></div>
    <div id="t2" class="block" style="left:10px; top:10px; border:solid 1px black; opacity:0.5; width:80px; height:20px; -webkit-transform:rotate(-90deg);"></div>
</div>


<canvas id=myCanvas></canvas-->
<div>
种子数量：
<input type="text" class="w30" value="60" id="maxNum" maxlength=3> 
<input type="button" class="genButton" index=1 value=" Div组合方式 " />  
<input type="button" class="genButton" index=2 value=" Canvas生成方式 " />  
<input type="button" class="genButton" index=3 value=" Div成长顺序 " />  
<input type="button" class="genButton" index=4 value=" Canvas成长顺序 " />  
</div>
<div id=seedArea>

</div>

<script language="javascript">
var SeedData = [{"frame":[[23,34],[19,13]],"offset":[0,-45],"rotated":false,"sourceColorRect":[[53,101],[19,13]],"sourceSize":[125,125],"source":"\/images\/seed\/feet-nut.png","index":3},{"frame":[[172,43],[41,39]],"offset":[-1,-25],"rotated":false,"sourceColorRect":[[41,68],[41,39]],"sourceSize":[125,125],"source":"\/images\/seed\/head-nut.png","index":1},{"frame":[[2,70],[33,15]],"offset":[-1,-27],"rotated":true,"sourceColorRect":[[45,82],[33,15]],"sourceSize":[125,125],"source":"\/images\/seed\/face-nut.png","index":2},{"frame":[[2,78],[41,35]],"offset":[1,7],"rotated":false,"sourceColorRect":[[43,38],[41,35]],"sourceSize":[125,125],"source":"\/images\/seed\/stem-nut.png","index":4},{"frame":[[215,71],[69,23]],"offset":[-1,-30],"rotated":true,"sourceColorRect":[[27,81],[69,23]],"sourceSize":[125,125],"source":"\/images\/seed\/wings-nut.png","index":5}];
var defineStep = 10 ;
var useCanvas = false ;
var idCount = 0 ;
var loadedImages = false ;
$(document).ready(function(){
    loadImages();
    //testFunc(1) ;
    $('.genButton').click(function(){$('.genButton').attr('disabled',false);$(this).attr('disabled',true); testFunc( $(this).attr('index') )});;
});

var loadImages = function() {
    var total = SeedData.length;
    var imageCounter = 0;
    var onLoad = function(err, msg) {
        if (err) {
            //
        }
        imageCounter++;
        if (imageCounter == total) {
            loadedImages = true;
        }
    }

    for( index in SeedData ) {
        var img = new Image();
        img.onload = function() { onLoad(false); };
        img.onerror = function() { onLoad(true, e);}; 
        img.src = SeedData[index]['source'] ; ;
    }
}


var testFunc = function( type ){
    if( !loadedImages ) {
        setTimeout('testFunc('+type+')',50);
    }
    else {
        $('#seedArea').empty();
        var max = $('#maxNum').val();
        for( var i=0;i<max;i++ ) {
            if( type>2 ) defineStep = i%5+1 ;
            else defineStep = 10 ;
            if( type%2==1 ) createSeed( SeedData );
            else createSeedImage( SeedData );
            idCount ++ ;
        }
    }
}

var createSeedImage = function(seedData) {
    var newSeed = document.createElement("canvas");
    newSeed.width = 77 ; 
    newSeed.height = 102 ; 
    $('#seedArea').append( newSeed );
    new JumpObj(newSeed,10);
    var context = newSeed.getContext("2d");
	for( index in seedData ) {
        if( seedData[index]['index']>defineStep ) continue ;
        
        var image = new Image();
        image.src = seedData[index]['source'] ;

        var newBlock = document.createElement("canvas");
        var blockContext = newBlock.getContext("2d");

        var sx = seedData[index]['frame'][0][0] ;
        var sy = seedData[index]['frame'][0][1] ;
        var dx = seedData[index]['sourceColorRect'][0][0]-25 ;
        var dy = seedData[index]['sourceColorRect'][0][1]-30 ;
        var sw = seedData[index]['frame'][1][0] ;
        var sh = seedData[index]['frame'][1][1] ;
        var dw = seedData[index]['sourceColorRect'][1][0] ;
        var dh = seedData[index]['sourceColorRect'][1][1] ;

        if( seedData[index]['rotated'] ){
            blockContext.rotate( -90*Math.PI/180 );
            var sw = seedData[index]['frame'][1][1] ;
            var sh = seedData[index]['frame'][1][0] ;
            var dw = seedData[index]['sourceColorRect'][1][1] ;
            var dh = seedData[index]['sourceColorRect'][1][0] ;
            var dx1 = dx ;
            var dx = (dy+sw)*-1 ;
            var dy = dx1 ; ;
        }
        else {
            
        }
        blockContext.drawImage(image,sx,sy,sw,sh,dx,dy,dw,dh);
        context.drawImage(newBlock,0,0);
    }
}



var createSeed = function(seedData) {
    var newSeed = document.createElement("div");
    $(newSeed).addClass('seedMain');
    $('#seedArea').append( newSeed );
    //$(newSeed).click(function(){JumpObj(this,10);});
    new JumpObj(newSeed,10);
	for( index in seedData ) {
        if( seedData[index]['index']>defineStep ) continue ;
        var imageSrc = seedData[index]['source'] ;
        /*  
        var img = new Image();
        img.src = imageSrc ;
        $('#imageShow').append(img);
        */

        var newBlock = document.createElement("div");
        var style = [] ;
        style["z-index"] = seedData[index]['index'] ;
        style["background"] = 'url('+imageSrc+') no-repeat -'+seedData[index]['frame'][0][0]+'px -'+seedData[index]['frame'][0][1]+'px' ;

        style["left"] = seedData[index]['sourceColorRect'][0][0]-25 ;
        style["top"] = seedData[index]['sourceColorRect'][0][1]-30 ;

        if( seedData[index]['rotated'] ) {
            style["-webkit-transform"] = "rotate(-90deg)"; 
            style["width"] = seedData[index]['frame'][1][1]; 
            style["height"] = seedData[index]['frame'][1][0];
            style["left"] = Math.ceil(style["left"]+style["height"]/2-style["width"]/2) ;
            style["top"] = Math.ceil(style["top"]+style["width"]/2-style["height"]/2) ;
        } else {
            style["width"] = seedData[index]['frame'][1][0]; 
            style["height"] = seedData[index]['frame'][1][1];
        }
        $(newBlock).addClass('block').css(style);

        $(newSeed).append( newBlock );
        //break ;
    }
}

var JumpObj = function(elem, range, startFunc, endFunc) {   
         var curMax = range = range || 6;   
         startFunc = startFunc || function(){};   
         endFunc = endFunc || function(){};   
         var drct = 0;   
         var step = 1;   
   
         init();   
   
         function init() { elem.style.position = 'relative';active() }   
         function active() { elem.onmouseover = function(e) {if(!drct)jump()} }   
         function deactive() { elem.onmouseover = null }   
   
         function jump() {   
              var t = parseInt(elem.style.top);   
             if (!drct) motionStart();   
             else {   
                 var nextTop = t - step * drct;   
                 if (nextTop >= -curMax && nextTop <= 0) elem.style.top = nextTop + 'px';   
                 else if(nextTop < -curMax) drct = -1;   
                else {   
                     var nextMax = curMax / 2;   
                     if (nextMax < 1) {motionOver();return;}   
                     curMax = nextMax;   
                     drct = 1;   
                 }   
             }   
             setTimeout(function(){jump()}, 150 / (curMax+3) + drct * 3);   
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

</script>


