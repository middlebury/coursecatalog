<?php
/*
 FSM Parser demo: the simple XML parser (using LoadFSMFile).
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

//---------Programming the FSM :)
$parser->LoadFSMFile("xmlparser_loadfsm.fsm");

//---------Run the parser
$parser->ParseFile("example.xml","CDATA");

?>