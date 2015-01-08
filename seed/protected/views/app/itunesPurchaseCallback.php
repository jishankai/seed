<?php header('Content-type:text/xml'); ?>
<?php echo '<?xml version="1.0" encoding="UTF-8"?>' ?>
<dict>
    <key>flag</key>
    <string><?php echo $flag?></string>
    
    <key>title</key>
    <?php if(isset($title)){ ?>
        <string><?php echo $title?></string>
    <?php }else{ ?>
        <?php if($flag <= 100){ ?>
        <string>成功</string>
        <?php }elseif($flag <= 200){ ?>
        <string>失败</string>
        <?php }else{ ?>
        <string>エラー</string>
        <?php } ?>
    <?php } ?>
    
    <key>message</key>
    <?php if(isset($message)){ ?>
        <string><?php echo $message?></string>
    <?php }else{ ?>
        <?php if($flag <= 100){ ?>
        <string>購入成功。</string>
        <?php }elseif($flag <= 200){ ?>
        <string>購入失敗。購入内容をもう一度確認してから試してみよう。</string>
        <?php }else{ ?>
        <string>ネットワーク接続エラー。時間を置いてからもう一度試してみよう。</string>
        <?php } ?>
    <?php } ?>
</dict>