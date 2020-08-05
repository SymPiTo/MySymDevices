<?php

trait FB_soap  
{
          
	
	/*//////////////////////////////////////////////////////////////////////////////
	2018-02´5-01 (TESTED-OK)
	--------------------------------------------------------------------------------
	Funktion 	:	Gibt den Lautstärke Wert zurück
						 
	Befehl		:	GetVolume($channel = 'Master')  
	--------------------------------------------------------------------------------
	Soap / upnp-command
	--------------------------------------------------------------------------------
	Parameter:		 $channel = 'Master'
					
	Rückgabewert: 	 Integer Wert 0 - x 
	//////////////////////////////////////////////////////////////////////////////*/
	  public function Get_Hosts($MetaData)
	  {
	    return $this->processSoapCall(
						"/upnp/control/hosts",

					    "urn:dslforum-org:service:Hosts:1",

					    "GetSpecificHostEntry",

					       array(
							new SoapParam($MetaData       ,"NewMACAddress"   )


							)
		);
	  }



# Daten eines Dienste aus der FritzBox lesen
# $fbroot = 'http://'.FB_HOST.':'.FB_PORT; //Adresse + Port (immer 49000)
# $desc = "tr64desc.xml"; //Hier sind die Infos über die Dienste enthalten
# SCPD = "lanhostconfigmgmSCPD.xml";  // Hier sind die Infos über die Funktionen und Parameter/Variablen enthalten, sowie die Definition der Variablen Typen.
# $action="GetInfo";  //Diese Funktion soll ausgeführt werden
public function ReadServiceList($fbroot,$descXML,$SCPD)
{
	global $_IPS;

	$xml = @simplexml_load_file($fbroot.'/'.$descXML);
	if ($xml === false)
	{
		if ($_IPS['SENDER'] == "WebFront") echo "Not found: ".$descXML.PHP_EOL;
		else IPS_LogMessage("FritzBox","ERROR: Not found: ".$descXML);
		return false;
	}
	$xml->registerXPathNamespace('fb', $xml->getNameSpaces(false)[""]);
	$xmlservice = $xml->xpath("//fb:service[fb:SCPDURL='/".$SCPD."']");
	$service['uri'] = (string)$xmlservice[0]->serviceType;
	$service['location'] =$fbroot.(string)$xmlservice[0]->controlURL;
	$service['SCPDURL'] =trim((string)$xmlservice[0]->SCPDURL,"/");
	return $service;
}
 

	/*//////////////////////////////////////////////////////////////////////////////
	2018-02´5-01 (TESTED-OK)
	--------------------------------------------------------------------------------
	Funktion 	:	Sub Routine für Soap Call. Übertragt upnp Protokoll im Netzwerk
						 
	Befehl		:	processSoapCall($path,$uri,$action,$parameter)
	--------------------------------------------------------------------------------
	Soap / upnp-command
	--------------------------------------------------------------------------------
	Parameter:  $path  
				$uri		 
 				$action
				$parameter
				
	Rückgabewert: 	Fehler Code
	//////////////////////////////////////////////////////////////////////////////*/	
	  Protected function processSoapCall(string $path, string $uri,string $action, array $parameter)
	  {
	    try{
		$ip = $this->ReadPropertyString('FBX_IP');
	      $client     = new SoapClient(
					  null, 
					  array(
						  		"location"   => "http://192.168.178.1:49000".$path,

						       "uri"        => $uri,

							   'trace' 		=> true,

							   'noroot' 	=> true,

							   'login'     => IPS_GetProperty($this->InstanceID, "FBX_USERNAME"),

							   'password'  => IPS_GetProperty($this->InstanceID, "FBX_PASSWORD"),
							    

							));

	      return $client->__soapCall($action,$parameter);
	    }catch(Exception $e){
	      $faultstring = $e->faultstring;
	      $faultcode   = $e->faultcode;
	      if(isset($e->detail->UPnPError->errorCode)){
		$errorCode   = $e->detail->UPnPError->errorCode;
		throw new Exception("Error during Soap Call: ".$faultstring." ".$faultcode." ".$errorCode." (".$this->resoveErrorCode($path,$errorCode).")");
	      }else{

		throw new Exception("Error during Soap Call: ".$faultstring." ".$faultcode);

	      }

	    }

	  }



