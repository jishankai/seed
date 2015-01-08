<?php
$catalogArray = array();
$bodyIds = $faceIds = $budIds = $dressIds = array();
for( $i=1001;$i<=1030;$i++ ) {
    $catalogArray['bodyIds'][] = $i ;
}
for( $i=2001;$i<=2025;$i++ ) {
    $catalogArray['faceIds'][] = $i ;
}
for( $i=3001;$i<=3030;$i++ ) {
    $catalogArray['budIds'][] = $i ;
}
for( $i=4001;$i<=4030;$i++ ) {
    $catalogArray['dressIds'][] = $i ;
}


return array(
    'basic' => $catalogArray ,
    'params'=> array(
        'maxCount' => count($catalogArray['bodyIds'])*count($catalogArray['budIds'])*(count($catalogArray['faceIds'])+count($catalogArray['dressIds'])) ,
    ) ,
);

