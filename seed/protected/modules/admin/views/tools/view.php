<div class="ml5 mt10 fl w480">没有种子？   
PlayerId: <input type="text" id="seedPlayerId" style="width:50px;" value="<?php echo $this->playerId; ?>" />
<input type="button" value="去垃圾桶捡一个！" onclick="generateSeed(1)" /> <br />  <br />
<select id="bodyId">
    <?php foreach(SeedData::getBodyData() as $id=>$obj) echo '<option value="'.$id.'">'.$id.':'.$obj->getName().'</option>';?>
</select>
<select id="faceId">
    <?php foreach(SeedData::getFaceData() as $id=>$obj) echo '<option value="'.$id.'">'.$id.':'.$obj->getName().'</option>';?>
</select>
<select id="budId">
    <?php foreach(SeedData::getBudData() as $id=>$obj) echo '<option value="'.$id.'">'.$id.':'.$obj->getName().'</option>';?>
</select>

<select id="dressId">
    <option value="0">-新生无装备-</option>
    <?php foreach(SeedData::getDressData() as $id=>$obj) echo '<option value="'.$id.'">'.$id.':'.$obj->getName().'</option>';?>
</select>

<input type="button" value="自己造一个 " onclick="generateSeed(0)" />

<br /><br />
道具:<select id="itemId">
    <?php foreach(ItemMeta::getAll() as $itemId=>$obj) {
        if( $itemId>10000 ) continue ;
        echo '<option value="'.$itemId.'">'.$itemId.':'.$obj->getName().'</option>';
    }
    ?>  
</select>
数量：<input type="number" id="number" style="width:50px;" value="10" /> 
PlayerId: <input type="text" id="playerId" style="width:50px;" value="<?php echo $this->playerId; ?>" />
<input type="button" value=" 发装备 " onclick="addItem()" />
<br /><br />
Lv.<?php echo $player->level;?> 当前经验：<span style="border:solid 1px #ccc;"><?php echo $currentExp;?> / <?php echo $nextLevelExp;?> </span> 
增加值：<input type="number" id="expValue" style="width:100px;" value="<?php echo max(1,$nextLevelExp-$currentExp-1);?>" />
<input type="button" value=" 加经验 " onclick="addExp()" />
<br /><br />
种子IDs <input type="text" id="seedIds" value="" /> <input type="button" value=" 查看种子API返回值 " onclick="showSeedInfo()" />
逗号分隔的多个ID <br /><br />
花园ID <input type="text" id="gardenId" style="width:50px;" value="" /> <input type="button" value=" 查看种子API返回值 " onclick="showGardenInfo()" />
<br /><br />
</div>
</div>


<script language="javascript">

var generateSeed = function(isRand) {
    ajaxLoader.get('<?php echo $this->createUrl('tools/generate' ); ?>&rand='+isRand+'&bodyId='+$('#bodyId').val()+'&faceId='+$('#faceId').val()+'&budId='+$('#budId').val()+'&dressId='+$('#dressId').val()+'&playerId='+$('#seedPlayerId').val(),alertAndRefresh);
}

var alertAndRefresh = function(data){
    /*
    if( !Common.empty(data) )
        CommonDialog.alert(data,'Common.refreshCurrentPage()'); 
    else 
        Common.refreshCurrentPage() ;
    */
    CommonDialog.alert(data);
}

var alertAndGo = function(data){
}
var addItem = function(){
    ajaxLoader.get('<?php echo $this->createUrl('tools/getItem' ); ?>&playerId='+$('#playerId').val()+'&itemId='+$('#itemId').val()+'&number='+$('#number').val(),alertAndRefresh);
}

var addExp = function(){
    ajaxLoader.get('<?php echo $this->createUrl('tools/addExp' ); ?>&value='+$('#expValue').val(),Common.refreshCurrentPage);
}

var showGardenInfo = function(){
    var gardenId = parseInt( $('#gardenId').val() );
    if( !gardenId ) {
        $('#gardenId').focus();
        return false ;
    }
    window.open('<?php echo $this->createUrl('/seed/getDisplayInfo');?>&gardenId='+gardenId+'&isApi=1&showData=1');
}

var showSeedInfo = function(){
    var seedIds = $('#seedIds').val();
    if( !seedIds ) {
        $('#seedIds').focus();
        return false ;
    }
    window.open('<?php echo $this->createUrl('/seed/getDisplayInfo');?>&seedIds='+seedIds+'&isApi=1&showData=1');
}

</script>