	/*//////////////////////////////////////////////////////////////////////////////
	2018-02´5-01 (TESTED-OK)
	--------------------------------------------------------------------------------
	Funktion 	:	Sub Routine für Soap Call. Error Handling
						 
	Befehl		:	processSoapCall($path,$uri,$action,$parameter)
	--------------------------------------------------------------------------------
	Soap / upnp-command
	--------------------------------------------------------------------------------
	Parameter:  $path  
				$uri		 
 				$action
				$parameter
				
	Rückgabewert: 	Fehler Code
	//////////////////////////////////////////////////////////////////////////////*/
	  private function resolveErrorCode(string $path, $errorCode)

	  {

	   $errorList = array( "/AVTransport/ctrl"      => array(

										   "701" => "ERROR_AV_UPNP_AVT_INVALID_TRANSITION",

										   "702" => "ERROR_AV_UPNP_AVT_NO_CONTENTS",

										   "703" => "ERROR_AV_UPNP_AVT_READ_ERROR",

										   "704" => "ERROR_AV_UPNP_AVT_UNSUPPORTED_PLAY_FORMAT",

										   "705" => "ERROR_AV_UPNP_AVT_TRANSPORT_LOCKED",

										   "706" => "ERROR_AV_UPNP_AVT_WRITE_ERROR",

										   "707" => "ERROR_AV_UPNP_AVT_PROTECTED_MEDIA",

										   "708" => "ERROR_AV_UPNP_AVT_UNSUPPORTED_REC_FORMAT",

										   "709" => "ERROR_AV_UPNP_AVT_FULL_MEDIA",

										   "710" => "ERROR_AV_UPNP_AVT_UNSUPPORTED_SEEK_MODE",

										   "711" => "ERROR_AV_UPNP_AVT_ILLEGAL_SEEK_TARGET",

										   "712" => "ERROR_AV_UPNP_AVT_UNSUPPORTED_PLAY_MODE",

										   "713" => "ERROR_AV_UPNP_AVT_UNSUPPORTED_REC_QUALITY",

										   "714" => "ERROR_AV_UPNP_AVT_ILLEGAL_MIME",

										   "715" => "ERROR_AV_UPNP_AVT_CONTENT_BUSY",

										   "716" => "ERROR_AV_UPNP_AVT_RESOURCE_NOT_FOUND",

										   "717" => "ERROR_AV_UPNP_AVT_UNSUPPORTED_PLAY_SPEED",

										   "718" => "ERROR_AV_UPNP_AVT_INVALID_INSTANCE_ID"

										 ),

			       "/RenderingControl/ctrl" => array(

										   "701" => "ERROR_AV_UPNP_RC_INVALID_PRESET_NAME",

										   "702" => "ERROR_AV_UPNP_RC_INVALID_INSTANCE_ID"

										 ),

			       "/ContentDirectory/ctrl"   => array(

										   "701" => "ERROR_AV_UPNP_CD_NO_SUCH_OBJECT",

										   "702" => "ERROR_AV_UPNP_CD_INVALID_CURRENTTAGVALUE",

										   "703" => "ERROR_AV_UPNP_CD_INVALID_NEWTAGVALUE",

										   "704" => "ERROR_AV_UPNP_CD_REQUIRED_TAG_DELETE",

										   "705" => "ERROR_AV_UPNP_CD_READONLY_TAG_UPDATE",

										   "706" => "ERROR_AV_UPNP_CD_PARAMETER_NUM_MISMATCH",

										   "708" => "ERROR_AV_UPNP_CD_BAD_SEARCH_CRITERIA",

										   "709" => "ERROR_AV_UPNP_CD_BAD_SORT_CRITERIA",

										   "710" => "ERROR_AV_UPNP_CD_NO_SUCH_CONTAINER",

										   "711" => "ERROR_AV_UPNP_CD_RESTRICTED_OBJECT",

										   "712" => "ERROR_AV_UPNP_CD_BAD_METADATA",

										   "713" => "ERROR_AV_UPNP_CD_RESTRICTED_PARENT_OBJECT",

										   "714" => "ERROR_AV_UPNP_CD_NO_SUCH_SOURCE_RESOURCE",

										   "715" => "ERROR_AV_UPNP_CD_SOURCE_RESOURCE_ACCESS_DENIED",

										   "716" => "ERROR_AV_UPNP_CD_TRANSFER_BUSY",

										   "717" => "ERROR_AV_UPNP_CD_NO_SUCH_FILE_TRANSFER",

										   "718" => "ERROR_AV_UPNP_CD_NO_SUCH_DESTINATION_RESOURCE",

										   "719" => "ERROR_AV_UPNP_CD_DESTINATION_RESOURCE_ACCESS_DENIED",

										   "720" => "ERROR_AV_UPNP_CD_REQUEST_FAILED"

										 ) ); 



	    if (isset($errorList[$path][$errorCode])){

	      return $errorList[$path][$errorCode] ;

	    }else{

	      return "UNKNOWN";

	    }

	  }







# SOAP-Aktion ausführen
Public function MySoapAction($service,$action,$parameter=null,$user = false,$pass = false)
{
	global $_IPS;
	if (($parameter <> null) and (!is_array($parameter)) and (!is_object($parameter)))
	{
		if ($_IPS['SENDER'] == "WebFront") echo "Falscher Parameter in soapCall.".PHP_EOL;
		else IPS_LogMessage("FritzBox","Falscher Parameter in soapCall. Script:".$_IPS['SELF']." - ".IPS_GetName($_IPS['SELF']));
		return false;
	}

	if ($service === false) return false;
	$service['noroot'] = true;
	if ((defined("FB_DEBUG") and FB_DEBUG) or (defined("FB_DEBUG_ERROR") and FB_DEBUG_ERROR))
	$service['trace'] = true;
	else 	$service['trace'] = false;
	$service['exceptions'] = false;
	$service['connection_timeout'] = 2;
	$service['default_socket_timeout'] = 2;
	if (!($user === false))
	{
		$service['login'] = $user;
		$service['password'] = $pass;
	}
	$client = new SoapClient(null,$service);

	for ($i=0;$i<2000;$i++)
	{
		if (IPS_SemaphoreEnter("FB_".(string)12834,1))
		{
			if ($parameter <> null)
			{
			   if (is_array($parameter)) $status = @$client->__soapCall($action,$parameter);
			   elseif (is_object($parameter)) $status = @$client->{$action}($parameter);
			}
			else $status = @$client->{$action}();
			IPS_SemaphoreLeave("FB_".(string)12834);
			$i=-1;
			break;
		} else {
		
		IPS_Sleep(mt_rand(1,5));
		}
	}
	if ($i <> -1)
	{
			if ($_IPS['SENDER'] == "WebFront") echo "Konnte Semaphore nicht setzen.".PHP_EOL;
			else IPS_LogMessage("FritzBox","ERROR: Konnte Semaphore nicht setzen. Script:".$_IPS['SELF']." - ".IPS_GetName($_IPS['SELF']));
			return false;
	}
	if (is_soap_fault($status))
	{
		if (defined("FB_DEBUG_ERROR") and FB_DEBUG_ERROR)
		{
			IPS_LogMessage('FB_SOAP_FAULT',print_r($status,1));
			IPS_LogMessage('FB_DEBUG',print_r($client->__getLastRequest(),1));
		}
		if ($status->faultstring == "Could not connect to host")
		{
		   FB_SetFBOffline();
		   FB_ErrorCounter();
		}
	   return false;
	}

	if (defined("FB_DEBUG") and FB_DEBUG)
	{
		IPS_LogMessage('FB_DEBUG',print_r($status,1));
		IPS_LogMessage('FB_DEBUG',print_r($client->__getLastRequest(),1));
	}
   FB_SetFBOnline();
   if (is_Null($status)) return true;
	else return $status;
}















