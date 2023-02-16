<?php
ini_set('display_errors','stderr');


$header = false;

$xw = xmlwriter_open_memory();
xmlwriter_set_indent($xw,1);

xmlwriter_start_document($xw,'1.0','UTF-8');

// while($line = fgets(STDIN)){
//     echo($line);
// }
xmlwriter_start_element($xw, 'tag1');

// Атрибут 'att1' для элемента 'tag1'
xmlwriter_start_attribute($xw, 'att1');
xmlwriter_text($xw, 'valueofatt1');
xmlwriter_end_attribute($xw);

xmlwriter_write_comment($xw, 'this is a comment.');

// Создаём дочерний элемент
xmlwriter_start_element($xw, 'tag11');
xmlwriter_text($xw, 'This is a sample text, ä');
xmlwriter_end_element($xw); // tag11

xmlwriter_end_element($xw); // tag1



xmlwriter_start_pi($xw, 'php');
xmlwriter_text($xw, '$foo=2;echo $foo;');
xmlwriter_end_pi($xw);

xmlwriter_end_document($xw);

echo xmlwriter_output_memory($xw);