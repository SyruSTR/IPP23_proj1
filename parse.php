<?php
ini_set('display_errors','stderr');
include 'codeInfo.php';


$xw = xmlwriter_open_memory();
xmlwriter_set_indent($xw,1);

xmlwriter_start_document($xw,'1.0','UTF-8');

function itsVar($string){
    
    if(preg_match("/(LF|GF|TF)@[a-zA-Z_\-&$%*!?][a-zA-Z_\-$&%*!?0-9]*/",$string)){
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

function printXmlElement($typeName,$elementNumber,$text){
    global $xw;
    xmlwriter_start_element($xw,'arg'.($elementNumber+1));

    xmlwriter_start_attribute($xw,"type");
    xmlwriter_text($xw,$typeName);
    xmlwriter_end_attribute($xw);

    xmlwriter_text($xw,$text);
    
    xmlwriter_end_element($xw);
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
    $splitLine = preg_split("/[\s]+/", trim($line, "\n"));

    if(array_key_exists($splitLine[0],$language)){
        xmlwriter_start_element($xw,'instruction');

        xmlwriter_start_attribute($xw,'order');
        xmlwriter_text($xw,$order++);
        xmlwriter_end_attribute($xw);

        xmlwriter_start_attribute($xw,'opcode');
        xmlwriter_text($xw,strtoupper($splitLine[0]));
        xmlwriter_end_attribute($xw);

        if(isset($language[$splitLine[0]])){
            $atrCount = count($language[$splitLine[0]]);
            if($atrCount != count($splitLine)-1){
                exit(23);
            }
            
            

            for($i = 0; $i < $atrCount; $i++){
                $manyOptions = false;
                if(is_array($language[$splitLine[0]][$i])){
                    $number_of_options = count($language[$splitLine[0]][$i]);
                    $manyOptions = true;
                }
                else{
                    $number_of_options = 1;
                }

                $j = 0;
                $count_of_errors = 0;
                while($j < $number_of_options){

                    if($manyOptions){
                        $checkedParam = $language[$splitLine[0]][$i][$j];
                    }
                    else{
                        $checkedParam = $language[$splitLine[0]][$i];
                    }
                    

                    //echo ($language[$splitLine[0]][$i]->name)."\n";
                    
                    switch($checkedParam){
                        case ParamTypes::variable:
                            //echo "its variable\n";
                            
                            if(itsVar($splitLine[$i+1])){
                                printXmlElement(
                                    "var",
                                    $i,
                                    $splitLine[$i+1]
                                );
                            }
                            else{
                                $count_of_errors++;
                            }
                            break;
                        case ParamTypes::symbol:
                            if(preg_match("/^string@*/",$splitLine[$i+1])){
                                printXmlElement(
                                    "string",
                                    $i,
                                    substr($splitLine[$i+1],7)
                                );
                            }
                            elseif(preg_match("/^int@*/",$splitLine[$i+1])){
                                if(preg_match("/[a-zA-Z]+/",($val = substr($splitLine[$i+1],4)))){
                                    $count_of_errors++;
                                }
                                elseif(intval($val,10)){
                                    printXmlElement(
                                        "int",
                                        $i,
                                        $val
                                    );
                                }
                                else
                                    $count_of_errors++;
                            }
                            elseif(preg_match("/^bool@*/",$splitLine[$i+1])){
                                $val = substr($splitLine[$i+1],5);
                                if(strcmp("true",$val) == 0 || strcmp("false",$val) == 0){
                                    printXmlElement(
                                        "bool",
                                        $i,
                                        $val
                                    );
                                }
                                else
                                    $count_of_errors++;
                                
                            }
                            elseif(preg_match("/^nil@nil/",$splitLine[$i+1])){
                                //echo "its..... nothing?";
                                $val = "nil";
                                printXmlElement(
                                    "nil",
                                    $i,
                                    $val
                                );
                            }
                            else{
                                $count_of_errors++;
                            }
                            break;
                        case ParamTypes::label:
                            //echo "its label\n";
                            if(preg_match("/^[a-zA-Z]+$/",$splitLine[$i+1])){
                                $val = $splitLine[$i+1];
                                printXmlElement(
                                    "label",
                                    $i,
                                    $val
                                );
                            }
                            else
                                $count_of_errors++;
                            break;
                        case ParamTypes::type:
                            //echo "its type\n";

                            if(preg_match("/^(int|string|bool)$/",$splitLine[$i+1])){
                                $val = $splitLine[$i+1];
                                printXmlElement(
                                    "type",
                                    $i,
                                    $val
                                );
                            }
                            else
                                $count_of_errors++;
                            break;
                        default:
                            $count_of_errors++;
                    }
                
                    $j++;
                }
                if($count_of_errors == $j){
                    exit(23);
                }
                
                    
            }
        }
        elseif(count($splitLine)-1 > 0)
            exit(23);
        //end element 'instruction'
        xmlwriter_end_element($xw);
    }
    else{
        //echo "chyba!\n";
        $error = true;
        break;
    }
}

xmlwriter_end_element($xw);

xmlwriter_end_document($xw);

if(!$error){
    echo xmlwriter_output_memory($xw);
    exit (0);
}