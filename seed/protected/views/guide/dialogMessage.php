<?php switch($showType) {
    case 1:
?>
<div style="width:421px; height:337px; overflow:hidden;">
    <div class="b_text06" style="float:right;">
    <i class="text06pic"></i>
        <div class="long">
            <span id="guideDialogMessage">
            </span>
            <em class="b_text05goon"><span class="b_text05gnp"></span><span class="b_text05gnp1"></span></em>
        </div>
    </div>
</div>

<?php
        break;
    case 2:
?>
<div style="width:421px; height:337px; overflow:hidden;">
    <div class="b_text06 b_text06_1" style="float:left">
        <i class="text06pic"></i>
        <div class="long">
            <span id="guideDialogMessage">
            </span>
            <em class="b_text05goon"><span class="b_text05gnp"></span><span class="b_text05gnp1"></span></em>
        </div>
    </div>
</div>

<?php
        break;
    case 3:
?>


<div style="width:216px; height:533px; overflow:hidden;">
    <div class="b_text06" style="float:right;">
        <i class="text06pic" style="left:50px;"></i>
        <div class="height">
            <span id="guideDialogMessage">
            </span>
            <em class="b_text05goon"><span class="b_text05gnp"></span><span class="b_text05gnp1"></span></em>
        </div>
    </div>
</div>

<?php
        break;
    case 4:
    default:
?>
<div style="width:216px; height:533px; overflow:hidden;">
    <div class="b_text06 b_text06_1" style="float:left;">
        <i class="text06pic" style="left:50px;"></i>
        <div class="height">
            <span id="guideDialogMessage">
            </span>
            <em class="b_text05goon"><span class="b_text05gnp"></span><span class="b_text05gnp1"></span></em>
        </div>
    </div>
</div>
<?php } ?>









<?php
$clickCallback = new StdClass();
$clickCallback->close = 'close';
//if( !in_array(floor($currentAccessLevel/10),array(10) ) ) {
    if( $currentAccessLevel=='60' ){
        $clickCallback->accessLevel = 61;
    }
    elseif( $currentAccessLevel=='60_1' ){
        $clickCallback->accessLevel = 62;
    }
    elseif( $currentAccessLevel==62 ){
        $clickCallback->accessLevel = $currentAccessLevel+2;
    }
    else {
        $clickCallback->accessLevel = $currentAccessLevel+1;
    }
//}
?>
<script>
var dataString = '<?php echo addslashes($dialogMessage);?>';
var tempArray = dataString.split('|');
var messageCount = 0; 
var nextDialogMessage = function(){
    if( messageCount>=tempArray.length ){
        $('#guideDialogMessage').parent().unbind('click').children('.b_text05goon').remove();;
        NativeApi.callback(<?php echo json_encode($clickCallback); ?>);
        return ;
    }
    var element = document.createElement('span');
    $(element).html(tempArray[messageCount]).hide().fadeIn();
    $('#guideDialogMessage').empty().append(element).parent().unbind('click').click(function(){
        nextDialogMessage();
    });
    messageCount ++ ;
    if( messageCount<tempArray.length ) {
        //$('#guideDialogMessage').append('<div style="width:100%;clear:both;"><i class="new_hand" style="margin-left:200px;"></i></div>');
    }
    else {
        
    }
}

$(document).ready(function(){
    nextDialogMessage();
});

</script>
