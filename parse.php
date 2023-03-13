<?php
ini_set('display_errors','stderr');
include 'codeInfo.php';


/**
 * Login via email and password
 *
 * @param $numberCode exit code
 * @param $debugInfo comment for debuging
 * @param $line which line its happen
 */
function exitWithCode($numberCode,$debugInfo,$line){
    //debug message
    //echo $line."\n";
    //echo $debugInfo;
    exit($numberCode);
}
/**
 * @param mixed $checkedString
 * 
 * @return bool
 */
function itsVar($checkedString){
    
    if(preg_match("/(LF|GF|TF)@[a-zA-Z_\-&$%*!?][a-zA-Z_\-$&%*!?0-9]*/",$checkedString)){
        return true;
    }
    return false;
}

/**
 * @param mixed $checkedString
 * 
 * @return bool
 */
function itsLabel($checkedString){
    if(preg_match("/^[\$%\*\!_\-\?a-zA-Z]+$/",$checkedString)){
        return true;
    }
    return false;
}

/**
 * @param mixed $typeName type of variable
 * @param mixed $elementNumber count of element
 * @param mixed $text variable value
 * 
 */
function printXmlElement($typeName,$elementNumber,$text){
    global $xw;
    xmlwriter_start_element($xw,'arg'.($elementNumber+1));

    xmlwriter_start_attribute($xw,"type");
    xmlwriter_text($xw,$typeName);
    xmlwriter_end_attribute($xw);

    xmlwriter_text($xw,$text);
    
    xmlwriter_end_element($xw);
}

//work with argv
if($argc > 1){
    if($argv[2] == "--help"){
        if($argc > 2)
            exit(10);
        echo "tetno parser prijima IPPcode23 na standartni vstup.\n
            Ho vystup je XML file\n";
        echo "Usage: parser.php [options] < inputFile\n";
        exit(0);
    }
}
//start document
$xw = xmlwriter_open_memory();
xmlwriter_set_indent($xw,1);
xmlwriter_start_document($xw,'1.0','UTF-8');

//number of opcode in queue
$order = 1;
$headerIFJ = false;



while($line = fgets(STDIN)){
    //first we are check on newLine or comment
    if(preg_match("/^(\n|#.*)$/",$line)){
        continue;
    }
    //then check header
    elseif (!$headerIFJ && preg_match("/^[ ]*.IPPcode23[ ]*(#.*)?\r?$/",$line) )
    {
        xmlwriter_start_element($xw,"program");
        xmlwriter_start_attribute($xw,"language");
        xmlwriter_text($xw,"IPPcode23");
        xmlwriter_end_attribute($xw);
        $headerIFJ = true;
        continue;
    }
    //continie when have a header
    elseif($headerIFJ)
    {
        //split the string into an array of individual words
        $splitLine = preg_split("/[\s]+/", trim($line, "\n"));
        
        //check opcode in language
        if(array_key_exists(strtoupper($splitLine[0]),$language)){
            //opcode non-register dependent
            $splitLine[0] = strtoupper($splitLine[0]);

            //each operator has a structure
            //<instruction order="NumberOfOrder" opcode="nameOfOpcode">
            xmlwriter_start_element($xw,'instruction');

            xmlwriter_start_attribute($xw,'order');
            xmlwriter_text($xw,$order++);
            xmlwriter_end_attribute($xw);

            xmlwriter_start_attribute($xw,'opcode');
            xmlwriter_text($xw,$splitLine[0]);
            xmlwriter_end_attribute($xw);

            //check haves opcode arguments or no
            if(isset($language[$splitLine[0]])){

                //check code on comment in line
                $atrCount = count($language[$splitLine[0]]);
                $extraAtr = 1;
                for ($i=0; $i < count($splitLine); $i++) { 
                    if(preg_match("/^$/",$splitLine[$i]))
                        $extraAtr++;
                    elseif(preg_match("/^#.*/",$splitLine[$i])){
                        $extraAtr += count($splitLine) - $i;
                        break;
                    }       
                }
                if($atrCount != count($splitLine)-$extraAtr){
                    exitWithCode(23,"Too many arg",$line);
                }
                
                
                //check each agrument
                for($i = 0; $i < $atrCount; $i++){

                    //some position have several type of argument
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
                        
                        //if position have many types op argument its array
                        //then its variable
                        if($manyOptions){
                            $checkedParam = $language[$splitLine[0]][$i][$j];
                        }
                        else{
                            $checkedParam = $language[$splitLine[0]][$i];
                        }
                        
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
                                if(preg_match("/^string@.*/",$splitLine[$i+1])){
                                    
                                    if(!($comment = strpos($splitLine[$i+1],"#"))){
                                        $cutLenght = null;
                                    }
                                    else{
                                        $cutLenght = -(strlen($splitLine[$i+1])-$comment);
                                    }
                                    $val = substr($splitLine[$i+1],7,$cutLenght);
                                    
                                    printXmlElement(
                                        "string",
                                        $i,
                                        $val
                                    );
                                }
                                elseif(preg_match("/^int@*/",$splitLine[$i+1])){
                                    if(preg_match("/[a-zA-Z]+/",($val = substr($splitLine[$i+1],4)))){
                                        $count_of_errors++;
                                    }
                                    elseif(intval($val,10) || $val == 0){
                                        printXmlElement(
                                            "int",
                                            $i,
                                            $val
                                        );
                                    }
                                    else{
                                        $count_of_errors++;
                                    }
                                        
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
                                elseif(preg_match("/^nil@nil$/",$splitLine[$i+1])){
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
                                if(itsLabel($splitLine[$i+1])){
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
                        exitWithCode(23,"command dont have this type of parametr",$line);
                        break;
                    }
                    
                        
                }
            }
            elseif(count($splitLine)-1 > 0){
                exitWithCode(23,"command dont have arg, code haves",$line);
                break;
            }
            //end element 'instruction'
            xmlwriter_end_element($xw);
        }
        //maybe its comment
        elseif(preg_match("/#.*/",$line)){
            continue;
        }
        else{
            exitWithCode(22,"22",$line);
            break;
        }
    }
    //its not a empty line or comment or header or first having command before a header
    else{
        exitWithCode(21,"21",$line);
    }
}
//end of document
xmlwriter_end_element($xw);
xmlwriter_end_document($xw);

if(!$headerIFJ){
    exitWithCode(21,"dont have header","");
}
echo xmlwriter_output_memory($xw);
exitWithCode(0,"0","");