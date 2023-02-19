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

    "ADD" => array(ParamTypes::variable,ParamTypes::symbol,ParamTypes::symbol),
    "SUB" => array(ParamTypes::variable,ParamTypes::symbol,ParamTypes::symbol),
    "MUL" => array(ParamTypes::variable,ParamTypes::symbol,ParamTypes::symbol),
    "IDIV" => array(ParamTypes::variable,ParamTypes::symbol,ParamTypes::symbol),
    "LT" => array(ParamTypes::variable,ParamTypes::symbol,ParamTypes::symbol),
    "GT" => array(ParamTypes::variable,ParamTypes::symbol,ParamTypes::symbol),
    "EQ" => array(ParamTypes::variable,ParamTypes::symbol,ParamTypes::symbol),
    "AND" => array(ParamTypes::variable,ParamTypes::symbol,ParamTypes::symbol),
    "OR" => array(ParamTypes::variable,ParamTypes::symbol,ParamTypes::symbol),
    "NOT" => array(ParamTypes::variable,ParamTypes::symbol,ParamTypes::symbol),
    "INT2CHAR" => array(ParamTypes::variable,ParamTypes::symbol),
    "STRI2INT" => array(ParamTypes::variable,ParamTypes::symbol,ParamTypes::symbol),

    "READ" => array(ParamTypes::variable,ParamTypes::type),
    "WRITE" => array(ParamTypes::symbol),

    "CONCAT" => array(ParamTypes::variable,ParamTypes::symbol,ParamTypes::symbol),
    "STRLEN" => array(ParamTypes::variable,ParamTypes::symbol),
    "GETCHAR" => array(ParamTypes::variable,ParamTypes::symbol,ParamTypes::symbol),
    "SETCHAR" => array(ParamTypes::variable,ParamTypes::symbol,ParamTypes::symbol),

    "TYPE" => array(ParamTypes::variable,ParamTypes::symbol),

    "LABEL" => array(ParamTypes::label),
    "JUMP" => array(ParamTypes::label),
    "JUMPIFEQ" =>  array(ParamTypes::label,ParamTypes::symbol,ParamTypes::symbol),
    "JUMPIFNEQ" => array(ParamTypes::label,ParamTypes::symbol,ParamTypes::symbol),
    "EXIT" => array(ParamTypes::symbol),

    "DPRINT" => array(ParamTypes::symbol),
    "BREAK" => NULL,
);
?>