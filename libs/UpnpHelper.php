<?php

/* --------------------------------------------------------------------------- 
  TRAITS: 
...............................................................................
        
  * UPNP_Discovery
 
 
-------------------------------------------------------------------------------*/
trait UpnpDeviceDiscovery {
    //*****************************************************************************
	/* Function: searchUPNP($member)
	...............................................................................
	Sucht alle UPNP Clients / Server
	...............................................................................
	Parameters:  
            $member - "client" // "server".
	--------------------------------------------------------------------------------
	Returns: 
                * Device Array[]_.
                    - $Device_Array[$i]['DeviceDescription']. 
                    - $Device_Array[$i]['Root'].
                    - $Device_Array[$i]['DeviceIP'].
                    - $Device_Array[$i]['DevicePort'].
                    - $Device_Array[$i]['ModelName']. 
                    - $Device_Array[$i]['UDN'].
                    - $Device_Array[$i]['FriendlyName']. 
                    - $Device_Array[$i]['IconURL'].
                    - $Device_Array[$i]['DeviceControlServiceType'].
                    - $Device_Array[$i]['DeviceControlURL'].
                    - $Device_Array[$i]['DeviceRenderingServiceType'].
                    - $Device_Array[$i]['DeviceRenderingControlURL'].
                    - $Device_Array[$i]['DeviceActiveIcon'].
	--------------------------------------------------------------------------------*/
        //Status:
	/* **************************************************************************** */
	public function searchUPNP(string $member){
		/*mögliche Aufrufe:
		$ST_ALL = "ssdp:all";
		$ST_RD = "upnp:rootdevice";
		$ST_AV = "urn:dial-multiscreen-org:service:dial:1"; 
		*/
		$ST_MR = "urn:schemas-upnp-org:device:MediaRenderer:1";
		$ST_MS = "urn:schemas-upnp-org:device:MediaServer:1";
		/*
		$ST_CD = "urn:schemas-upnp-org:service:ContentDirectory:1";
		$ST_RC = "urn:schemas-upnp-org:service:RenderingControl:1";
		--------------------------------------------------------------------------------
		*/
		if ($member == "client"){
			
			$SSDP_Search_Array = $this->mSearch($ST_MR);
			//IPSLog('mSearch Ergebnis ',$SSDP_Search_Array);
			
			$SSDP_Array = $this->array_multi_unique($SSDP_Search_Array);
			//IPSLog('bereinigtes mSearch Ergebnis ',$SSDP_Array);
			
		 	$UPNP_Device_Array = $this->create_UPNP_Device_Array($SSDP_Array); 
			//IPSLog('create Device Ergebnis ',$UPNP_Device_Array);
			//Ergebnis wird als ARRAY in ID_CLIENT_ARRAY in Subfunctions gespeichert;
			$upnpArray = $UPNP_Device_Array;
			
		}
		if ($member == "server"){
			//$this->setvalue("upnp_ServerArray", 'suche Server ...');
			$SSDP_Search_Array = $this->mSearch($ST_MS);
			$SSDP_Array = $this->array_multi_unique($SSDP_Search_Array);
			//IPSLog('bereinigtes mSearch Ergebnis ',$SSDP_Array);
		 	$UPNP_Server_Array = $this->create_UPNP_Server_Array($SSDP_Array); 
			//Ergebnis wird als ARRAY in ID_Server_ARRAY in Subfunctions gespeichert;
            $upnpArray = $UPNP_Server_Array;
		}
		//IPSLog('FERTIG ', $result);
		return($upnpArray);
	}

    
    //*****************************************************************************
    /* Function: mSearch($ST)
    ...............................................................................
    Sucht alle UPNP Clients / Server
    ...............................................................................
    Parameters:  
     * $ST = $ST_MR for Clients / $ST_MS for server
        $ST_ALL - "ssdp:all";
        $ST_RD - "upnp:rootdevice";
        $ST_AV - "urn:dial-multiscreen-org:service:dial:1";
        $ST_MR - "urn:schemas-upnp-org:device:MediaRenderer:1";
        $ST_MS - "urn:schemas-upnp-org:device:MediaServer:1";
        $ST_CD - "urn:schemas-upnp-org:service:ContentDirectory:1";
        $ST_RC - "urn:schemas-upnp-org:service:RenderingControl:1";
    --------------------------------------------------------------------------------
    Returns: 
        $response
    --------------------------------------------------------------------------------*/
    //Status: checked 30.4.2019
    /* **************************************************************************** */
        public function mSearch( $st = 'ssdp:all', $mx = 2, $man = 'ssdp:discover', $from = null, $port = null, $sockTimout = '5' )
        {
                $USER_AGENT = 'IP-Symcon, UPnP/1.0, IPSKernelVersion:' . IPS_GetKernelVersion();
        // BUILD MESSAGE
                $msg  = 'M-SEARCH * HTTP/1.1' . "\r\n";
                $msg .= 'HOST: 239.255.255.250:1900' ."\r\n";
                $msg .= 'MAN: "'. $man .'"' . "\r\n";
                $msg .= 'MX: '. $mx ."\r\n";
                $msg .= 'ST:' . $st ."\r\n";
                $msg .= 'USER-AGENT: '. $USER_AGENT ."\r\n";
                $msg .= '' ."\r\n";
                // MULTICAST MESSAGE
                $sock = socket_create( AF_INET, SOCK_DGRAM, 0 );
                $opt_ret = socket_set_option( $sock, 1, 6, TRUE );
                $send_ret = socket_sendto( $sock, $msg, strlen( $msg ), 0, '239.255.255.250', 1900);
                // SET TIMEOUT FOR RECIEVE
                socket_set_option( $sock, SOL_SOCKET, SO_RCVTIMEO, array( 'sec'=>$sockTimout, 'usec'=>'0' ) );
                // RECIEVE RESPONSE
                $response = array();
                do {
                        $buf = null;
                        @socket_recvfrom( $sock, $buf, 1024, MSG_WAITALL, $from, $port );
                        if( !is_null($buf) )$response[] = $this->parseMSearchResponse( $buf );
                } while( !is_null($buf) );
                // CLOSE SOCKET
                socket_close( $sock );
                return $response;
        }
 




/* //////////////////////////////////////////////////////////////////////////////
  function parseMSearchResponse( $response )
  Aufarbeitung der SSDP-Response
  / *//////////////////////////////////////////////////////////////////////////////
  //
    //*****************************************************************************
    /* Function: parseMSearchResponse($response)
    ...............................................................................
    Aufarbeitung der SSDP-Response
    ...............................................................................
    Parameters:  
     * $response - SSDP-Response
    --------------------------------------------------------------------------------
    Returns: 
        $parsedResponse - array
    --------------------------------------------------------------------------------*/
    //Status: checked 29.6.2018
    /* **************************************************************************** */
    Protected function parseMSearchResponse($response) {
        $responseArray = explode("\r\n", $response);

        $parsedResponse = array();

        //Response auslesen und bearbeiten, da häufig unterschiedlich in Groß- und Kleinschreibung sowie Leerzeichen dazwischen
        foreach ($responseArray as $row) {
            if (stripos($row, 'HTTP') === 0) {
                $parsedResponse['HTTP'] = $row;
            }
            if (stripos($row, 'CACHE-CONTROL:') === 0) {
                $parse = str_ireplace('CACHE-CONTROL:', '', $row);

                $parsedResponse['CACHE-CONTROL'] = str_ireplace('CACHE-CONTROL:', '', $row);

                //prüfen, ob erstes Zeichen ein "Leerzeichen" ist, da
                if (0 === strpos($parse, " ")) {
                    $parsedResponse['CACHE-CONTROL'] = trim($parse, " ");
                } else {
                    $parsedResponse['CACHE-CONTROL'] = $parse;
                }
            }
            if (stripos($row, 'DATE:') === 0) {
                $parse = str_ireplace('DATE:', '', $row);

                $parsedResponse['DATE'] = str_ireplace('DATE:', '', $row);

                //prüfen, ob erstes Zeichen ein "Leerzeichen" ist, da
                if (0 === strpos($parse, " ")) {
                    $parsedResponse['DATE'] = trim($parse, " ");
                } else {
                    $parsedResponse['DATE'] = $parse;
                }
            }
            if (stripos($row, 'LOCATION:') === 0) {
                $parse = str_ireplace('LOCATION:', '', $row);

                $parsedResponse['LOCATION'] = str_ireplace('LOCATION:', '', $row);

                //prüfen, ob erstes Zeichen ein "Leerzeichen" ist, da
                if (0 === strpos($parse, " ")) {
                    $parsedResponse['LOCATION'] = trim($parse, " ");
                } else {
                    $parsedResponse['LOCATION'] = $parse;
                }
            }
            if (stripos($row, 'SERVER:') === 0) {
                $parse = str_ireplace('SERVER:', '', $row);

                $parsedResponse['SERVER'] = str_ireplace('SERVER:', '', $row);

                //prüfen, ob erstes Zeichen ein "Leerzeichen" ist, da
                if (0 === strpos($parse, " ")) {
                    $parsedResponse['SERVER'] = trim($parse, " ");
                } else {
                    $parsedResponse['SERVER'] = $parse;
                }
            }
            if (stripos($row, 'ST:') === 0) {
                $parse = str_ireplace('ST:', '', $row);

                $parsedResponse['ST'] = str_ireplace('ST:', '', $row);

                //prüfen, ob erstes Zeichen ein "Leerzeichen" ist, da
                if (0 === strpos($parse, " ")) {
                    $parsedResponse['ST'] = trim($parse, " ");
                } else {
                    $parsedResponse['ST'] = $parse;
                }
            }
            if (stripos($row, 'USN:') === 0) {
                $parse = str_ireplace('USN:', '', $row);

                $parsedResponse['USN'] = str_ireplace('USN:', '', $row);

                //prüfen, ob erstes Zeichen ein "Leerzeichen" ist, da
                if (0 === strpos($parse, " ")) {
                    $parsedResponse['USN'] = trim($parse, " ");
                } else {
                    $parsedResponse['USN'] = $parse;
                }
            }
        }
        return $parsedResponse;
    }


      
    //*****************************************************************************
    /* Function: array_multi_unique($multiArray)
    ...............................................................................
    Entfernung von Duplikaten
    ...............................................................................
    Parameters:  
     * $multiArray - Array mit Duplikaten
    --------------------------------------------------------------------------------
    Returns: 
        $uniqueArray - bereinigtes array
    --------------------------------------------------------------------------------*/
    //Status: checked 29.6.2018
    /* **************************************************************************** */
    Protected function array_multi_unique($multiArray) {
        $uniqueArray = array();

        foreach ($multiArray as $subArray) { //alle Array-Elemente durchgehen
            if (!in_array($subArray, $uniqueArray)) { //prüfen, ob Element bereits im Unique-Array
                $uniqueArray[] = $subArray; //Element hinzufügen, wenn noch nicht drin
            }
        }
        return $uniqueArray;
    }


