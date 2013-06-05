<?php
/*
 IXR - The Inutio XML-RPC Library - (c) Incutio Ltd 2002
 Version 1.61 - Simon Willison, 11th July 2003 (htmlentities -> htmlspecialchars)
 Site: http://scripts.incutio.com/xmlrpc/
 Manual: http://scripts.incutio.com/xmlrpc/manual.php
 Made available under the Artistic License: http://www.opensource.org/licenses/artistic-license.php
*/
class IXR_Value{
	var $data;
	var $type;

	function IXR_Value($data, $type=false){
		$this->data=$data;
		if(!$type)$type=$this->calculateType();
		$this->type=$type;
		if($type=='struct'){
			foreach($this->data as $key=>$value)$this->data[$key]=new IXR_Value($value);
		}
		if($type=='array'){
			for($i=0, $j=count($this->data); $i<$j; $i++)$this->data[$i]=new IXR_Value($this->data[$i]);
		}
	}

	function calculateType(){
		if($this->data===true || $this->data===false)return 'boolean';
		if(is_integer($this->data))return 'int';
		if(is_double($this->data))return 'double';
		if(is_object($this->data) && is_a($this->data, 'IXR_Date'))return 'date';
		if(is_object($this->data) && is_a($this->data, 'IXR_Base64'))return 'base64';
		if(is_object($this->data)){
			$this->data=get_object_vars($this->data);
			return 'struct';
		}
		if(!is_array($this->data))return 'string';
		if($this->isStruct($this->data)){
			return 'struct';
		}else{
			return 'array';
		}
	}

	function getXml(){
		switch ($this->type){
			case 'boolean':
				return '<boolean>'.(($this->data)?'1':'0').'</boolean>';
				break;
			case 'int':
				return '<int>'.$this->data.'</int>';
				break;
			case 'double':
				return '<double>'.$this->data.'</double>';
				break;
			case 'string':
				return '<string>'.htmlspecialchars($this->data).'</string>';
				break;
			case 'array':
				$return="<array><data>\n";
				foreach($this->data as $item)$return.='<value>'.$item->getXml()."</value>\n";
				$return.='</data></array>';
				return $return;
				break;
			case 'struct':
				$return="<struct>\n";
				foreach($this->data as $name=>$value)$return.="<member><name>$name</name><value>".$value->getXml()."</value></member>\n";
				$return.='</struct>';
				return $return;
				break;
			case 'date':
			case 'base64':
				return $this->data->getXml();
				break;
		}
		return false;
	}

	function isStruct($array){
		$expected=0;
		foreach ($array as $key=>$value){
			if((string)$key!=(string)$expected)return true;
			$expected++;
		}
		return false;
	}
}

class IXR_Message {
	var $message;
	var $messageType;
	var $faultCode;
	var $faultString;
	var $methodName;
	var $params;
	var $_arraystructs=array();
	var $_arraystructstypes=array();
	var $_currentStructName=array();
	var $_param;
	var $_value;
	var $_currentTag;
	var $_currentTagContents;
	var $_parser;

	function IXR_Message ($message){
		$this->message=$message;
	}

	function parse(){
		$this->message=preg_replace('/<\?xml(.*)?\?'.'>/', '', $this->message);
		if(trim($this->message)=='')return false;
		$this->_parser=xml_parser_create();
		xml_parser_set_option($this->_parser, XML_OPTION_CASE_FOLDING, false);
		xml_set_object($this->_parser, $this);
		xml_set_element_handler($this->_parser, 'tag_open', 'tag_close');
		xml_set_character_data_handler($this->_parser, 'cdata');
		if(!xml_parse($this->_parser, $this->message))return false;
		xml_parser_free($this->_parser);
		if($this->messageType=='fault'){
			$this->faultCode=$this->params[0]['faultCode'];
			$this->faultString=$this->params[0]['faultString'];
		}
		return true;
	}

	function tag_open($parser, $tag, $attr){
		$this->currentTag=$tag;
		switch($tag){
			case 'methodCall':
			case 'methodResponse':
			case 'fault':
				$this->messageType=$tag;
				break;
			case 'data':
				$this->_arraystructstypes[]='array';
				$this->_arraystructs[]=array();
				break;
			case 'struct':
				$this->_arraystructstypes[]='struct';
				$this->_arraystructs[]=array();
				break;
		}
	}

