<?php
/*
 FSM Parser demo: the simple XML parser.
*/
include_once("fsmparserclass.inc.php");
$parser=new FSMParser();

//---------Functions to handle FSM events
function BeginTag($str){
 echo "+TAG:".$str;
}

function EndTag($str){
if(strpos($str,'/')!==false)echo " CLOSED-";
echo "\n";
}

function ClosingTag($str){
 echo "-TAG:".trim($str,'<>/')."\n";
}

function GetSimpleAttr($str){
 echo " ".$str."=ON";
}

function GetValueAttr($str){
 $v=explode("=",$str,2);
 echo " ".$v[0]."=".$v[1];
}

//---------Programming the FSM:
//In normal state, catch opening or simple tag start
$parser->FSM('/</','','TAG_OPEN','CDATA');
//In normal state, catch closing tag
$parser->FSM('/<\/\w+\s*>/','return ClosingTag($STRING);','CDATA','CDATA');
//In normal state, catch all other data
$parser->FSM('/./s','echo $STRING;','CDATA','CDATA');
//First word after tag start would be tag name
$parser->FSM('/[\w_]+/','return BeginTag($STRING);','IN_TAG','TAG_OPEN');
//While in tag, catch simple attributes
$parser->FSM('/[\w_]+/','return GetSimpleAttr($STRING);','IN_TAG','IN_TAG');
//While in tag, catch attributes with values
$parser->FSM('/[\w_]+=[\'""][^\'"]*[\'"]/','return GetValueAttr($STRING);','IN_TAG','IN_TAG');
//While in tag, catch tag closing
$parser->FSM('/\/?>/','return EndTag($STRING);','CDATA','IN_TAG');

//---------Run the parser
$parser->ParseFile("example.xml","CDATA");
?>