	/*//////////////////////////////////////////////////////////////////////////////
	2018-02´5-01 (TESTED-OK)
	-------------------------------------------------------------------------------- 	
	Funktion 	:	sende Telnet Befehle	 	 					
	Befehl		:	send_cmd($cmd)
	-------------------------------------------------------------------------------------------
		CEOL-TELNET-command
	-------------------------------------------------------------------------------------------
 	Parameter:		 $cmd
					 'MVUP'  	-> Master Volume UP
					 'MVDOWN'	-> Master Volume DOWN
					 
	Rückgabewert: 	 $xml
	//////////////////////////////////////////////////////////////////////////////*/		
	Protected function send_cmd(string $cmd){
 		$ip = $this->ReadPropertyString('IPAddress');
		$url = "http://$ip:80/goform/formiPhoneAppDirect.xml";
		
		$xml = $this->curl_get($url, $cmd);
		return $xml;
	}


	
	
	/*//////////////////////////////////////////////////////////////////////////////
	2018-02´5-01 (TESTED-OK)
	--------------------------------------------------------------------------------
	* 2nd Method
	* Send a POST request using cURL 
	* @param string $url to request
		$host = "192.168.178.29";  		
		$url =  "http://192.168.178.29/goform/AppCommand.xml";
		
	* @param array $post values to send 	=
							$xml = "<?xml version="1.0" encoding="utf-8"?>";
							$xml .= "<tx>";
	 						$xml .= "<cmd id="1">SetFavoriteStation</cmd>";
	 						$xml .= "<zone>Main</zone>";
	 						$xml .= "<value>3</value>";
							$xml .= "</tx>"
		$post =  $xml;										
	
	* @param array $options for cURL 
	* @return string 
	//////////////////////////////////////////////////////////////////////////////*/	
	Protected function curl_post($url, $post = NULL) 
	{
		print_r($url)."\n";
		print_r($post)."\n";
		$defaults = array(
			CURLOPT_POST => 1,
			//CURLOPT_HEADER => array('Content-Type: text/xml'),
			CURLOPT_HEADER => 1,
			// set url
			CURLOPT_URL => $url,
			CURLOPT_FRESH_CONNECT => 1,
			//return the transfer as a string
			CURLOPT_RETURNTRANSFER => 1,
			//CURLOPT_FORBID_REUSE => 1,
			CURLOPT_TIMEOUT => 5,
			//CURLOPT_POSTFIELDS => http_build_query($post)
			//CURLOPT_POSTFIELDS => $post
			);
		// create curl resource
		$ch = curl_init();
		curl_setopt_array($ch, ($defaults));
		
		
		if( ! $result = curl_exec($ch))
		{
			trigger_error(curl_error($ch));
		}
		// close curl resource to free up system resources
		curl_close($ch);
		return $result;
		} 
	