    //*****************************************************************************
    /* Function: directory($Directory)
    ...............................................................................
    nur ein Verzeichnis und keine komplette URL als ServerContentDirectory,
    RenderingControlURL und ControlURL wird übergeben
    ...............................................................................
    Parameters:  
     * $Directory - ADirectory als string
    --------------------------------------------------------------------------------
    Returns: 
        $parsed_Directory - bereinigtes Directory
    --------------------------------------------------------------------------------*/
    //Status: checked 29.6.2018
    /* **************************************************************************** */
    Protected function directory($Directory) {
        if (stristr($Directory, "http") == true) { //komplette URL vorhanden ?
            $vars1 = explode("//", $Directory, 2); //cut nach http
            $cutted1 = $vars1[0];
            $cutted2 = $vars1[1];
            $vars2 = explode("/", $cutted2, 2); //cut nach Port (", 2" --> 2 Teile)
            $cutted3 = $vars2[0];
            $Directory = (string)$vars2[1];
        }
        if (strpos($Directory, "/") == 0) { //prüfen, ob erstes Zeichen ein "/" ist
            $raw_Directory = trim($Directory, "/");
        } else {
            $raw_Directory = $Directory;
        }
        $parsed_Directory = ("/" . $raw_Directory);
        //$this->SendDebug('Directory bereinigter Wert: ', $parsed_Directory , 0);
        return $parsed_Directory;
    }


