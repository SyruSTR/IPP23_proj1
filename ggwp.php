<?php

$xw = xmlwriter_open_memory();
xmlwriter_set_indent($xw,1);
xmlwriter_start_document($xw,'1.0','UTF-8');

xmlwriter_start_element($xw,"program");
xmlwriter_start_attribute($xw,"language");
xmlwriter_text($xw,"IPPcode23");
xmlwriter_end_attribute($xw);

xmlwriter_end_element($xw);

xmlwriter_end_document($xw);

echo xmlwriter_output_memory($xw);

?>