	function cdata($parser, $cdata){
		$this->_currentTagContents.=$cdata;
	}

	function tag_close($parser, $tag){
		$valueFlag=false;
		switch($tag){
			case 'int':
			case 'i4':
				$value=(int)trim($this->_currentTagContents);
				$this->_currentTagContents='';
				$valueFlag=true;
				break;
			case 'double':
				$value=(double)trim($this->_currentTagContents);
				$this->_currentTagContents='';
				$valueFlag=true;
				break;
			case 'string':
				$value=(string)trim($this->_currentTagContents);
				$this->_currentTagContents='';
				$valueFlag=true;
				break;
			case 'dateTime.iso8601':
				$value=new IXR_Date(trim($this->_currentTagContents));
				$this->_currentTagContents='';
				$valueFlag=true;
				break;
			case 'value':
				if(trim($this->_currentTagContents)!=''){
					$value=(string)$this->_currentTagContents;
					$this->_currentTagContents='';
					$valueFlag=true;
				}
				break;
			case 'boolean':
				$value=(boolean)trim($this->_currentTagContents);
				$this->_currentTagContents='';
				$valueFlag=true;
				break;
			case 'base64':
				$value=base64_decode($this->_currentTagContents);
				$this->_currentTagContents='';
				$valueFlag=true;
				break;
			case 'data':
			case 'struct':
				$value=array_pop($this->_arraystructs);
				array_pop($this->_arraystructstypes);
				$valueFlag=true;
				break;
			case 'member':
				array_pop($this->_currentStructName);
				break;
			case 'name':
				$this->_currentStructName[]=trim($this->_currentTagContents);
				$this->_currentTagContents='';
				break;
			case 'methodName':
				$this->methodName=trim($this->_currentTagContents);
				$this->_currentTagContents='';
				break;
		}
		if($valueFlag){
			if(count($this->_arraystructs)>0){
				if($this->_arraystructstypes[count($this->_arraystructstypes)-1]=='struct'){
					$this->_arraystructs[count($this->_arraystructs)-1][$this->_currentStructName[count($this->_currentStructName)-1]]=$value;
				}else{
					$this->_arraystructs[count($this->_arraystructs)-1][]=$value;
				}
			}else{
				$this->params[]=$value;
			}
		}
	}
}

class IXR_Request {
	var $method;
	var $args;
	var $xml;

	function IXR_Request($method, $args){
		$this->method=$method;
		$this->args=$args;
		$this->xml=<<<EOD
<?xml version="1.0"?>
<methodCall>
<methodName>{$this->method}</methodName>
<params>

EOD;
		foreach ($this->args as $arg){
			$this->xml.='<param><value>';
			$v=new IXR_Value($arg);
			$this->xml.=$v->getXml();
			$this->xml.="</value></param>\n";
		}
		$this->xml.='</params></methodCall>';
	}

	function getLength(){
		return strlen($this->xml);
	}

	function getXml(){
		return $this->xml;
	}
}

class IXR_Client {
	var $server;
	var $port;
	var $path;
	var $useragent;
	var $response;
	var $message=false;
	var $debug=false;
	var $error=false;

	function IXR_Client($server, $path=false, $port=80){
		if(!$path){
			$bits=parse_url($server);
			$this->server=$bits['host'];
			$this->port=isset($bits['port'])?$bits['port']:80;
			$this->path=isset($bits['path'])?$bits['path']:'/';
			if(!$this->path)$this->path='/';
		}else{
			$this->server=$server;
			$this->path=$path;
			$this->port=$port;
		}
		$this->useragent='Gblog';
	}

