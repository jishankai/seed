序列化:
<form method="POST" action="<?php echo $this->createUrl('items/Serialize'); ?>">
    输入需要序列化的数组:<input type="textarea" name ="array" cols=40 rows=20 style="overflow:auto"/><br><br>
    <input type="submit" value="发送"><br><br>
</form>
反序列化:
<form method="POST" action="<?php echo $this->createUrl('items/Unserialize'); ?>">
    输入需要反序列化的数据:<input type="textarea" name ="array" cols=40 rows=20 style="overflow:auto"/><br><br>
    <input type="submit" value="发送"><br><br>
    返回结果:<?php if (isset($newArray)) print_r($newArray); ?><br><br>
</form>
<?php echo '当前的UnixTime='. time();?>
<form method="POST" action="<?php echo $this->createUrl('items/GetUnixTime'); ?>">
    输入需要转换的UnixTime:<input type="textarea" name ="unixTime" cols=40 rows=20 style="overflow:auto"/><br>不输入时返回当前时间<br>
    <input type="submit" value="发送"><br><br>
</form>
<form method="POST" action="<?php echo $this->createUrl('items/GetTime'); ?>">
    输入需要+UnixTime差值:<input type="textarea" name ="time" cols=40 rows=20 style="overflow:auto"/><br>不输入时返回当前时间<br>
    <input type="submit" value="发送"><br><br>
    返回结果:<?php if (isset($dateTime2)) print_r($dateTime2); ?><br><br>
</form>
