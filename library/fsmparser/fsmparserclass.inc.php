<?php
/*

Finite State Machine Parser class.

FSM-based text parser framework.

*/

//Parse() result codes
define("FSMSTOP_OK",0);		//FSM reached end of text or file.
define("FSMSTOP_UNHANDLED",1);	//FSM stopped because some state is unhandled.
define("FSMSTOP_STOP",2);	//FSM stopped by a handler.

class FSMParser {

 var $FSM=array();
 var $STATE;

/*
 FSM programming method.
 $expect	- Token regex. On best match...
 $execute	- ...execute this PHP code. $STRING is a match, $STATE is current state.
  Result can be an array:
   "NEWSTATE" (optional) sets the new FSM state.
   "STOP" if nonzero stops FSM.
  If not - its result is assumed as new state
 $defaultstate	- if function did not return any state, assume this state.
 $state		- Before all, check if FSM is in this state.
*/
 function FSM($expect,$execute,$defaultstate,$state=NULL){
  $this->FSM[]=array("ex"=>$expect,"do"=>$execute,"ds"=>$defaultstate,"cs"=>$state);
 }

/*
 FSM loading function. Syntax described in README.
*/
 function LoadFSM($data){
   $dataset=explode("\n",$data);
   foreach($dataset as $line){
    if(strlen(trim($line))){
     if($line[0]=="#") continue;
     if($line[0]=="\t") { $do.=trim($line); continue; }
     $comp=explode(" ",$line);
     $cs=($comp[0]=="*")?NULL:trim($comp[0]);
     $ex=trim($comp[1]);
     $ds=trim($comp[2]);
    }elseif(strlen($ex)){
     $this->FSM($ex,$do,$ds,$cs);
     unset($ex);
     unset($do);
     unset($ds);
     unset($cs);
    }
   }
 }

/*
 FSM loading function - file wrapper.
*/
 function LoadFSMFile($file){
  $this->LoadFSM(file_get_contents($file));
 }


/*
 Main loop method.
*/
 function Parse($text,$state){
  $PTR=0; $LEN=strlen($text); $RET=FSMSTOP_OK; $this->STATE=$state;
  while($PTR<$LEN){
   foreach($this->FSM as $tkey=>$line) if( (!$line["cs"]) or ($line["cs"]==$this->STATE) ) {
    if(preg_match($line["ex"],substr($text,$PTR),$matches,PREG_OFFSET_CAPTURE)) {
     if( (is_null($tok_off)) or ($matches[0][1]<$tok_off) or ( ($matches[0][1]==$tok_off) and (strlen($matches[0][0])>$tok_len) ) ) {
      $tok_off=$matches[0][1];
      $tok_len=strlen($matches[0][0]);
      $tok_key=$tkey;
      $STRING=$matches[0][0];
     }
    }
   }
   if(is_null($tok_key)){$RET=FSMSTOP_UNHANDLED;break;}
   $STATE=$this->STATE;
   if(is_array($result=eval($this->FSM[$tok_key]["do"]))){
    $this->STATE=$result["NEWSTATE"]?$result["NEWSTATE"]:($this->FSM[$tok_key]["ds"]?$this->FSM[$tok_key]["ds"]:$this->STATE);
    if($result["STOP"]){$RET=FSMSTOP_STOP;break;}
   }else{
    $this->STATE=$result?$result:($this->FSM[$tok_key]["ds"]?$this->FSM[$tok_key]["ds"]:$this->STATE);
   }
   $PTR+=$tok_off+$tok_len;
   unset($tok_off);
   unset($tok_len);
   unset($tok_key);
  }
  return $RET;
 }

/*
 Main loop method - file wrapper.
*/
 function ParseFile($filename,$state){
  return $this->Parse(file_get_contents($filename),$state);
 }

}
?>
