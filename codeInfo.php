<?php

enum ParamTypes{
    case variable;
    case symbol;
    case label;
    case type;
}

enum SymbolType{
    case string;
    case integer;
    case bool;
    case nil;
}

$language = array(
    //prace s pamci
    "MOVE" => array(ParamTypes::variable,ParamTypes::symbol),
    "CREATEFRAME" => NULL,
    "PUSHFRAME" => NULL,
    "POPFRAME" => NULL,
    "DEFVAR" => array(ParamTypes::variable),
    "CALL" => array(ParamTypes::label),
    "RETURN" => NULL,
    //
    "PUSHS" => array(ParamTypes::symbol),
    "POPS" => array(ParamTypes::variable),

    "ADD" => array(ParamTypes::variable,array(ParamTypes::symbol,ParamTypes::variable),array(ParamTypes::symbol,ParamTypes::variable)),
    "SUB" => array(ParamTypes::variable,array(ParamTypes::symbol,ParamTypes::variable),array(ParamTypes::symbol,ParamTypes::variable)),
    "MUL" => array(ParamTypes::variable,array(ParamTypes::symbol,ParamTypes::variable),array(ParamTypes::symbol,ParamTypes::variable)),
    "IDIV" => array(ParamTypes::variable,array(ParamTypes::symbol,ParamTypes::variable),array(ParamTypes::symbol,ParamTypes::variable)),
    "LT" => array(ParamTypes::variable,array(ParamTypes::symbol,ParamTypes::variable),array(ParamTypes::symbol,ParamTypes::variable)),
    "GT" => array(ParamTypes::variable,array(ParamTypes::symbol,ParamTypes::variable),array(ParamTypes::symbol,ParamTypes::variable)),
    "EQ" => array(ParamTypes::variable,array(ParamTypes::symbol,ParamTypes::variable),array(ParamTypes::symbol,ParamTypes::variable)),
    "AND" => array(ParamTypes::variable,array(ParamTypes::symbol,ParamTypes::variable),array(ParamTypes::symbol,ParamTypes::variable)),
    "OR" => array(ParamTypes::variable,array(ParamTypes::symbol,ParamTypes::variable),array(ParamTypes::symbol,ParamTypes::variable)),
    "NOT" => array(ParamTypes::variable,array(ParamTypes::symbol,ParamTypes::variable)),
    "INT2CHAR" => array(ParamTypes::variable,array(ParamTypes::symbol,ParamTypes::variable)),
    "STRI2INT" => array(ParamTypes::variable,array(ParamTypes::symbol,ParamTypes::variable),array(ParamTypes::symbol,ParamTypes::variable)),

    "READ" => array(ParamTypes::variable,ParamTypes::type),
    "WRITE" => array(array(ParamTypes::symbol,ParamTypes::variable)),

    "CONCAT" => array(ParamTypes::variable,array(ParamTypes::symbol,ParamTypes::variable),array(ParamTypes::symbol,ParamTypes::variable)),
    "STRLEN" => array(ParamTypes::variable,array(ParamTypes::symbol,ParamTypes::variable)),
    "GETCHAR" => array(ParamTypes::variable,array(ParamTypes::symbol,ParamTypes::variable),array(ParamTypes::symbol,ParamTypes::variable)),
    "SETCHAR" => array(ParamTypes::variable,array(ParamTypes::symbol,ParamTypes::variable),array(ParamTypes::symbol,ParamTypes::variable)),

    "TYPE" => array(ParamTypes::variable,array(ParamTypes::symbol,ParamTypes::variable)),

    "LABEL" => array(ParamTypes::label),
    "JUMP" => array(ParamTypes::label),
    "JUMPIFEQ" =>  array(ParamTypes::label,array(ParamTypes::symbol,ParamTypes::variable),array(ParamTypes::symbol,ParamTypes::variable)),
    "JUMPIFNEQ" => array(ParamTypes::label,array(ParamTypes::symbol,ParamTypes::variable),array(ParamTypes::symbol,ParamTypes::variable)),
    "EXIT" => array(array(ParamTypes::symbol,ParamTypes::variable)),

    "DPRINT" => array(array(ParamTypes::symbol,ParamTypes::variable)),
    "BREAK" => NULL,
);
?>