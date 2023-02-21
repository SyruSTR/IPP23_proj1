<?php
ini_set('display_errors','stderr');
include 'codeInfo.php';


$xw = xmlwriter_open_memory();
xmlwriter_set_indent($xw,1);

xmlwriter_start_document($xw,'1.0','UTF-8');

function itsVar($string){
    
    if(preg_match("/(LF|GF|TF)@[a-zA-Z_\-$%*!?][a-zA-Z_\-$%*!?0-9]*/",$string)){
        //return mb_substr($string,3);
        return true;
    }
    return false;
}

function itsLabel($string){
    if(preg_match("/[a-zA-Z_\-$%*!?][a-zA-Z_\-$%*!?0-9]*/",$string)){
        return true;
    }
    return false;
}

function printXmlElementType($typeName){
    global $xw;
    xmlwriter_start_attribute($xw,"type");
    xmlwriter_text($xw,$typeName);
    xmlwriter_end_attribute($xw);
}

$line = fgets(STDIN);
$error = false;

if ($line = ".IPPcode23")
{
    xmlwriter_start_element($xw,"program");
    xmlwriter_start_attribute($xw,"language");
    xmlwriter_text($xw,"IPPcode23");
    xmlwriter_end_attribute($xw);
}

$order = 1;

while($line = fgets(STDIN)){
    $splitLine = explode(" ", trim($line, "\n"));

    if(array_key_exists($splitLine[0],$language)){
        xmlwriter_start_element($xw,'instruction');

        xmlwriter_start_attribute($xw,'order');
        xmlwriter_text($xw,$order++);
        xmlwriter_end_attribute($xw);

        xmlwriter_start_attribute($xw,'opcode');
        xmlwriter_text($xw,strtoupper($splitLine[0]));
        xmlwriter_end_attribute($xw);

        if(($atrCount = count($language[$splitLine[0]])) > 0){
            for($i = 0; $i < $atrCount; $i++){
                //echo ($language[$splitLine[0]][$i]->name)."\n";
                xmlwriter_start_element($xw,'arg'.($i+1));
                switch($language[$splitLine[0]][$i]){
                    case ParamTypes::variable:
                        //echo "its variable\n";
                        printXmlElementType("var");
                        //its hodnota
                        if(itsVar($splitLine[$i+1])){
                            xmlwriter_text($xw,$splitLine[$i+1]);
                        }
                        else{
                            $error = true;
                        }
                        break;
                    case ParamTypes::symbol:
                        if(preg_match("/^string@*/",$splitLine[$i+1])){
                            //echo "its STRIIIIIIIIIIING!";
                        }
                        elseif(preg_match("/^int@*/",$splitLine[$i+1])){
                            if(preg_match("/[a-zA-Z]+/",($val = substr($splitLine[$i+1],4)))){
                                $error = true;
                            }
                            elseif(intval($val,10)){
                                printXmlElementType("int");
                                //echo $val."\n";
                                xmlwriter_text($xw,$val);
                            }
                            else
                                $error = true;
                        }
                        elseif(preg_match("/^bool@*/",$splitLine[$i+1])){
                            printXmlElementType("bool");
                            $val = substr($splitLine[$i+1],5);
                            xmlwriter_text($xw,$val);
                            //echo "TRUE FALSE TRUE FALSE";
                        }
                        elseif(preg_match("/^nil@*/",$splitLine[$i+1])){
                            //echo "its..... nothing?";
                        }
                        else{

                        }
                        break;
                    case ParamTypes::label:
                        //echo "its label\n";
                        break;
                    case ParamTypes::type:
                        //echo "its type\n";
                        break;
                    default:
                        $error = true;
                }

                //end element 'argX'
                xmlwriter_end_element($xw);
                    
            }
        }
        //end element 'instruction'
        xmlwriter_end_element($xw);
    }
    else{
        //echo "chyba!\n";
        $error = true;
        break;
    }
}

// $xw = xmlwriter_open_memory();
// xmlwriter_set_indent($xw,1);

// xmlwriter_start_document($xw,'1.0','UTF-8');

// // while($line = fgets(STDIN)){
// //     echo($line);
// // }
// xmlwriter_start_element($xw, 'tag1');

// // Атрибут 'att1' для элемента 'tag1'
// xmlwriter_start_attribute($xw, 'att1');
// xmlwriter_text($xw, 'valueofatt1');
// xmlwriter_end_attribute($xw);

// xmlwriter_write_comment($xw, 'this is a comment.');

// // Создаём дочерний элемент
// xmlwriter_start_element($xw, 'tag11');
// xmlwriter_text($xw, 'This is a sample text, ä');
// xmlwriter_end_element($xw); // tag11

// xmlwriter_end_element($xw); // tag1



// xmlwriter_start_pi($xw, 'php');
// xmlwriter_text($xw, '$foo=2;echo $foo;');
// xmlwriter_end_pi($xw);

//element program
xmlwriter_end_element($xw);

xmlwriter_end_document($xw);

if(!$error){
    echo xmlwriter_output_memory($xw);
    exit (0);
}
    
// else
//     echo "its shit!\n";