    //*****************************************************************************
    /* Function: create_UPNP_Device_Array($Device_SSDPArray)
    ...............................................................................
    erzeugt ein Array aus allen gefundenen Upnp clients
    ...............................................................................
    Parameters:  
     * $Device_SSDPArray - Array der gefundenen Clients
    --------------------------------------------------------------------------------
    Returns: 
        $SaveArray - abgespeichertes Upnp Client Array
    --------------------------------------------------------------------------------*/
    //Status: checked 29.6.2018
    /* **************************************************************************** */
    Protected function create_UPNP_Device_Array($Device_SSDPArray) {

     

        for ($i = 0, $size = count($Device_SSDPArray); $i < $size; $i++) {

            $DeviceDescription = $Device_SSDPArray[$i]['LOCATION'];

            //$this->stdout(print_r($DeviceDescription));
            //$this->SendDebug('create_UPNP_Device_Array', 'DeviceDescription:' . $DeviceDescription, 0);
            //Rootverzeichnis/IP/Port

            $vars1 = explode("//", $DeviceDescription, 2); //cut nach http
            $cutted1 = $vars1[0];
            $cutted2 = $vars1[1];
            $vars2 = explode("/", $cutted2, 2);     //cut nach Port
            $cutted3 = $vars2[0];
            $cutted4 = $vars2[1];
            $vars3 = explode(":", $cutted3, 2);     //IP und Port
            $DeviceIP = $vars3[0];
            $DevicePort = $vars3[1];

            $root = "http://" . "$cutted3" . "/";
            /* ///////////////////////////////////////////////////////////////////////////
              nur auswerten, wenn enicht 121.0.0.1
              -----------------------------------------------------------------------------
              / *///////////////////////////////////////////////////////////////////////////
            if ($DeviceIP != "127.0.0.1") {
                /* ///////////////////////////////////////////////////////////////////////////
                  //nur auswerten, wenn erreichbar und auslesbar
                  //-----------------------------------------------------------------------------
                  //nach einem SSDP-Request immer erreichbar,
                  //somit $Device_Party_Array[$i]['FriendlyName'] nur bei Wiederholung aufgerufen
                  /////////////////////////////////////////////////////////////////////////// */

                if ($this->ping($DeviceIP, $DevicePort, $timeout = "1") == "false") {
                    /* ////////////////////////////////////////////////////////////////////////
                      nicht erreichbares Device:
                      FriendlyName aus DeviceArray (vorher geladen) sowie Image "not connected"
                      / *////////////////////////////////////////////////////////////////////////

                    $DeviceDescription = "";
                    $root = "";
                    $DeviceIP = "";
                    $DevicePort = "";
                    $modelName = "";
                    $UDN = "";



                    $iconurl = "";
                    $DeviceControlServiceType = "";
                    $DeviceControlURL = "";
                    $DeviceRenderingServiceType = "";
                    $DeviceRenderingControlURL = "";
                    $DeviceActiveIcon = ("image/not connected.png");


                    $this->SendDebug('create_UPNP_Device_Array', 'Device nicht erreichbar !', 0);
                } else {//2
                    $ctx = stream_context_create(array('http' => array('timeout' => 1000)));

                    if (!file_get_contents("$DeviceDescription", -1, $ctx)) {

                        $this->SendDebug('create_UPNP_Device_Array', 'DeviceDescription nicht ladbar !', 0);
                    } else {

                        $this->SendDebug('create_UPNP_Device_Array', 'Device erreichbar !', 0);

                        $this->SendDebug('create_UPNP_Device_Array', 'DeviceDescription ladbar !', 0);
                        /* /////////////////////////////////////////////////////////////////////
                          erreichbares Device:
                          Description des Gerätes abrufen und auswerten
                          / */////////////////////////////////////////////////////////////////////

                        $xml = @file_get_contents("$DeviceDescription", -1);
                        $xml = str_replace("&", "&amp;", $xml);
                        //Enable (TRUE) user error handling                        
                        libxml_use_internal_errors(true);

                        $xmldesc = new SimpleXMLElement($xml);

                        //Modelname lesen
                        $modelName = (string) $xmldesc->device->modelName;
                        //$this->SendDebug('Device Desription', 'Model Name: ' . $modelName, 0);
                        //UDN lesen
                        $UDN = (string) $xmldesc->device->UDN;
                        //$this->SendDebug('device Desription', 'UDN:' . $UDN, 0);
                        //Name 
                        $friendlyName_raw = $xmldesc->device->friendlyName;


                        if (stripos($friendlyName_raw, " ")) { //wenn Leerzeichen nur ersten Teil
                            $var = explode(" ", $friendlyName_raw);
                            $friendlyName_raw = $var[0];
                        }
                        if (stripos($friendlyName_raw, "/")) { //wenn "/" nur ersten Teil
                            $var = explode(" ", $friendlyName_raw);
                            $friendlyName_raw = $var[0];
                        }


                        $friendlyName = substr("$friendlyName_raw", 0, 20);

                        if ($modelName == "Sonos Play:1") {
                            $friendlyName = "SonosK";
                        }

                        if ($modelName == "Sonos Play:3") {
                            $friendlyName = "SonosSZ";
                        }
                        //$this->SendDebug('device Desription', 'friendlyName:' . $friendlyName, 0);
                        /* /////////////////////////////////////////////////////////////////////
                          verfügbare Icons ermitteln
                          / */////////////////////////////////////////////////////////////////////

                        if (isset($xmldesc->device->iconList)) {
                            $icons = array();
                            //Icons auslesen und nach Grösse 120x120 suchen
                            foreach ($xmldesc->device->iconList->icon as $icon) {
                                //Icons durchsuchen nach 1. Größe 120x120
                                if ($icon->height == "120") {
                                    $icons[] = $icon->url;

                                    //PNG Icon mit Größe 120x120 suchen
                                    if (preg_grep('/png/i', $icons)) {
                                        $iconpng = preg_grep('/png/i', $icons);
                                        $icon120 = end($iconpng);
                                    }
                                    //wenn nicht vorhanden letztes mit Größe 120x120 übernehmen
                                    else {
                                        $icon120 = end($icons);
                                    }
                                } else {
                                    $icons[] = $icon->url;
                                    $icon120 = $icons[0];
                                }
                            }

                            //wenn komplette URL bereits enthalten
                            if (stristr($icon120, "http")) {
                                $iconurl = (string) $icon120;
                            } else { //sonst $root davor setzen und vorher auf"/" prüfen
                                if (strpos($icon120, "/") == 0) { //prüfen, ob erstes Zeichen ein "/" ist
                                    $iconurl = (string) $root . (trim(end($icon120), "/"));
                                } else {
                                    $iconurl = (string) $root . $icon120;
                                }
                            }
                        } else { //wenn kein icon vorhanden Dummy nehmen
                            $iconurl = ("image/UPNP.png");
                        }

  
                        /* /////////////////////////////////////////////////////////////////////
                          Services von SONOS Player auslesen und auf AV Transport und RenderingControl beschränken
                          / *///////////////////////////////////////////////////////////////////// 
                        if (isset($xmldesc->device->deviceList->device)) {
                            foreach ($xmldesc->device->deviceList->device as $device) {
                                $deviceType = (string) $device->deviceType;
                                if($deviceType == 'urn:schemas-upnp-org:device:MediaRenderer:1'){
                                    $DeviceControlServiceType = "";
                                    $DeviceControlURL = "";
                                    $DeviceRenderingServiceType = "";
                                    $DeviceRenderingControlURL = "";
                                            foreach ($device->serviceList->service as $service) {
                                                $serviceType = (string) $service->serviceType;
                                                $this->SendDebug('Device Desription', 'service Type: ' . $serviceType, 0);
                                                if (stristr($serviceType, "urn:schemas-upnp-org:service:AVTransport")){
                                                    $DeviceControlServiceType = (string) $service->serviceType;
                                                    $Directory = (string)$service->controlURL;
                                                    $DeviceControlURL = $this->directory($Directory);  
                                                    //$this->SendDebug('DeviceControlURL: - ', $DeviceControlURL, 0);
                                                } 

                                                if (stristr($serviceType, "urn:schemas-upnp-org:service:RenderingControl")){
                                                    $DeviceRenderingServiceType = (string) $service->serviceType;
                                                    $Directory = (string)$service->controlURL;
                                                    $DeviceRenderingControlURL = $this->directory($Directory);
                                                    //$this->SendDebug('$DeviceRenderingControlURL: - ', $Directory , 0);
                                                } 
                                            }
                                            
                                }            
                            }                
                        }

                        /* /////////////////////////////////////////////////////////////////////
                          Services auslesen und auf AV Transport und RenderingControl beschränken
                          / */////////////////////////////////////////////////////////////////////
 
                            else if (isset($xmldesc->device->serviceList->service)) {
                                        $DeviceControlServiceType = "";
                                        $DeviceControlURL = "";
                                        $DeviceRenderingServiceType = "";
                                        $DeviceRenderingControlURL = "";
                                foreach ($xmldesc->device->serviceList->service as $service) {
                                    $serviceType = (string) $service->serviceType;
                                    $this->SendDebug('Device Desription', 'service Type: ' . $serviceType, 0);
                                    if (stristr($serviceType, "urn:schemas-upnp-org:service:AVTransport")){
                                        $DeviceControlServiceType = (string) $service->serviceType;
                                        $Directory = (string)$service->controlURL;
                                        $DeviceControlURL = $this->directory($Directory);  
                                        //$this->SendDebug('DeviceControlURL: - ', $DeviceControlURL, 0);
                                    } 

                                    if (stristr($serviceType, "urn:schemas-upnp-org:service:RenderingControl")){
                                        $DeviceRenderingServiceType = (string) $service->serviceType;
                                        $Directory = (string)$service->controlURL;
                                        $DeviceRenderingControlURL = $this->directory($Directory);
                                        //$this->SendDebug('$DeviceRenderingControlURL: - ', $Directory , 0);
                                    } 
                                }
                            }
                        
                        
                        
                    }
                }//2
                // alle Lokalen Devices ausschliesen
                if ($DeviceIP != "127.0.0.1") {
                    //Ausgangszustand nicht selektiert, also ohne Icon-Haken
                    $DeviceActiveIcon = "";

                    //DeviceArray erstellen
                    $this->SendDebug('Client: ',$friendlyName. ' found', 0);
                    $Device_Array[$i]['DeviceDescription'] = $DeviceDescription;
                    $this->SendDebug('Client-DeviceDescription', $DeviceDescription, 0);
                    $Device_Array[$i]['Root'] = $root;
                    $this->SendDebug('Client-Root', $root, 0);
                    $Device_Array[$i]['DeviceIP'] = $DeviceIP;
                    $this->SendDebug('Client-DeviceIP', $DeviceIP, 0);
                    $Device_Array[$i]['DevicePort'] = $DevicePort;
                    $this->SendDebug('Client-DevicePort', $DevicePort, 0);
                    $Device_Array[$i]['ModelName'] = $modelName;
                    $this->SendDebug('Client-ModelName', $modelName, 0);
                    $Device_Array[$i]['UDN'] = $UDN;
                    $this->SendDebug('Client-UDN', $UDN, 0);
                    $Device_Array[$i]['FriendlyName'] = $friendlyName;
                    $this->SendDebug('Client-FriendlyName', $friendlyName, 0);
                    $Device_Array[$i]['IconURL'] = $iconurl;
                    $this->SendDebug('Client-IconURL', $iconurl, 0);
                    $Device_Array[$i]['DeviceControlServiceType'] = $DeviceControlServiceType;
                    $this->SendDebug('Client-DeviceControlServiceType', $DeviceControlServiceType, 0);
                    $Device_Array[$i]['DeviceControlURL'] = $DeviceControlURL;
                    $this->SendDebug('Client-DeviceControlURL', $DeviceControlURL, 0);
                    $Device_Array[$i]['DeviceRenderingServiceType'] = $DeviceRenderingServiceType;
                    $this->SendDebug('Client-DeviceRenderingServiceType', $DeviceRenderingServiceType, 0);
                    $Device_Array[$i]['DeviceRenderingControlURL'] = $DeviceRenderingControlURL;
                    $this->SendDebug('Client-DeviceRenderingControlURL', $DeviceRenderingControlURL, 0);
                    $Device_Array[$i]['DeviceActiveIcon'] = $DeviceActiveIcon;
                    $this->SendDebug('Client-DeviceActiveIcon', $DeviceActiveIcon, 0);
                    
                }
            }
        }//1
        // Array von Doppelten Einträgen bereinigen
        $Clean_Device_Array = $this->array_multi_unique($Device_Array);
       
   
        
        return $Clean_Device_Array;
    }

