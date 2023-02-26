<?php

$arr = array(
    1=>array(1,2,3),
    2=>array(array(1,2),2,3),
);


//echo count($arr[2][1]);

var_dump(is_array($arr[2][0]))

?>