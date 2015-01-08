<?php //if(SeedConfig::isDebug()) {?>
<div style="text-align:center">
<form method="POST">
    <label>DeviceId: <input type="text" name="deviceId"></label>
    <input type="submit" value="SUBMIT">
</form>
</div>
<?php //} ?>
<script>
LoadAction.push(function(){
    NativeApi.callback({'relogin':1,'close':'close'});
});
</script>
