<?php
//$blocks = array(3=>'head',5=>'face',1=>'feet',4=>'stem',2=>'wings');

/*
$blocks = array('feet'=>3,'head'=>1,'face'=>2,'stem'=>4,'wings'=>5);
$keys = array('nut');

$dataArray = array(); 
foreach( $blocks as $block=>$index ) {
    foreach( $keys as $key ) {
        $xmlFile = $block.'-'.$key.'.xml';
        $imgFile = '/images/seed/'.$block.'-'.$key.'.png';
        $xml = simplexml_load_file( $xmlFile ); 
        if( empty($xml->dict->dict->dict[0])  )  {
            continue ;
        }
        $data = $xml->dict->dict->dict[0] ;
        $result = array();
        
        $result['frame']        = getFormatedData( $data->string[0] );
        $result['offset']       = getFormatedData( $data->string[1] );
        $result['rotated']      = isset( $data->true );
        $result['sourceColorRect']   = getFormatedData( $data->string[2] );
        $result['sourceSize']   = getFormatedData( $data->string[3] ) ;
        $result['source']       = $imgFile ;
        $result['index']        = $index ;
        
        $dataArray[] = $result ;
    }
}

var_dump( $dataArray );
echo json_encode( $dataArray );

function getFormatedData($string) {
    $string = str_replace( array('{','}'),array('[',']'),$string );
    return json_decode( $string );
}

*/
/*
$data = file_get_contents('face-nut.xml');
$xml = xml_parser_create(); 
$obj = xml_parse( $xml,$data );

var_dump($xml);
var_dump($obj);
*/

//$xml = simplexml_load_file('face-nut.xml');
 
//var_dump( count(  ) );

/*
foreach( $xml->dict->dict->dict as $x ) {
    var_dump($x);

} 
*/

$d = dir(_);

