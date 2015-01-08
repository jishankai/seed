<?php header('Content-type:text/xml'); ?>
<?php echo '<?xml version="1.0" encoding="UTF-8"?>' ?>
<dict>
    <key>products</key>
    <array>
        <?php 
            foreach ((array)$products as $productName => $info) {
                echo '<string>' . $productName . '</string>';
            }
        ?>
    </array>
    
    <key>productPurchaseGold</key>
    <array>
        <?php foreach ($products as $productName => $info) {
            echo '<string>' . $info['purchaseGold'] . '</string>';
        }
        ?>
    </array>
    
    <key>productSystemGold</key>
    <array>
        <?php foreach ($products as $productName => $info) {
            echo '<string>' . $info['systemGold'] . '</string>';
        }
        ?>
    </array>
    
    <key>PurchaseCallbackUrl</key>
    <string><?php echo htmlspecialchars($callbackUrl) ?></string>
    
    <key>CurrentTimestamp</key>
    <string><?php echo $tm ?></string>
    
    <key>maxSeedPoint</key>
    <string><?php echo $maxSeedPoint ?></string>
    
    <key>currentPurchaseGold</key>
    <string><?php echo $currentPurchaseGold ?></string>
    
    <key>currentSystemGold</key>
    <string><?php echo $currentSystemGold ?></string>
</dict>
