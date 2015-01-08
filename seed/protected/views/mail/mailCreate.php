<center>
    <form method="POST" action="<?php echo $this->createUrl('mail/mailCreate'); ?>">
        用户的ID:<input type="textarea" name ="playerIds" cols=40 rows=20 style="overflow:auto"/><br>
        领取时限:<input type="text" name ="getDays"/><br>
        存放时间:<input type="text" name ="keepDays"/><br>
        消息类型:
        <select name="informType" >
            <option value="1">系统礼物箱</option>
            <option value="2">礼品</option>
            <option value="3">系统公告</option>
        </select><br>
        通知的主题:<input type="text" name ="title" cols=80 rows=20 style="overflow:auto" maxLength= "26"/><br>
        通知的内容:<textarea name ="notice" cols=18 rows=8 style="overflow:auto" maxLength= "100"/></textarea><br>
        发送物品ID:<input type="text" name ="goodsId"/><br>
        物品的数量:<input type="text" name ="goodsNum"/><br>
        发送的种子:<input type="text" name ="seedId"/><br>
        发送游戏币:<input type="text" name ="sentGold"/><br>
        发送的金钱:<input type="text" name ="sentMoney"/><br>
        <input type="submit" value="发送">
    </form>
</center>