	function query(){
		$args=func_get_args();
		$method=array_shift($args);
		$request=new IXR_Request($method, $args);
		$length=$request->getLength();
		$xml=$request->getXml();
		$r="\r\n";
		$request="POST {$this->path} HTTP/1.0$r";
		$request.="Host: {$this->server}$r";
		$request.="Content-Type: text/xml$r";
		$request.="User-Agent: {$this->useragent}$r";
		$request.="Content-length: {$length}$r$r";
		$request.=$xml;
		if($this->debug)echo '<pre>'.htmlspecialchars($request)."\n</pre>\n\n";
		$fp=@fsockopen($this->server, $this->port);
		if(!$fp){
			$this->error=new IXR_Error(-32300, 'transport error - could not open socket');
			return false;
		}
		fputs($fp, $request);
		$contents='';
		$gotFirstLine=false;
		$gettingHeaders=true;
		while(!feof($fp)){
			$line=fgets($fp, 4096);
			if(!$gotFirstLine){
				if(strstr($line, '200')===false){
					$this->error=new IXR_Error(-32300, 'transport error - HTTP status code was not 200');
					return false;
				}
				$gotFirstLine=true;
			}
			if(trim($line)=='')$gettingHeaders=false;
			if(!$gettingHeaders)$contents.=trim($line)."\n";
		}
		if($this->debug)echo '<pre>'.htmlspecialchars($contents)."\n</pre>\n\n";
		$this->message=new IXR_Message($contents);
		if(!$this->message->parse()){
			$this->error=new IXR_Error(-32700, 'parse error. not well formed');
			return false;
		}
		if($this->message->messageType=='fault'){
			$this->error=new IXR_Error($this->message->faultCode, $this->message->faultString);
			return false;
		}
		return true;
	}

	function getResponse(){
		return $this->message->params[0];
	}

	function isError(){
		return (is_object($this->error));
	}

	function getErrorCode(){
		return $this->error->code;
	}

	function getErrorMessage(){
		return $this->error->message;
	}
}

class IXR_Error {
	var $code;
	var $message;

	function IXR_Error($code, $message){
		$this->code=$code;
		$this->message=$message;
	}

	function getXml(){
		$xml=<<<EOD
<methodResponse>
<fault>
<value>
<struct>
<member>
<name>faultCode</name>
<value><int>{$this->code}</int></value>
</member>
<member>
<name>faultString</name>
<value><string>{$this->message}</string></value>
</member>
</struct>
</value>
</fault>
</methodResponse>

EOD;
		return $xml;
	}
}

class IXR_Date {
	var $year;
	var $month;
	var $day;
	var $hour;
	var $minute;
	var $second;

	function IXR_Date($time){
		if(is_numeric($time)){
			$this->parseTimestamp($time);
		}else{
			$this->parseIso($time);
		}
	}

	function parseTimestamp($timestamp){
		$this->year=date('Y', $timestamp);
		$this->month=date('Y', $timestamp);
		$this->day=date('Y', $timestamp);
		$this->hour=date('H', $timestamp);
		$this->minute=date('i', $timestamp);
		$this->second=date('s', $timestamp);
	}

	function parseIso($iso){
		$this->year=substr($iso, 0, 4);
		$this->month=substr($iso, 4, 2);
		$this->day=substr($iso, 6, 2);
		$this->hour=substr($iso, 9, 2);
		$this->minute=substr($iso, 12, 2);
		$this->second=substr($iso, 15, 2);
	}

	function getIso(){
		return $this->year.$this->month.$this->day.'T'.$this->hour.':'.$this->minute.':'.$this->second;
	}

	function getXml(){
		return '<dateTime.iso8601>'.$this->getIso().'</dateTime.iso8601>';
	}

	function getTimestamp(){
		return mktime($this->hour, $this->minute, $this->second, $this->month, $this->day, $this->year);
	}
}

class IXR_Base64 {
	var $data;

	function IXR_Base64($data){
		$this->data=$data;
	}

	function getXml(){
		return '<base64>'.base64_encode($this->data).'</base64>';
	}
}

class IXR_ClientMulticall extends IXR_Client {
	var $calls=array();

	function IXR_ClientMulticall($server, $path=false, $port=80){
		parent::IXR_Client($server, $path, $port);
		$this->useragent='Gblog';
	}

	function addCall(){
		$args=func_get_args();
		$methodName=array_shift($args);
		$struct=array(
			'methodName'=>$methodName,
			'params'=>$args
		);
		$this->calls[]=$struct;
	}

	function query(){
		return parent::query('system.multicall', $this->calls);
	}
}
