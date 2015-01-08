<?php
$catalog = include(dirname(__FILE__)."/Catalog.cfg.php");
extract( $catalog['basic'] );

$bodySize = count($bodyIds);
$faceSize = count($faceIds);
$budSize = count($budIds);
$dressSize = count($dressIds);

$catalogArray = array();
foreach( $bodyIds as $bodyId ) {
    $catalogArray[(100000+$bodyId)] = array(
        'bodyId'    => $bodyId ,
        'faceId'    => 1 ,
        'budId'     => 1 ,
        'type'      => 1 ,
        'size'      => $faceSize*$budSize ,
    );
    $catalogArray[(200000+$bodyId)] = array(
        'bodyId'    => $bodyId ,
        'faceId'    => 1 ,
        'budId'     => 1 ,
        'type'      => 2 ,
        'size'      => $faceSize*$dressSize ,
    );
}

return $catalogArray;