   //*****************************************************************************
    /* Function: create_UPNP_Server_Array($Server_SSDPArray)
    ...............................................................................
    erzeugt ein Array aus allen gefundenen Upnp Server
    ...............................................................................
    Parameters:  
     * $Server_SSDPArray - Array der gefundenen Server
    --------------------------------------------------------------------------------
    Returns: 
        $SaveArray - abgespeichertes Upnp Server Array
    --------------------------------------------------------------------------------*/
    //Status: checked 29.6.2018
    /* **************************************************************************** */
    Protected function create_UPNP_Server_Array($Server_SSDPArray) {
        /* //////////////////////////////////////////////////////////////////////////////
          Auslesen des Arrays und Abfrage der Descriptions, wenn erreichbar
          / *//////////////////////////////////////////////////////////////////////////////

  
        //$Server_Array = array();

        $zaehler = 0;
        for ($i = 0, $size = count($Server_SSDPArray); $i < $size; $i++) {
            $ServerDescription = $Server_SSDPArray[$i]['LOCATION'];


            $this->SendDebug('create_UPNP_Server_Array', 'ServerDescription' . $ServerDescription, 0);
            //Rootverzeichnis/IP/Port----------------------------------------------------

            $vars1 = explode("//", $ServerDescription, 2); //cut nach http
            $cutted1 = $vars1[0];
            $cutted2 = $vars1[1];
            $vars2 = explode("/", $cutted2, 2);     //cut nach Port
            $cutted3 = $vars2[0];
            $cutted4 = $vars2[1];
            $vars3 = explode(":", $cutted3, 2);     //IP und Port
            $ServerIP = $vars3[0];
            $ServerPort = $vars3[1];

            $root = "http://" . "$cutted3" . "/";

            if ($ServerIP != "127.0.0.1") {

                /* ///////////////////////////////////////////////////////////////////////////
                  nur auswerten, wenn erreichbar und auslesbar
                  -----------------------------------------------------------------------------
                  nach einem SSDP-Request immer erreichbar,
                  somit $Device_Party_Array[$i]['FriendlyName'] nur bei Wiederholung aufgerufen
                  / *///////////////////////////////////////////////////////////////////////////

                if ($this->ping($ServerIP, $ServerPort, $timeout = "1") == "false") {
                    /* ////////////////////////////////////////////////////////////////////////
                      nicht erreichbarer Server:
                      FriendlyName aus DeviceArray sowie Image "not connected"
                      / *////////////////////////////////////////////////////////////////////////

                    $ServerDescription = "";
                    $root = "";
                    $ServerIP = "";
                    $ServerPort = "";
                    $modelName = "";
                    $UDN = "";


                    $iconurl = "";
                    $ServerServiceType = "";
                    $ServerContentDirectory = "";
                    $ServerActiveIcon = ("image/not connected.png");
                    $this->SendDebug('create_UPNP_Server_Array', 'Server nicht erreichbar !', 0);
                } else {//2
                    $ctx = stream_context_create(array('http' => array('timeout' => 1000)));

                    if (!file_get_contents("$ServerDescription", -1, $ctx)) {

                        $this->SendDebug('create_UPNP_Server_Array', 'ServerDescription nicht ladbar !', 0);
                    } else {

                        $this->SendDebug('create_UPNP_Server_Array', 'Server erreichbar !', 0);

                        $this->SendDebug('create_UPNP_Server_Array', 'ServerDescription ladbar !', 0);
                        /* /////////////////////////////////////////////////////////////////////
                          erreichbarer Server:
                          Description des Server abrufen und auswerten
                          / */////////////////////////////////////////////////////////////////////

                        $xml = @file_get_contents("$ServerDescription", -1);
                        $xml = str_replace("&", "&amp;", $xml);
                        //print_r($xml);

                        libxml_use_internal_errors(true);

                        $xmldesc = new SimpleXMLElement($xml);

                        //Modelname lesen
                        $modelName = (string) $xmldesc->device->modelName;

                        //UDN lesen
                        $UDN = (string) $xmldesc->device->UDN;

                        //Name -> nur erste 10 Zeichen anzeigen, sonst passt es nicht in den Button
                        $friendlyName_raw = $xmldesc->device->friendlyName;

                        if (stripos($friendlyName_raw, " ")) { //wenn Leerzeichen nur ersten Teil
                            $var = explode(" ", $friendlyName_raw);
                            $friendlyName_raw = $var[0];
                        }
                        if (stripos($friendlyName_raw, "/")) { //wenn "/" nur ersten Teil
                            $var = explode(" ", $friendlyName_raw);
                            $friendlyName_raw = $var[0];
                        }

                        $friendlyName = substr("$friendlyName_raw", 0, 10);

                        /* /////////////////////////////////////////////////////////////////////
                          verfügbare Icons ermitteln
                          / */////////////////////////////////////////////////////////////////////

                        if (isset($xmldesc->device->iconList)) {
                            $icons = array();
                            //Icons auslesen und nach Grösse 120x120 suchen
                            foreach ($xmldesc->device->iconList->icon as $icon) {
                                //Icons durchsuchen nach 1. Größe 120x120
                                if ($icon->height == "120") {
                                    $icons[] = $icon->url;

                                    //PNG Icon mit Größe 120x120 suchen
                                    if (preg_grep('/png/i', $icons)) {
                                        $iconpng = preg_grep('/png/i', $icons);
                                        $icon120 = end($iconpng);
                                    }
                                    //wenn nicht vorhanden letztes mit Größe 120x120 übernehmen
                                    else {
                                        $icon120 = end($icons);
                                    }
                                } else {
                                    $icons[] = $icon->url;
                                    $icon120 = $icons[0];
                                }
                            }

                            //wenn komplette URL bereits enthalten
                            if (stristr($icon120, "http")) {
                                $iconurl = (string) $icon120;
                            } else { //sonst $root davor setzen und vorher auf"/" prüfen
                                if (strpos($icon120, "/") == 0) { //prüfen, ob erstes Zeichen ein "/" ist
                                    $iconurl = (string) $root . (trim(end($icon120), "/"));
                                } else {
                                    $iconurl = (string) $root . $icon120;
                                }
                            }
                        } else { //wenn kein icon vorhanden Dummy nehmen
                            $iconurl = ("image/UPNP.png");
                        }

                        /* /////////////////////////////////////////////////////////////////////
                          verfügbare Services
                          / */////////////////////////////////////////////////////////////////////
                        //Services auslesen und auf ContentDirectory beschränken------------------
                        if (isset($xmldesc->device->serviceList->service)) {
                            foreach ($xmldesc->device->serviceList->service as $service) {
                                $serviceType = $service->serviceType;

                                if (stristr($serviceType, "urn:schemas-upnp-org:service:ContentDirectory")) {
                                    $ServerServiceType = (string) $service->serviceType;
                                    $Directory = (string)$service->controlURL;
                                    $ServerContentDirectory = $this->directory($Directory);
                                }
                            }
                        }
                    }
                }//2
                //Ausgangszustand nicht selektiert, also ohne Icon-Haken
                $ServerActiveIcon = "";
                
                //ServerArray erstellen, dabei Sonos ausblenden
                if(substr($modelName, 0, 5) != "Sonos"){
                    $this->SendDebug('Server: ',$friendlyName. ' found', 0);
                    $Server_Array[$zaehler]['ServerDescription'] = $ServerDescription;
                    $this->SendDebug('Server-Description', $ServerDescription, 0);
                    $Server_Array[$zaehler]['Root'] = $root;
                    $this->SendDebug('Server-Root', $root, 0);
                    $Server_Array[$zaehler]['ServerIP'] = $ServerIP;
                    $this->SendDebug('Server-IP', $ServerIP, 0);
                    $Server_Array[$zaehler]['ServerPort'] = $ServerPort;
                    $this->SendDebug('Server-Port', $ServerPort, 0);
                    $Server_Array[$zaehler]['ModelName'] = $modelName;
                    $this->SendDebug('Server-ModelName', $modelName, 0);
                    $Server_Array[$zaehler]['UDN'] = $UDN;
                    $this->SendDebug('Server-UDN', $UDN, 0);
                    $Server_Array[$zaehler]['FriendlyName'] = $friendlyName;
                    $this->SendDebug('Server-FriendlyName', $friendlyName, 0);
                    $Server_Array[$zaehler]['IconURL'] = $iconurl;
                    $this->SendDebug('Server-IconURL', $iconurl, 0);
                    $Server_Array[$zaehler]['ServerServiceType'] = $ServerServiceType;
                    $this->SendDebug('Server-ServiceType', $ServerServiceType, 0);
                    $Server_Array[$zaehler]['ServerContentDirectory'] = $ServerContentDirectory;
                    $this->SendDebug('Server-ContentDirectory', $ServerContentDirectory, 0);
                    $Server_Array[$zaehler]['ServerActiveIcon'] = $ServerActiveIcon;
                    $this->SendDebug('Server-ActiveIcon', $ServerActiveIcon, 0);
                    $zaehler = $zaehler + 1;
                }
            }
        }//1
        // Array von Doppelten Einträgen bereinigen
        $Clean_ServerArray = $this->array_multi_unique($Server_Array);
        $SaveArray = json_encode($Clean_ServerArray);
  
        return $SaveArray;
    }

	//*****************************************************************************
	/* Function: ping($IP, $Port, $timeout)
        ...............................................................................
        Ping
        ...............................................................................
        Parameters: 
            *  $IP - IP Adresse
            *  $Port - Port
            *  $timeout - timeout Zeit in ms
 	--------------------------------------------------------------------------------
	Returns: 
        -----------------------------------------------------------------------------
        Status: 
        *****************************************************************************/
        Protected function ping($IP, $Port, $timeout){
            $fsock = @fsockopen($IP, $Port, $errno, $errstr, $timeout);
            //socket_set_timeout($fsock, $timeout);
            if ( ! $fsock ){
                $this->SendDebug('Send', $IP.'ist nicht erreichbar!', 0);
                return ("false");
            }
            else{
                $this->SendDebug('Send', $IP.'ist erreichbar!', 0);
                return ("true");
            }
        }
}
