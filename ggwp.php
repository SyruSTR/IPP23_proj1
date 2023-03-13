<?php


//10
$strT = "string@řetězec\0322s\032lomítkem\032\092\032a\010novým\035řádkem";
$strF = "string@řetězec\03s\032lomítkem\032\092\032a\010novým\035řádkem";


$testStr = "\010";
var_dump(quotemeta($testStr));


for ( $pos=0; $pos < strlen($testStr); $pos ++ ) {
 $byte = substr($testStr, $pos);
 echo 'Байт ' . $pos . ' строки $str равен ' . ord($byte) . PHP_EOL;
}

// var_dump(strpos($testStr,'\\'));
// echo substr($testStr,strpos($testStr,"\\"),strpos($testStr,"\\")+3);


// while($str = substr($strT,strpos($strT,"\\"))){
    
// }
// print_r($matches);
// $comment = strpos($str,"#");

// var_dump($comment);

// echo substr($str,7,-(strlen($str)-$comment));


// if(!($comment = strpos($str,"%"))){
//     $cutLenght = null;
// }
// else{
//     $cutLenght = -(strlen($str)-$comment);
// }

// $val = substr($str,7,$cutLenght);
// var_dump(-(strlen($str)-$comment));
// var_dump($val);
// $val = substr($str,7,null);
// var_dump($val);
// $xw = xmlwriter_open_memory();
// xmlwriter_set_indent($xw,1);
// xmlwriter_start_document($xw,'1.0','UTF-8');

// xmlwriter_start_element($xw,"program");
// xmlwriter_start_attribute($xw,"language");
// xmlwriter_text($xw,"IPPcode23");
// xmlwriter_end_attribute($xw);

// xmlwriter_end_element($xw);

// xmlwriter_end_document($xw);

// echo xmlwriter_output_memory($xw);

?>