	/*//////////////////////////////////////////////////////////////////////////////
	2018-02´5-01 (TESTED-OK)
	--------------------------------------------------------------------------------
	* 1st Method
	* Send a GET request using cURL 
	* @param string $url to request 
	* @param array $get values to send 
	* @param array $options for cURL 
	* @return string 
	
	//////////////////////////////////////////////////////////////////////////////*/	
	Protected function curl_get($url, $get)
	{
		//print_r($get);
		//$test = $url. (strpos($url, '?') === FALSE ? '?' : ''). http_build_query($get);
		//$test = $url. (strpos($url, '?') === FALSE ? '?' : ''). $get;
		//print_r($test);
		//$ret = Message($test);
		$defaults = array(
			//CURLOPT_URL => $url. (strpos($url, '?') === FALSE ? '?' : ''). http_build_query($get),
			CURLOPT_URL => $url. (strpos($url, '?') === FALSE ? '?' : ''). $get,
			CURLOPT_HEADER => 0,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_TIMEOUT => 4
		);
		
		$ch = curl_init();
		curl_setopt_array($ch, ($defaults));
		
		$result = curl_exec($ch);
		
		
		$error = curl_error($ch);
		//print_r($result);
		
		//if( ! $result = curl_exec($ch))
		//{
			//trigger_error('Curl-Fehler:'.curl_error($ch));
		//}
		$errno = curl_errno($ch);
	
		//	print_r($errno);
		
		curl_close($ch);
		return $result;
	}
        
            
        Public function TelnetCeol(string $command, string $value) {
            $ip = $this->ReadPropertyString('IPAddress');
            $socket = fsockopen($ip, 23, $errno, $errstr); 

            if($socket) 
            { 
                //echo "Connected <br /><br />"; 
            } 
            else 
            { 
                //echo "Connection failed!<br /><br />"; 
            } 
            $cmd = $command.$value.chr(13);
            fputs($socket, $cmd); 
            //$buffer = ""; 
            //while(!feof($socket)) 
            //{ 
              //  $buffer .=fgets($socket, 4096); 
            //} 

            //print_r($buffer); 
            //echo "<br /><br /><br />"; 
            //var_dump($buffer); 

            fclose($socket); 
        }
} //Ende der Klasse


?>
