<?php
//zugehoerige Unter-Klassen    
require_once(__DIR__ . "/DenonCeol_Interface.php");
//require_once(__DIR__ . "/../libs/XML2Array.php");
require_once(__DIR__ . "/../libs/NetworkTraits2.php");
require_once(__DIR__ . "/DiscoverTrait.php");

    // Klassendefinition
    class DenonCeol extends IPSModule {
        //externe Klasse einbinden - ueberlagern mit TRAIT
        use CEOLupnp;
        //use XML2Array;
        use MyDebugHelper2;
        use DiscoveryServerTrait;              
        
        // Der Konstruktor des Moduls
        // Überschreibt den Standard Kontruktor von IPS
        public function __construct($InstanceID) {
            // Diese Zeile nicht löschen
            parent::__construct($InstanceID);
            
                        
           
        }
        
        // Create() wird einmalig beim Erstellen einer neuen Instanz ausgeführt
        // Überschreibt die interne IPS_Create($id) Funktion
        public function Create() {
            // Diese Zeile nicht löschen.
            parent::Create();
            
            // Variable aus dem Instanz Formular registrieren (zugänglich zu machen)
            $this->RegisterPropertyBoolean("active", false);
            $this->RegisterPropertyBoolean("LastFM", false);
            $this->RegisterPropertyString("IPAddress", "");
            $this->RegisterPropertyInteger("UpdateInterval", 5000);
           
            // Register Profiles
            $this->RegisterProfiles();

            //Status Variable anlegen
            $variablenID = $this->RegisterVariableString("CeolSrcName", "Source Name");
            IPS_SetInfo ($variablenID, "WSS"); 
            $variablenID = $this->RegisterVariableInteger("CeolSource", "Source", "");
            IPS_SetInfo ($variablenID, "WSS"); 
            $variablenID = $this->RegisterVariableBoolean("CeolPower", "Power");
            IPS_SetInfo ($variablenID, "WSS"); 
            $variablenID = $this->RegisterVariableInteger("CeolVolume", "Volume", "");
            IPS_SetInfo ($variablenID, "WSS"); 
            $variablenID = $this->RegisterVariableInteger("CeolVol", "Vol", "");
            IPS_SetInfo ($variablenID, "WSS"); 
            $variablenID = $this->RegisterVariableBoolean("CeolMute", "Mute");
            IPS_SetInfo ($variablenID, "WSS"); 
            $variablenID = $this->RegisterVariableString("CeolSZ1", "Line1");
            IPS_SetInfo ($variablenID, "WSS"); 
            $variablenID = $this->RegisterVariableString("CeolSZ2", "Line2");
            IPS_SetInfo ($variablenID, "WSS"); 
            $variablenID = $this->RegisterVariableString("CeolSZ3", "Line3");
            IPS_SetInfo ($variablenID, "WSS"); 
            $variablenID = $this->RegisterVariableString("CeolSZ4", "Line4"); 
            IPS_SetInfo ($variablenID, "WSS"); 
            $variablenID = $this->RegisterVariableString("CeolSZ5", "Line5");
            IPS_SetInfo ($variablenID, "WSS"); 
            $variablenID = $this->RegisterVariableString("CeolSZ6", "Line6");
            IPS_SetInfo ($variablenID, "WSS"); 
            $variablenID = $this->RegisterVariableString("CeolSZ7", "Line7");
            IPS_SetInfo ($variablenID, "WSS"); 
            $variablenID = $this->RegisterVariableString("CeolSZ8", "Line8"); 
            IPS_SetInfo ($variablenID, "WSS"); 
            $variablenID = $this->RegisterVariableInteger("CeolFavChannel", "Sender", "");
            IPS_SetInfo ($variablenID, "WSS"); 
            $variablenID = $this->RegisterVariableString("CeolArtPicUrl", "ArtPicUrl"); 
            IPS_SetInfo ($variablenID, "WSS"); 
            
                
            //UPNP Variable
            $this->RegisterVariableString("Ceol_ServerArray", "Server:Array");
                
            $variablenID = $this->RegisterVariableString("Ceol_ServerContentDirectory", "Server:ContentDirectory");
            IPS_SetInfo ($variablenID, "WSS"); 
            $variablenID = $this->RegisterVariableString("Ceol_ServerIcon", "Server:Icon");
            IPS_SetInfo ($variablenID, "WSS"); 
            $variablenID = $this->RegisterVariableString("Ceol_ServerIP", "Server:IP");
            IPS_SetInfo ($variablenID, "WSS"); 
            $variablenID = $this->RegisterVariableInteger("Ceol_ServerKey", "Server:Key", "");
            IPS_SetInfo ($variablenID, "WSS"); 
            $variablenID = $this->RegisterVariableString("Ceol_ServerName", "Server:Name");
            IPS_SetInfo ($variablenID, "WSS"); 
            $variablenID = $this->RegisterVariableString("Ceol_ServerPort", "Server:Port");
            IPS_SetInfo ($variablenID, "WSS"); 

            
            $variablenID = $this->RegisterVariableString("Ceol_Artist", "DIDL_Artist [dc:creator]");
            IPS_SetInfo ($variablenID, "WSS"); 
            $variablenID = $this->RegisterVariableString("Ceol_Album", "DIDL_Album [upnp:album]");
            IPS_SetInfo ($variablenID, "WSS"); 
            $variablenID = $this->RegisterVariableString("Ceol_Title", "DIDL_Titel [dc:title]");
            IPS_SetInfo ($variablenID, "WSS"); 
            $variablenID = $this->RegisterVariableString("Ceol_Actor", "DIDL_Actor [upnp:actor]");
            IPS_SetInfo ($variablenID, "WSS"); 
            $variablenID = $this->RegisterVariableString("Ceol_AlbumArtUri", "DIDL_AlbumArtURI [upnp:albumArtURI]");
            IPS_SetInfo ($variablenID, "WSS"); 
            $variablenID = $this->RegisterVariableString("Ceol_Genre", "DIDL_Genre [upnp:genre]");
            IPS_SetInfo ($variablenID, "WSS"); 
            $variablenID = $this->RegisterVariableString("Ceol_Date", "DIDL_Date [dc:date]");
            IPS_SetInfo ($variablenID, "WSS"); 
            
            $variablenID = $this->RegisterVariableInteger("Ceol_PlayMode", "PlayMode", "UPNP_Playmode");
            IPS_SetInfo ($variablenID, "WSS"); 

            $variablenID = $this->RegisterVariableInteger("Ceol_PlayStatus", "PlayStatus", "Media_Status");
            IPS_SetInfo ($variablenID, "WSS"); 

            $variablenID = $this->RegisterVariableInteger("Ceol_NoTracks", "No of tracks", "");
            IPS_SetInfo ($variablenID, "WSS"); 
            $variablenID = $this->RegisterVariableString("Ceol_PlaylistName", "PlaylistName");
            IPS_SetInfo ($variablenID, "WSS"); 
            $this->RegisterVariableString("Ceol_Playlist_XML", "Playlist_XML");  
                
            $variablenID = $this->RegisterVariableInteger("Ceol_Progress", "Progress", "UPNP_Progress");
            IPS_SetInfo ($variablenID, "WSS"); 
            $variablenID = $this->RegisterVariableInteger("Ceol_Track", "Pos:Track", "");
            IPS_SetInfo ($variablenID, "WSS"); 
            $variablenID = $this->RegisterVariableString("Ceol_Transport_Status", "Pos:Transport_Status");
            IPS_SetInfo ($variablenID, "WSS"); 
            $variablenID = $this->RegisterVariableString("Ceol_RelTime", "RelTime");
            IPS_SetInfo ($variablenID, "WSS"); 
            $variablenID = $this->RegisterVariableString("Ceol_TrackDuration", "TrackDuration");
            IPS_SetInfo ($variablenID, "WSS"); 
            


            // Aktiviert die Standardaktion der Statusvariable im Webfront
            $this->EnableAction("CeolPower");
            IPS_SetVariableCustomProfile($this->GetIDForIdent("CeolPower"), "~Switch");
            $this->EnableAction("CeolMute");
            IPS_SetVariableCustomProfile($this->GetIDForIdent("CeolMute"), "~Switch");
            $this->EnableAction("CeolSource");
            IPS_SetVariableCustomProfile($this->GetIDForIdent("CeolSource"), "DenonCEOL_Source");
            $this->EnableAction("CeolVolume");
            IPS_SetVariableCustomProfile($this->GetIDForIdent("CeolVolume"), "DenonCEOL_Volume");
            $this->EnableAction("CeolVol");
            IPS_SetVariableCustomProfile($this->GetIDForIdent("CeolVol"), "DenonCEOL_Vol");
            $this->EnableAction("CeolFavChannel");
            IPS_SetVariableCustomProfile($this->GetIDForIdent("CeolFavChannel"), "DenonCeol_Channel");
            $this->EnableAction("Ceol_PlayMode");
            IPS_SetVariableCustomProfile($this->GetIDForIdent("Ceol_PlayMode"), "UPNP_Playmode");
            $this->EnableAction("Ceol_PlayStatus");
            IPS_SetVariableCustomProfile($this->GetIDForIdent("Ceol_PlayStatus"), "Media_Status");

            // Objekte unsichbar machen in webfront
            IPS_SetHidden($this->GetIDForIdent("Ceol_ServerArray"), true); //Objekt verstecken
            IPS_SetHidden($this->GetIDForIdent("Ceol_ServerContentDirectory"), true); //Objekt verstecken
            IPS_SetHidden($this->GetIDForIdent("Ceol_ServerKey"), true); //Objekt verstecken
            IPS_SetHidden($this->GetIDForIdent("Ceol_ServerArray"), true); //Objekt verstecken
            IPS_SetHidden($this->GetIDForIdent("Ceol_Playlist_XML"), true); //Objekt verstecken
            
            
            
            
            //
            // Timer erstellen
            $this->RegisterTimer("Update", $this->ReadPropertyInteger("UpdateInterval"), 'CEOL_update($_IPS[\'TARGET\']);');
            // Progress Timer erstellen
            $this->RegisterTimer("Ceol_PlayInfo", 0,  'CEOL_GetPosInfo(' . $this->InstanceID . ');');

        }
        
        // ApplyChanges() wird einmalig aufgerufen beim Erstellen einer neuen Instanz und
        // bei Änderungen der Formular Parameter (form.json) (nach Übernahme Bestätigung)
        // Überschreibt die intere IPS_ApplyChanges($id) Funktion
        public function ApplyChanges() {
            // Diese Zeile nicht löschen
            parent::ApplyChanges();



            if($this->ReadPropertyBoolean("active")){
                $this->SetTimerInterval("Update", $this->ReadPropertyInteger("UpdateInterval"));
                $this->CeolInit();
            }
            else {
                $this->SetTimerInterval("Update", 0);
                $this->SetTimerInterval("Ceol_PlayInfo", 0);
            }
        }
 
        public function RequestAction($Ident, $Value) {
            switch($Ident) {
                case "CeolPower":
                    //Hier würde normalerweise eine Aktion z.B. das Schalten ausgeführt werden
                    //Ausgaben über 'echo' werden an die Visualisierung zurückgeleitet
                
                    //$this->SetPower($Value);
                    //Neuen Wert in die Statusvariable schreiben
                    //SetValue($this->GetIDForIdent($Ident), $Value);
                        if($Value){
                            $host = $this->ReadPropertyString('IPAddress');
                            $url = "http://$host:80/goform/formiPhoneAppPower.xml";
                            $cmd = '1+PowerOn';
                            $xml = $this->curl_get($url, $cmd);
                            $this->SetMute_AV('0');
                            $this->setvalue("CeolMute", false);
                        }
                        else{
                            $host = $this->ReadPropertyString('IPAddress');
                            $url = "http://$host:80/goform/formiPhoneAppPower.xml";
                            $cmd = '1+PowerStandby';
                            $xml = $this->curl_get($url, $cmd);
                        }
                    break;
                case "CeolSrcName":
 
                    break;
                case "CeolSource":
                    $this->SelectSource($value);
                    $this->SendDebug("Source: ", $value, 0);
                    break;
                case "CeolVolume":
                    break;
                case "CeolVol":
                    $curVol = $this->getvalue("CeolVol");
                    if($Value === $curVol + 10){
                        $this->IncVolume();
                    }
                    elseif($Value === $curVol - 10){
                        $this->DecVolume();
                    }
                    else{
                        $this->SetVolumeDB($Value);
                    }
                    //$this->setvalue("CeolVol", $Value);

                    break;
                case "CeolMute":
                        if($Value){
                            $this->SetMute_AV('1');
                            $this->setvalue("CeolMute", true);
                        }
                        else{
                            $this->SetMute_AV('0');
                            $this->setvalue("CeolMute", false);
                        }
                    break;
                case "CeolFavChannel":
                    $this->setValue("CeolFavChannel", $Value);
                    break;
                default:
                    throw new Exception("Invalid Ident");
            }

        } 
        
        
        
        
        
        
        
        
        
        
        /**
        * Die folgenden Funktionen stehen automatisch zur Verfügung, wenn das Modul über die "Module Control" eingefügt wurden.
        * Die Funktionen werden, mit dem selbst eingerichteten Prefix, in PHP und JSON-RPC wie folgt zur Verfügung gestellt:
        *
        * CEOL_XYFunktion($id);
        *
        */

        
        private function CeolInit(){
            
            $this->ip = $this->ReadPropertyString('IPAddress');
        }
        
	/*//////////////////////////////////////////////////////////////////////////////
	Funktion:  update()
	...............................................................................
	Funktion wird über Timer alle x Sekunden gestartet
         *  call SubFunctions:  $this->Get_MainZone_Status()
         *                      $this->get_audio_status(
	...............................................................................
	Parameter:  none
	--------------------------------------------------------------------------------
	SetVariable:   CeolMute
                       CeolPower
                       CeolVolume
                       CeolSource
	--------------------------------------------------------------------------------
	return: none  
	--------------------------------------------------------------------------------
	Status: checked 2018-06-03
	//////////////////////////////////////////////////////////////////////////////*/       
        public function update() {
            $ip = $this->ReadPropertyString('IPAddress');
            $alive = Sys_Ping($ip, 1000);
            if ($alive){
                //Netzwerk Verbindungs Störung wurde behoben
                if ($connection = false){
                    $connection = true;
                    $this->SetTimerInterval("Update", $this->ReadPropertyInteger("UpdateInterval"));
                }
                //MainZoneStatus auslesen   
                $output = $this->Get_MainZone_Status();
                $power = ($output['Power']['value']);
                $InputFuncSelect = ($output['InputFuncSelect']['value']);
                $MasterVolume = ($output['MasterVolume']['value']);
                $Mute = ($output['Mute']['value']);
                if ($power == 'ON'){
                        $_power = true;
                }
                else{
                        $_power = false;
                        $this->SetValue("CeolSZ2", 'Denon CEOL Picolo');
                        $this->SetValue("CeolSZ3", 'ausgeschaltet');
                        $this->SetTimerInterval("Ceol_PlayInfo", 0);
                }
                $this->SetValue("CeolPower", $_power);
                $this->SetValue("CeolVolume", $MasterVolume);
                $vol =  (intval($MasterVolume) + 78)*10;
                $this->SetValue("CeolVol", $vol);
                if ($Mute == 'off'){
                        $_mute = false;
                }
                else{
                        $_mute = true;
                }
                $this->SetValue("CeolMute", $_mute);
                //AudioStatus auslesen
                $output = $this->get_audio_status();		
                $sz1 = $output['szLine']['value'][0];
                $sz2 = utf8_decode($output['szLine']['value'][1]);
                if (empty($sz2)){$sz2 = '- - - -';}
                $sz3 = $output['szLine']['value'][2];
                if (empty($sz3)){$sz3 = '- - - -';}
                $sz4 = "";
                if(isset($output['szLine']['value'][3])){
                    $sz4 = $output['szLine']['value'][3];
                };
                $sz5 = "";
                if(isset($output['szLine']['value'][4])){
                    $sz5 = $output['szLine']['value'][4];
                };
                $sz6 = "";
                if(isset($output['szLine']['value'][5])){
                    $sz6 = $output['szLine']['value'][5];
                };
                $sz7 = "";
                if(isset($output['szLine']['value'][6])){
                    $sz7 = $output['szLine']['value'][6];
                };
                $sz8 = "";
                if(isset($output['szLine']['value'][7])){
                    $sz8 = $output['szLine']['value'][7];
                };
               
           
                $this->SetValue("CeolSZ1", $sz1);
                $this->SetValue("CeolSZ2", substr($sz2, 0,60));
                $this->SetValue("CeolSZ3", $sz3);
                $this->SendDebug("CeolSZ3: ", $sz3, 0);
                
                $this->SendDebug("CeolSZ4: ", $sz4, 0);
                $this->SetValue("CeolSZ4", $sz4);
                
                $this->SetValue("CeolSZ5", $sz5);
                $this->SendDebug("CeolSZ5: ", $sz5, 0);
                $this->SetValue("CeolSZ6", $sz6);
                $this->SendDebug("CeolSZ6: ", $sz6, 0);
                $this->SetValue("CeolSZ7", $sz7);
                $this->SendDebug("CeolSZ7: ", $sz7, 0);
                $this->SetValue("CeolSZ8", $sz8);
                $this->SendDebug("CeolSZ8e: ", $sz8, 0);
                
                
                $Source = $output['NetFuncSelect']['value'];
                $this->SendDebug("get Source: ", $Source, 0);
                switch ($Source){
                        case "IRADIO":
                            $this->SetValue("CeolSource", 0);
                            //ArtistPicture suchen
                            $artistTitel = $this->getvalue("CeolSZ2");
                            $dispLine2 = explode(" - ", $artistTitel);
                            $this->SendDebug("Line 2 array: ", $dispLine2, 0);
                            
                            if($this->ReadPropertyBoolean("LastFM")){
                                $size = 1;
                                $url = $this->getImageFromLastFM($dispLine2[0], $size);
                                    $Typ = gettype($url);
                                    $this->SendDebug("Variablbele Typ für URL ", $url, 0);
                            
                                if($url === false){
                                    $this->SendDebug("error ", "no Image found", 0);
                                }
                                else{
                                    $this->SetValue("CeolArtPicUrl", $url);
                                }
                            }else{
                                //take Radio station image as url
                                $StationNo = $this->getvalue("CeolFavChannel");
                                $Station = str_pad($StationNo, 4, 0, STR_PAD_LEFT); 
                                $url = "images/RadioStation/".$Station.".png";
                                $this->SetValue("Ceol_AlbumArtUri", $url);
                            }    
                            
                            if (isset($dispLine2[0])){
                                $this->setvalue("Ceol_Artist", $dispLine2[0]);
                            }
                            if (isset($dispLine2[1])){
                                $this->setvalue("Ceol_Title", $dispLine2[1]);
                            }   
                            
                            $this->setvalue("Ceol_Album", $this->getvalue("CeolSZ3"));
                        
                        break;	
                        case "SERVER":
                            $this->SetValue("CeolSource", 1);
                            $MediaNo = substr($this->getvalue("Ceol_PlaylistName"), -4);
                            if($this->getvalue("Ceol_Genre")=== 'AudioBook'){
                                $url = "AudioBooks/".$MediaNo.".jpg";
                                $this->SetValue("CeolArtPicUrl", $url);
                            }
                            else{
                                $url = "CDs/".$MediaNo.".jpg";
                                $this->SetValue("CeolArtPicUrl", $url);   
                            }
                        break;	
                        case "USB":
                            $this->SetValue("CeolSource", 2);
                        break;	
                        case "IPOD":
                            $this->SetValue("CeolSource", 3);
                        break;	
                        case "AUX_A":
                            $this->SetValue("CeolSource", 4);
                        break;	
                        case "AUX_D":
                            $this->SetValue("CeolSource", 5);
                        break;		
                }
            }
            else {
                //Keine Netzwerk-Verbindung zun Client
                $this->SendDebug("Meldung: ", "Keine Netzwerkverbindung zu Denon Ceol.", 0);
                $this->SetTimerInterval("Update", 10000);
                $this->SetTimerInterval("Ceol_PlayInfo", 0);
                $this->SetValue("CeolPower", false);
                $connection = false;
            }
        }
        
	/*//////////////////////////////////////////////////////////////////////////////	
	Befehl 	: Get_MainZone_Status()
	...............................................................................
	Liest MainZone Status aus
        HTTP Befehl:	http://192.168.178.29:80/goform/formMainZone_MainZoneXmlStatus.xml     
	...............................................................................				 
	setVariable: 
        --------------------------------------------------------------------------------
	Parameter:	host = String = Adresse von DENON CEOL
        --------------------------------------------------------------------------------
	Rückgabewert: 	$xml->array = output
	 	$output['item']['Zone']['value']		=>	MainZone
                $output['item']['Power']['value']		=>	ON // STANDBY
                $output['item']['Model']['value']		=>	
                $output['item']['MasterVolume']['value']	=>	NET
                $output['item']['MasterVolume']['value']	=>	-74.0
                $output['item']['Mute']['value']		=>	on // off
					output['item']['Mute']['value']
					---------------------------------------------
					<?xml version="1.0" encoding="UTF-8"?>
					<item>
						<Zone>
							<value>MainZone</value>
						</Zone>
						<Power>
							<value>ON</value>
						</Power>
						<Model>
							<value/>
						</Model>
						<InputFuncSelect>
							<value>NET</value>
						</InputFuncSelect>
						<MasterVolume>
							<value>-74.0</value>
						</MasterVolume>
						<Mute>
							<value>off</value>
						</Mute>
					</item>
	//////////////////////////////////////////////////////////////////////////////*/ 
	Public function Get_MainZone_Status(){
                $host = $this->ReadPropertyString('IPAddress');
		$url = "http://$host:80/goform/formMainZone_MainZoneXmlStatus.xml";
		$cmd = "";
		$xml = $this->curl_get($url, $cmd);
	//$this->SendDebug('Get_MainZone_Status: XMLcreateArray_IN:', $xml, 0);
        //$output = XML2Array::createArray($xml);
        $output = json_decode(json_encode((array)simplexml_load_string($xml)),true);
    //$this->SendDebug('Get_MainZone_Status: XMLcreateArray_OUT:', $output, 0);
                //$this->SendDebug("MainZone: ", $output, 0);
		//$status = ($output['item']['Power']['value']);
        $this->SendDebug("MainZoneStatus: ", $xml, 0);
		return $output;
	}	
        
	/*//////////////////////////////////////////////////////////////////////////////	
	Befehl 	:	get_audio_status()
	...............................................................................
	Liest Audio Status aus
        HTTP Befehl:	$url = "http://192.168.178.29:80/goform/formNetAudio_StatusXml.xml";     
	...............................................................................				 
	setVariable: 
        --------------------------------------------------------------------------------
        Parameter:	none
	--------------------------------------------------------------------------------
        Rückgabewert: 	$xml->array = output
		$output['item']['MasterVolume']['value']        =>	Mastervolume Status
	 	$output['item']['szLine']['value'][0]		=>	Display Line 1
		$output['item']['szLine']['value'][1]		=>	Display Line 2
		$output['item']['szLine']['value'][2]		=>	Display Line 3
		$output['item']['szLine']['value'][3]		=>	Display Line 4
		$output['item']['szLine']['value'][4]		=>	Display Line 5
		$output['item']['szLine']['value'][5]		=>	Display Line 6
		$output['item']['szLine']['value'][6]		=>	Display Line 7
		$output['item']['szLine']['value'][7]		=>	Display Line 8
		$output['item']['NetFuncSelect']['value']	=>	Selected Input 
		$output['item']['Mute']['value']			=>	Mute Status
	
	//////////////////////////////////////////////////////////////////////////////*/	
	Public function get_audio_status(){
                $host = $this->ReadPropertyString('IPAddress');
		$url = "http://$host:80/goform/formNetAudio_StatusXml.xml";
		$cmd = "";
		$xml = $this->curl_get($url, $cmd);
    //$this->SendDebug('get_audio_status:XMLcreateArray_IN: ', $xml, 0);
		//$this->SendDebug("AudioStatus: ", $xml, 0);
		//$output = XML2Array::createArray($xml);
        $output = json_decode(json_encode((array)simplexml_load_string($xml)),true);
    //$this->SendDebug('get_audio_status:XMLcreateArray_OUT: ', $output, 0);
		return $output;
	}	 

	/*//////////////////////////////////////////////////////////////////////////////
	Befehl: Navigate($Direction)
	...............................................................................
	Menü Navigation
        HTTP Befehl:  http://192.168.178.29:80/goform/formiPhoneAppNetAudioCommand.xml?CurLeft       
	Telnet Befehl: CurLeft // CurRight // CurUp // CurDown
	...............................................................................
	Parameter:  $value = left" // "right" // "up" // "down" // "ok"
	--------------------------------------------------------------------------------

	return:  
	--------------------------------------------------------------------------------
	Status:  
	//////////////////////////////////////////////////////////////////////////////*/
 	Public function Navigate(string $Direction){
		$host = $this->ReadPropertyString('IPAddress');
		$url = "http://$host:80/goform/formiPhoneAppNetAudioCommand.xml?";
		switch($Direction){
                    case 'left':
			$cmd = 'CurLeft';
			break;
                    case 'right':
			$cmd = 'CurRight';
			break;
                    case 'up':
			$cmd = 'CurUp';
			break;
                    case 'down':
			$cmd = 'CurDown';
			break;
                    case 'ok':
			$cmd = 'Enter';
			break;
		}
		
                $xml = $this->curl_get($url, $cmd);
		//$output = XML2Array::createArray($xml);
		return $xml;
	}	       

	/*//////////////////////////////////////////////////////////////////////////////
	Funktion SetPower($status)
	...............................................................................
	Denon Ceol Ein / Aus Schalten
	...............................................................................
	Parameter:  $status = "On" // "Standby" 
	--------------------------------------------------------------------------------
	HTTP-Command: http://192.168.178.29:80/goform/formiPhoneAppPower.xml?1+PowerOn
	--------------------------------------------------------------------------------
	return: $status = 'on' / 'Standby'   
	--------------------------------------------------------------------------------
	Status: checked 2018-06-03
	//////////////////////////////////////////////////////////////////////////////*/        
	Public function GetPower(){
            $PowerStatus =  $this->getvalue("CeolPower");
            return $PowerStatus;
        }        
        
	/*//////////////////////////////////////////////////////////////////////////////
	Funktion SetPower($status)
	...............................................................................
	Denon Ceol Ein / Aus Schalten
	...............................................................................
	Parameter:  $status = "On" // "Standby" 
	--------------------------------------------------------------------------------
	HTTP-Command: http://192.168.178.29:80/goform/formiPhoneAppPower.xml?1+PowerOn
	--------------------------------------------------------------------------------
	return: $status = 'on' / 'Standby'  // 0 / 1 
	--------------------------------------------------------------------------------
	Status: checked 2018-06-03
	//////////////////////////////////////////////////////////////////////////////*/        
	Public function SetPower(string $status){
		$host = $this->ReadPropertyString('IPAddress');
		$url = "http://$host:80/goform/formiPhoneAppPower.xml";
		if ($status == "On"){
                    $this->SendDebug('SetPower', 'Power: '.'einschalten', 0);
			$cmd = '1+PowerOn';
			$power=true;
		}
		if ($status == "Standby"){
			$cmd = '1+PowerStandby';
			$power=false;
		}
		$xml = $this->curl_get($url, $cmd);
    //$this->SendDebug('SetPower:XMLcreateArray_IN: ', $xml, 0);
		//$output = XML2Array::createArray($xml);
        $output = json_decode(json_encode((array)simplexml_load_string($xml)),true);
    //$this->SendDebug('SetPower:XMLcreateArray_IN: ', $output, 0);
 		//$status = ($output['item']['Power']['value']);
         $status = ($output['Power']['value']);
         $this->SetValue("CeolPower", $power);
		return $status;	
	}        

	/*//////////////////////////////////////////////////////////////////////////////
	Befehl: SelectSource($Source)
	...............................................................................
	Funktion schaltet die Eingangs Quelle des Denon CEOL um:
				 0 = iRadio
				 1 = MediaServer
				 2 = USB
				 3 = IPOD
				 4 = AUX_A
				 5 = AUX_D
	...............................................................................
	Parameter:  $Source = "Radio" // "Server" // "USB" // "IPOD" // "AUX_A" // "AUX_D"
	--------------------------------------------------------------------------------
	HTTP Command: http://192.168.178.29:80/goform/formiPhoneAppDirect.xml?SIIRADIO
	Telnet Befehle: SIIRADIO // SISERVER // SIUSB // SIIPOD // SIAUXA // SIAUXD
        --------------------------------------------------------------------------------
        return: $command / false  
	--------------------------------------------------------------------------------
	Status: checked 2018-06 -03
	//////////////////////////////////////////////////////////////////////////////*/	
	Public function SelectSource(int $Source){
            switch ($Source){
		case 0:
                    $command = "SIIRADIO";
                    $this->SetValue("CeolSource", 0);
		break;
		case 1:
                    $command = "SISERVER";
                    $this->SetValue("CeolSource", 1);
		break;	
		case 2:
                    $command = "SIUSB";
                    $this->SetValue("CeolSource", 2);
		break;
		case 3:
                    $command = "SIIPOD";
                    $this->SetValue("CeolSource", 3);
		break;			
		case 4:
                    $command = "SIAUXA";
                    $this->SetValue("CeolSource", 4);
		break;
		case 5:
                    $command = "SIAUXD";
                    $this->SetValue("CeolSource", 5);
		break;
		default:
                    $this->SendDebug("Error: ", "Falscher Parameter", 0);
		break;		
            }
            $this->send_cmd($command);
            return $command;
	}	

	/*//////////////////////////////////////////////////////////////////////////////
	Funktion IncVolume() DecVolume()
	...............................................................................
	erhöht/senkt Lautstärke-Level um 1 an Denon CEOL
				 	- Lautstärke in % [0-100]   =  [-79dB ... -69dB] 
        HTTP Befehl: http://192.168.178.29t:80/goform/formiPhoneAppDirect.xml?MVUP
        Telnet Befehl  MDUP  / MVDOWN
	...............................................................................
	Parameter:  none
	--------------------------------------------------------------------------------
	SetValue:  CeolVolume
	--------------------------------------------------------------------------------
	return: true  
	--------------------------------------------------------------------------------
	Status: checked 2018-06-03
	//////////////////////////////////////////////////////////////////////////////*/	
	Public function IncVolume(){
            $MasterVolume = $this->getvalue("CeolVolume") + 1;
            if($MasterVolume < -65){
                $this->SetValue("CeolVolume", $MasterVolume);
                $vol =  (intval($MasterVolume) + 78)*10;
                $this->SetValue("CeolVol", $vol);
                $this->send_cmd('MVUP');
                return true;
            }
            else{
                //oberer Einstellwert von -65db erreicht.  /Lautstärkebegrenzung
                return false;
            }            
	}	
	
	Public function DecVolume(){	
            $MasterVolume = $this->getvalue("CeolVolume") - 1;
            if($MasterVolume > -80){
                $this->SetValue("CeolVolume", $MasterVolume);
                $vol =  (intval($MasterVolume) + 78)*10;
                $this->SetValue("CeolVol", $vol);
                $this->send_cmd('MVDOWN');
                return true;
            }
            else{
                //unterer Einstellwert von -79db erreicht.
                return false;
            }
	}
        
	/*//////////////////////////////////////////////////////////////////////////////
	Befehl: ToggleMute()
	...............................................................................
	Toggled Befehl "Mute"
	...............................................................................
	Parameter:  none
	--------------------------------------------------------------------------------
	SetValue:  CeolMute
	--------------------------------------------------------------------------------
	return: true  
	--------------------------------------------------------------------------------
	Status: checked 2018-05-31
	//////////////////////////////////////////////////////////////////////////////*/	
	Public function ToggleMute(){
            $state = $this->getvalue("CeolMute");
            if ($state){
		$this->SetMute_AV('0');
		$this->SetValue("CeolMute", false);
            }
            else{
		$this->SetMute_AV('1');
		$this->SetValue("CeolMute", true);
            }	
            return true;	
	}


	/*//////////////////////////////////////////////////////////////////////////////
	Befehl: SetVolumeDB($Volume)
	...............................................................................
        sendet Lautstärke-Level an Denon CEOL
		- Lautstärke in % [0-100]   =  [-79dB ... -69dB] 
                - Begrenzung auf -69dB
        HTTP Befehl:   http://192.168.178.29:80/goform/formiPhoneAppVolume.xml?1+-72.0      
	...............................................................................
	Parameter:  $Volume = Integer = [0 - 100] 
	--------------------------------------------------------------------------------
	Variable:  none
	--------------------------------------------------------------------------------
	return: $output['item']['MasterVolume']['value']
		$output['item']['Mute']['value']  
	--------------------------------------------------------------------------------
	Status: checked 2018-06-03
	//////////////////////////////////////////////////////////////////////////////*/	
	Public function SetVolumeDB(int $Volume){
            $VoldB = -79.0 + ($Volume/10);
            $Wert =intval($VoldB);
            $Wert = str_replace(',', '.',$Wert);
            $cmd = '1+'.$Wert;
            $host = $this->ReadPropertyString('IPAddress');
            $url = "http://$host:80/goform/formiPhoneAppVolume.xml";
            $xml = $this->curl_get($url, $cmd);
        //$this->SendDebug('SetVolumeDB:XMLcreateArray_IN: ', $xml, 0);
            //$output = XML2Array::createArray($xml);
            $output = json_decode(json_encode((array)simplexml_load_string($xml)),true);
        //$this->SendDebug('SetVolumeDB:XMLcreateArray_IN: ', $output, 0);
            //$VolDB = ($output['item']['MasterVolume']['value']);
            $VolDB = ($output['MasterVolume']['value']);
            $this->SetValue("CeolVolume", $Wert);
            $vol =  (intval($Wert) + 79)*10;
            $this->SetValue("CeolVol", $vol);
            return $VolDB;
	}        
        
	/*//////////////////////////////////////////////////////////////////////////////
	Befehl: setBass( $value)
	...............................................................................
	Erhöht Bass Level (Range: -10 ... +10) (40...60)
        HTTP Befehl: http://192.168.178.29:80/goform/formiPhoneAppDirect.xml?UP
	Telnet Befehl: PSBASS_UP // PSBAS_DOWN // PSBAS_50
	...............................................................................
	Parameter:  $value = "UP" // "DOWN" // "50"
	--------------------------------------------------------------------------------
	Variable: 
	--------------------------------------------------------------------------------
	return  
	--------------------------------------------------------------------------------
	Status: checked 2018-05-31
	//////////////////////////////////////////////////////////////////////////////*/
	Public function setBass(string $value){ 
		$cmd = 'PSBAS_'.$value;
		$xml = $this->send_cmd($cmd);
		return $xml;
	}	
        
	/*//////////////////////////////////////////////////////////////////////////////
	Befehl: setTreble($value)
	...............................................................................
	Erhöht Trebble Level (Range: -10 ... +10) (40...60)
        HTTP Befehl: http://192.168.178.29:80/goform/formiPhoneAppDirect.xml?UP
	Telnet Befehl: PSTRE_UP // PSTRE_DOWN // PSTRE_50
	...............................................................................
	Parameter:  $value = "UP" // "DOWN" // "50"
	--------------------------------------------------------------------------------
	Variable: 
	--------------------------------------------------------------------------------
	return  
	--------------------------------------------------------------------------------
	Status: checked 2018-05-31
	//////////////////////////////////////////////////////////////////////////////*/
	Public function setTreble(string $value){ 
		$cmd = 'PSTRE_'.$value;
		$xml = $this->send_cmd($cmd);
		return $xml;
	}	
	
	/*//////////////////////////////////////////////////////////////////////////////
	Befehl: setBalance($value)
	...............................................................................
	Verandert den Balance Level (Range: 00 ... 99)  
        HTTP Befehl: http://192.168.178.29:80/goform/formiPhoneAppDirect.xml?PSBAL_LEFT
	Telnet Befehl: PSBAL_LEFT // PSBAL_RIGHT // PSBAL_50 = Center
	...............................................................................
	Parameter:  $value = "LEFT" // "RIGHT" // "50"
	--------------------------------------------------------------------------------
	Variable: 
	--------------------------------------------------------------------------------
	return  
	--------------------------------------------------------------------------------
	Status: checked 2018-05-31
	//////////////////////////////////////////////////////////////////////////////*/
	Public function setBalance(string $value){ 
		$cmd = 'PSBAL_'.$value;
		$xml = $this->send_cmd($cmd);
		return $xml;
	}	
	

        
        
        
	/*//////////////////////////////////////////////////////////////////////////////
	Befehl: showClock()
	...............................................................................
	zeigt die Uhrzeit im Display .
	...............................................................................
	Parameter:   
	--------------------------------------------------------------------------------
	Variable: 
	--------------------------------------------------------------------------------
	return  
	--------------------------------------------------------------------------------
	Status: checked 2018.06.18
	//////////////////////////////////////////////////////////////////////////////*/
	Public function showClock(){ 
		$cmd = 'CLK';
		$xml = $this->send_cmd($cmd);
		return $xml;
	}
        
	/*//////////////////////////////////////////////////////////////////////////////
	Befehl: setTimer()
	...............................................................................
	Wecker stellen und starten
	...............................................................................
	Parameter:  $mode = once/ every 
                    $startTime  = 07:30
                    $endTime    = 21:30    
                    $funct      = FA (Favorite)/ IP (IPOD) / US (USB)    
                    $n          = 01    (Favoriten Nummer)
                    $state      = on / off
         
         
	--------------------------------------------------------------------------------
	Variable: 
	--------------------------------------------------------------------------------
	return : 
	--------------------------------------------------------------------------------
	Status: funktioniert nicht
	//////////////////////////////////////////////////////////////////////////////*/
	Public function setTimer(string $mode, string $startTime, string $endTime, string $funct = 'FA', string $n = '01', string $volT = '03', string $state){    
            $mode = strtoupper ($mode);
            $sT= explode(':', $startTime);
            $periodS = '2';

            $eT= explode(':', $endTime);
            $periodE = '2';
            if ($state == 'on'){
                $ts = '1';
            }
            else {
                $ts = '0';
            }
		$cmd = 'TS'.$mode.' '.$periodS.$sT[0].$sT[1].'-'.$periodE.$eT[0].$eT[1].' '.$funct.$n.' '.$volT.' '.$ts;
		$xml = $this->send_cmd($cmd);
		return $cmd;
	}     
        
	/*//////////////////////////////////////////////////////////////////////////////
	Befehl: switchTimer($stateTimerOnce,$stateTimerAlways)
	...............................................................................
	Wecker ein/aus schalten
	...............................................................................
	Parameter:  $stateTimerOnce      = on / off
                    $stateTimerAlways    = on / off
         
	--------------------------------------------------------------------------------
	Variable: 
	--------------------------------------------------------------------------------
	return : 
	--------------------------------------------------------------------------------
	Status: funktioniert nicht
	//////////////////////////////////////////////////////////////////////////////*/
	Public function switchTimer(string $stateTimerOnce, string $stateTimerAlways){    
            $stateTimerOnce = strtoupper ($stateTimerOnce);
            $stateTimerAlways = strtoupper ($stateTimerAlways);
            $cmd = 'TO'.$stateTimerAlways.' '.$stateTimerAlways;
            
            $host = $this->ReadPropertyString('IPAddress');
            $url = "http://$host:80/goform/formiPhoneAppNetAudioCommand.xml";
            $xml = $this->curl_get($url, $cmd);
            //$xml = $this->send_cmd($cmd);
            return $cmd;
	}  
        
	/*//////////////////////////////////////////////////////////////////////////////
	Befehl: SetRadioChannel($Channel)
	...............................................................................
	schaltet Radiosender (Favoriten) um:
        HTTP Befehl: http://192.168.178.29:80/goform/formiPhoneAppDirect.xml?FV 01
	Telnet Befehl: FV 01
	...............................................................................
	Parameter:  $Channel = String = ['0" - '50'] 
	--------------------------------------------------------------------------------
	SetVariable: 
	--------------------------------------------------------------------------------
	return:  
	--------------------------------------------------------------------------------
	Status: checked 2018-05-31
	//////////////////////////////////////////////////////////////////////////////*/	
	Public function SetRadioChannel(string $Channel){
            $this->SendDebug('Switch Radio to Channel:', $Channel, 0);
            $cmd = 'FV'.'%20'.$Channel;
            $this->send_cmd($cmd);
            $this->SetValue("CeolFavChannel", intval($Channel)-1);
            return $Channel;
	}	        
       

	/*//////////////////////////////////////////////////////////////////////////////
	Befehl: GetCover()
	...............................................................................
	Holt Cover Bilder des abgespielten Streams
        HTTP Befehl: http://192.168.2.99/NetAudio/art.asp-jpg
o                    http://192.168.2.99/img/album%20art_S.png
	...............................................................................
	Parameter:  none
	--------------------------------------------------------------------------------
	SetVariable: 
	--------------------------------------------------------------------------------
	return:  $xml->array = output
	--------------------------------------------------------------------------------
	Status: checked 2018-05-31
	//////////////////////////////////////////////////////////////////////////////*/ 	
	Public function GetCover(){
            $host = $this->ReadPropertyString('IPAddress');
            $url = "http://$host:80/NetAudio/art.asp-jpg";
            //$url = "http://$host:80/img/album%20art_S.png";
            $cmd = "";
            $xml = $this->curl_get($url, $cmd);
            $Cover ='<img src='.$url. ' width=320px height=280px scrolling="no">';	
            setvalue(38066 /*[Denon-CEOL\_Cover]*/, $Cover);	
            return $xml;
	}	

        
	//*****************************************************************************
	/* Function: setServer($serverName)
	...............................................................................
	Umschalten auf Client
        ...............................................................................
	Parameters:  
            $serverName - "Friendly Name des Servers"  = "Plex" // "AVM"
	--------------------------------------------------------------------------------
	Returns:
            $key - Nummer des Client Arrays
        --------------------------------------------------------------------------------
	Status: 14.7.2018 checked
	//////////////////////////////////////////////////////////////////////////////*/
	public function setServer(string $serverName){
		//IPSLog("Starte Funktion : ", 'setServer');
		$which_key = "FriendlyName";
		$which_value = $serverName;
		$array = $this->getvalue("Ceol_ServerArray");
		$Server_Array = unserialize($array);
		$key = $this->search_key($which_key, $which_value, $Server_Array);

		$Server_Array[$key]['ServerActiveIcon'] = "image/button_ok_blue_80x80.png";
		$ServerIP                   = $Server_Array[$key]['ServerIP'];
		$ServerPort                 = $Server_Array[$key]['ServerPort'];
		$friendlyName               = $Server_Array[$key]['FriendlyName'];
		$ServerServiceType          = $Server_Array[$key]['ServerServiceType'];
		$ServerContentDirectory     = $Server_Array[$key]['ServerContentDirectory'];
		$ServerActiveIcon           = $Server_Array[$key]['ServerActiveIcon'];
		$ServerIconURL              = $Server_Array[$key]['IconURL'];
		$this->SetValue("Ceol_ServerIP", $ServerIP);
		$this->SetValue("Ceol_ServerPort", $ServerPort);
		$this->SetValue("Ceol_ServerName", $friendlyName);
		$this->SetValue("Ceol_ServerKey", $key);
		//SetValue(UPNP_Server_ServiceType, $ServerServiceType);
		$this->SetValue("Ceol_ServerContentDirectory", $ServerContentDirectory);
		$this->SetValue("Ceol_ServerIcon", $ServerIconURL);
		return $key;
	}   
 
        
	//*****************************************************************************
	/* Function: loadPlaylist($AlbumNo)
	...............................................................................
	Playlist aus Datei laden (XML) und in Variable Playlist_XML schreiben
	...............................................................................
	Parameters:  
            $AlbumNo - Album Nummer = '0001'.
            $Media - "CD" / "Audio"
	--------------------------------------------------------------------------------
	Returns:  
            $xml - Playlist as XML 
	--------------------------------------------------------------------------------
	Status:  14.7.2018 checked
	//////////////////////////////////////////////////////////////////////////////*/
	public function loadPlaylist(string $AlbumNo, string $Media){	
            $this->SendDebug('Send','lade Play Liste' , 0);
            $Server = $this->getvalue("Ceol_ServerName");
            $PlaylistName = $Server.$AlbumNo;
            $this->setvalue("Ceol_PlaylistName", $PlaylistName);
            $PlaylistFile = $PlaylistName.'.xml';

            switch ($Media) {
                case 'CD':
                    $Playlist = file_get_contents($this->Kernel()."media/Multimedia/Playlist/Musik/".$PlaylistFile);
                    break;
                    case 'Audio':
                    $Playlist = file_get_contents($this->Kernel()."media/Multimedia/Playlist/Audio/".$PlaylistFile);
                    break;
                default:
                     
                    break;
            }
            
            // Playlist abspeichern
            $this->setvalue("Ceol_Playlist_XML", $Playlist);
            // neue Playlist wurde geladen - TrackNo auf 0 zurücksetzen
            $this->setvalue("Ceol_Track", 1);

            $vars 				= explode(".", $PlaylistFile);
            $PlaylistName 			= $vars[0];
            $PlaylistExtension		= $vars[1];

            $xml = new SimpleXMLElement($Playlist);

            return $xml;
	}
  
        
	//*****************************************************************************
	/* Function: play()
	...............................................................................
	vorgewählte Playlist abspielen
        ...............................................................................
	Parameters: 
            none.
	--------------------------------------------------------------------------------
	Returns:  
            none
	--------------------------------------------------------------------------------
	Status:  
	//////////////////////////////////////////////////////////////////////////////*/
	public function play(){	
                
		$Playlist   = $this->getvalue("Ceol_Playlist_XML");
		
		$xml = new SimpleXMLElement($Playlist);
		$tracks = $xml->count();
		$this->setvalue("Ceol_NoTracks",$tracks);
 		$TrackNo = $this->getvalue("Ceol_Track");
                if ($TrackNo < 1){
                    $TrackNo = 1;
                    $this->setvalue("Ceol_Track", 1);
                }
		$track = ("Track".strval($TrackNo-1));
			
		$res = $xml->$track->resource; // gibt resource des Titels aus

		$metadata = $xml->$track->metadata; // gibt resource des Titels aus
		//UPNP_GetPositionInfo_Playing abschalten zum Ausführen des Transitioning
		//IPS_SetScriptTimer($this->GetIDForIdent("upnp_PlayInfo"), 0);
		$this->SetTimerInterval('Ceol_PlayInfo', 0);
                $this->SendDebug("PLAY ", 'Timer Position Deaktivieren', 0);
                 //Transport zuruecksetzen  wenn Media Stream verlinkt
                            $TransStatus = $this->GetTransportInfo_AV();                       
                            $this->setvalue("Ceol_Transport_Status", $TransStatus); 
                if ($TransStatus != 'NO_MEDIA_PRESENT') {          
                    $this->Stop_AV();
                }    
		//Transport starten 
                $this->SetAVTransportURI_AV((string) $res, (string) $metadata);
                $this->SendDebug("PLAY ", 'SetAVTransportURI', 0);              
                            $TransStatus = $this->GetTransportInfo_AV();                       
                            $this->setvalue("Ceol_Transport_Status", $TransStatus); 
		//Stream ausführen	
		$this->Play_AV();
                $this->SendDebug("PLAY ", 'Play_AV', 0);
                            $TransStatus = $this->GetTransportInfo_AV();                       
                            $this->setvalue("Ceol_Transport_Status", $TransStatus);               

                IPS_Sleep(2000);
		// Postion Timer starten                 
                $this->SetTimerInterval('Ceol_PlayInfo', 1000);
                $this->SendDebug("PLAY ", 'Timer Position aktivieren', 0);  
	}

	//*****************************************************************************
	/* Function: PlayNextTrack()
	...............................................................................
	nächsten Track aus der vorgewählten Playlist abspielen
	...............................................................................
	Parameters:  
            none.
	--------------------------------------------------------------------------------
	Returns:  
            none
	--------------------------------------------------------------------------------
	Status:  
	//////////////////////////////////////////////////////////////////////////////*/
	public function PlayNextTrack(){	
            $track 	= $this->getvalue("Ceol_Track");
            $this->SendDebug("PlayNextTrack ", $track, 0);
            $this->setvalue("Ceol_Track",$track+1);
            $trackNo 	= ("Track".strval($track));
            $Playlist 	= $this->getvalue("Ceol_Playlist_XML");
            $xml = new SimpleXMLElement($Playlist);

            $res = $xml->$trackNo->resource; // gibt resource des Titels aus
            $metadata = $xml->$trackNo->metadata; // gibt resource des Titels aus

            $this->SetAVTransportURI_AV((string) $res, (string) $metadata);
            $this->Play_AV();
	}
  

	//*****************************************************************************
	/* Function: Stop()
        --------------------------------------------------------------------------------
        * Stream stoppen
        * Track Zähler zurücksetzen
        * Positions - Timer ausschalten
        ...............................................................................
	Parameters: 
            none.
	--------------------------------------------------------------------------------
	Returns:  
              none.
        //////////////////////////////////////////////////////////////////////////////*/
	public function stop(){	

            
            $this->SendDebug('STOP', 'Stream stoppen', 0);
            /*Timer abschalten--------------------------------------------------------*/
            $this->SetTimerInterval('Ceol_PlayInfo', 0);
            $this->SendDebug("STOP ", 'Timer Position Deaktivieren', 0);
            /*Stram stoppen--------------------------------------------------------*/
            $this->Stop_AV();
            //Track Zähler auf Anfang zurücksetzen
            $this->setvalue("Ceol_Track", 0);
            //Transport Status zurücksetzen auf Anfang zurücksetzen
            $this->setvalue("Ceol_Transport_Status", '');
         
	}
	
	//*****************************************************************************
	/* Function: Pause()
        --------------------------------------------------------------------------------
         Pause
        ...............................................................................
	Parameters: 
            none.
	--------------------------------------------------------------------------------
	Returns:
            none.
        //////////////////////////////////////////////////////////////////////////////*/
	public function pause()
	{	
		$this->Pause_AV();
	}


	
	//*****************************************************************************
	/* Function: Next()
        --------------------------------------------------------------------------------
        ...............................................................................
	Parameters: 
            none.
	--------------------------------------------------------------------------------
	Returns:
            none.
        //////////////////////////////////////////////////////////////////////////////*/
	public function next()
	{	
		$Playlist = $this->getvalue("Ceol_Playlist_XML");
		$xml = new SimpleXMLElement($Playlist);
		//$count = count($xml->children()); 
		//IPSLog("Anzahl XML Elemente : ", $count);
		
		$SelectedFile = $this->getvalue("Ceol_Track"); 
		
		$track = ("Track".($SelectedFile+1));

		//Aktueller Track = Selected File-----------------------------------------
        $this->setvalue("Ceol_Track", ($SelectedFile+1));

		$this->play();	

	}	
	
	
	
	//*****************************************************************************
	/* Function: Previous()
        -------------------------------------------------------------------------------
        nächste 
        ...............................................................................
	Parameters:
            none.
        --------------------------------------------------------------------------------
	Returns:
            none.
        //////////////////////////////////////////////////////////////////////////////*/
	public function previous()
	{	
	
		$Playlist = $this->getvalue("Ceol_Playlist_XML");
		$xml = new SimpleXMLElement($Playlist);
		$SelectedFile = $this->getvalue("Ceol_Track"); 
		$track = ("Track".($SelectedFile-1));

		//Aktueller Track = Selected File-----------------------------------------
        $this->setvalue("Ceol_Track", ($SelectedFile-1));
		
		$this->play();

	}	
        
 	//*****************************************************************************
	/* Function: seekForward()
        -------------------------------------------------------------------------------
        spult Lied um 20 Sekunden vor
        ...............................................................................
	Parameters:
            none.
        --------------------------------------------------------------------------------
	Returns:
            none.
        //////////////////////////////////////////////////////////////////////////////*/
	public function seekForward(){	
 
            $postime = $this->getvalue("Ceol_RelTime");
            $seconds = 20;
            $time_now = "00:00:00.000";
            $this->SendDebug('seekForward', $postime, 0);
            $position = date("H:i:s.000", (strtotime(date($postime)) + $seconds));

            $this->SendDebug('seekForward', $position, 0);
            $this->Seek_AV('REL_TIME', $position);
	}
	//*****************************************************************************
	/* Function: seekForward()
        -------------------------------------------------------------------------------
        spult Lied um 20 Sekunden vor
        ...............................................................................
	Parameters:
            none.
        --------------------------------------------------------------------------------
	Returns:
            none.
        //////////////////////////////////////////////////////////////////////////////*/
	public function seekBackward(){	
            $postime = $this->getvalue("Ceol_RelTime");
            $seconds = 20;
            $time_now = "00:00:00.000";
            $this->SendDebug('seekForward', $postime, 0);
            $position = date("H:i:s.000", (strtotime(date($postime)) - $seconds));
            $this->SendDebug('seekBackward', $position, 0);
            $this->Seek_AV('REL_TIME', $position);
	}
        
	//*****************************************************************************
	/* Function: seekPos($Seek)
        -------------------------------------------------------------------------------
        spult Lied auf $position %  der Lieddauer
        ...............................................................................
	Parameters:
            $Seek = Angabe 0 ...100 wird in % der Duration umgerechnet
        --------------------------------------------------------------------------------
	Returns:
            none.
	--------------------------------------------------------------------------------
	Status:   checked 5.7.2018  nur für TV und MusikPal CEOL funktiniert nicht
        //////////////////////////////////////////////////////////////////////////////*/
	public function seekPos(int $Seek){	
            $GetPositionInfo = $this->GetPositionInfo_AV();
            $Duration = $GetPositionInfo['TrackDuration'];
            $duration = explode(":", $Duration);
            $seconds = round(((($duration[0] * 3600) + ($duration[1] * 60) + ($duration[2])) * ($Seek/100)), 0, PHP_ROUND_HALF_UP);
            $position = gmdate('H:i:s', $seconds);
            $this->Seek_AV('REL_TIME', $position );
	}       
        
	//*****************************************************************************
	/* Function: GetPosInfo()
	...............................................................................
	Aufruf durch Timer jede Sekunde
	überprüft 'CurrentTransportState' und PositionInfo
	...............................................................................
	Parameters:
            none.
	--------------------------------------------------------------------------------
	Returns:  
            none.
	--------------------------------------------------------------------------------
	Status:  
	//////////////////////////////////////////////////////////////////////////////*/
	public function GetPosInfo(){ 
		//IPAdresse und Port des gewählten Device---------------------------------------
                $ClientPort = '8080';
		$host = $this->ReadPropertyString('IPAddress');
		$fsock = fsockopen($host, $ClientPort, $errno, $errstr, $timeout = '1');
		if ( !$fsock ){
                    //nicht erreichbar --> Timer abschalten--------------------------------
                    $this->SendDebug('Send', $host.'ist nicht erreichbar!', 0);
                    $this->SetTimerInterval('Ceol_PlayInfo', 0);  // DeAktivert Ereignis
		}
		else{
			/*///////////////////////////////////////////////////////////////////////////
			Auswertung nach CurrentTransportState "PLAYING" oder "STOPPED"
			bei "PLAYING" -> GetPositionInfo -> Progress wird angezeigt
			bei "STOPPED" -> nächster Titel wird aufgerufen
			/*///////////////////////////////////////////////////////////////////////////
			$Playlist = $this->getvalue("Ceol_Playlist_XML");
			$xml = new SimpleXMLElement($Playlist);
                        $TNo = GetValue($this->GetIDForIdent("Ceol_Track"));
                        $this->SendDebug("GetPosInfo ", 'Track Nummer '.$TNo, 0);
			$SelectedFile = $TNo -1; 
                        if ($TNo === 0){
                            $this->SetTimerInterval('Ceol_PlayInfo', 0);  // DeAktivert Ereignis
                            $this->SendDebug("GetPosInfo ", 'Timer Position Deaktivieren weil Track = 0', 0);
                        }
                        else {
                            $track = ("Track".($SelectedFile));
                            $DIDL_Lite_Class = $xml->$track->class;
                            $this->SendDebug("GetPosInfo ", 'class des Tracks abfragen: '.$DIDL_Lite_Class , 0);
                            /* Transport Status abfragen */
                            $PlayModeIndex = $this->GetTransportSettings_AV();
                            //$this->IPSLog("Playmode Array", $PlayMode); 
                            $this->SendDebug("GetPosInfo ", 'Playmode: '.$PlayModeIndex , 0);

                            $this->setvalue("Ceol_PlayMode", $PlayModeIndex);
                            /* Transport Status abfragen */
                            $TransStatus = $this->GetTransportInfo_AV();                       
                            $this->setvalue("Ceol_Transport_Status", $TransStatus);
                             $this->SendDebug("GetPosInfo ", 'Transport Status abfragen: '.$TransStatus , 0);
                            //Transport Status auswerten.
                            switch ($TransStatus){
                                case 'TRANSITIONING':
                                    IPS_Sleep(1000);
                                    break;
                                case 'NO_MEDIA_PRESENT':
                                    $this->SetTimerInterval('Ceol_PlayInfo', 0);  // DeAktivert Ereignis
                                    $this->SendDebug("GetPosInfo ", 'Timer Position Deaktivieren weil NO MEDIA', 0);
                                    $this->setvalue("Ceol_Progress",0);
                                    $this->setvalue("Ceol_Track",0);
                                    break;
                                case 'STOPPED':
                                    $lastTrack = $this->getvalue("Ceol_Track");
                                    $maxTrack = $this->getvalue("Ceol_NoTracks");
                                    if ($lastTrack > 0  AND $lastTrack < $maxTrack){
                                            $this->PlayNextTrack();		
                                    }
                                    else {
                                        $this->SetTimerInterval('Ceol_PlayInfo', 0);  // DeAktivert Ereignis
                                        $this->SendDebug("GetPosInfo ", 'Timer Position Deaktivieren weil STOPPED und TRACK = 0', 0);
                                        $this->setvalue("Ceol_Progress",0);
                                        $this->setvalue("Ceol_Track",0);
                                    }
                                    break;
                                case 'PLAYING':
                                    if($DIDL_Lite_Class == "object.item.audioItem.musicTrack"){
                                        $this->SendDebug("GetPosInfo ", 'progress aufrufen', 0);
                                        $fortschritt = $this->progress();
                                    }
                                    else if($DIDL_Lite_Class == "object.item.videoItem"){
                                            //include_once ("35896 /*[Multimedia\Core\UPNP_Progress]*/.ips.php"); //UPNP_Progress
                                    }
                                    else if($DIDL_Lite_Class == "object.item.imageItem.photo"){
                                            //include_once ("57444 /*[Multimedia\Core\UPNP_SlideShow]*/.ips.php"); //UPNP_SlideShow
                                    }
                                    else {$this->Stop_AV();}
                                    break;
                                default:

                            }
                        }    
		}
	}
            
        

	//*****************************************************************************
	/* Function: progress ($ClientIP, $ClientPort, $ControlURL)
	...............................................................................
	Fortschrittsanzeige
	Liest PositionInfo aus aktuellem Stream aus:
		['TrackDuration']
		['RelTime']
		['TrackMetaData'] = DIDL =
									'dc:creator'
									'dc:title'
									'upnp:album'
									'upnp:originalTrackNumber'
									'dc:description'
									'upnp:albumArtURI'
									'upnp:genre'
									'dc:date'
	...............................................................................
	Parameters:  
            $ClientIP - Client IP auf dem wiedergeben wird.
            $ClientPort - IP des Clients.
            $ControlURL - Control URL des Clients.
	--------------------------------------------------------------------------------
	Returns:  
            $Progress - Integer Wert 0 - 100 
        -------------------------------------------------------------------------------
	Status:  
	//////////////////////////////////////////////////////////////////////////////*/
	Protected function progress(){	
            $PositionInfo = $this->GetPositionInfo_AV();
            $this->SendDebug("GetPositionInfo_AV ", $PositionInfo , 0); 
            $Duration = (string) $PositionInfo['TrackDuration']; //Duration
            $this->setvalue("Ceol_TrackDuration", (string) $Duration);           
            $RelTime = (string) $PositionInfo['RelTime']; //RelTime
            $this->setvalue("Ceol_RelTime", (string) $RelTime);          
            //$this->SendDebug("progress ", ' GetRelTIME PositionInfo: '.$RelTime, 0);
            /*
            $TrackMeta = (string) $GetPositionInfo['TrackMetaData'];
            $b = htmlspecialchars_decode($TrackMeta);
            //$this->IPSLog('HTML: ', $b);
            $didlXml = simplexml_load_string($b); 
            $this->SendDebug("progress-DIDL INFO ", $didlXml , 0);
            $creator = (string)$didlXml->item[0]->xpath('dc:creator')[0];
            $title = (string) $didlXml->item[0]->xpath('dc:title')[0];
            $album = (string)$didlXml->item[0]->xpath('upnp:album')[0];
            $TrackNo = (string)$didlXml->item[0]->xpath('upnp:originalTrackNumber')[0];
            $actor = (string)$didlXml->item[0]->xpath('upnp:actor')[0];
            $AlbumArtURI = (string)$didlXml->item[0]->xpath('upnp:albumArtURI')[0];
            $genre = (string)$didlXml->item[0]->xpath('upnp:genre')[0];
            $date = (string)$didlXml->item[0]->xpath('dc:date')[0];
            */
                                
            $this->setvalue("Ceol_Artist",  $PositionInfo["artist"]);
            $this->setvalue("Ceol_Title",  $PositionInfo["title"]);
            $this->setvalue("Ceol_Album",  $PositionInfo["album"]);		
            //setvalue($this->GetIDForIdent("Ceol_TrackNo"),  $PositionInfo["TrackNo"]);
            
            //setvalue($this->GetIDForIdent("Ceol_Actor"),  $PositionInfo["album"]);
            $this->setvalue("Ceol_Date",  $PositionInfo["album"]);
            $this->setvalue("Ceol_AlbumArtUri", $PositionInfo["albumArtURI"]);
            //setvalue($this->GetIDForIdent("Ceol_Genre"),  $PositionInfo["genre"]);
                function get_time_difference($Duration, $RelTime){
                        $duration = explode(":", $Duration);
                        $reltime = explode(":", $RelTime);
                        $time_difference = round((((($reltime[0] * 3600) + ($reltime[1] * 60) + ($reltime[2]))* 100) / (($duration[0] * 3600) + ($duration[1] * 60) + ($duration[2]))), 0, PHP_ROUND_HALF_UP);
                        return ($time_difference);
                }
            if($Duration == "0:00:00"){
                    $Duration = (string) $PositionInfo['AbsTime']; //AbsTime
            }
            $Progress = get_time_difference($Duration, $RelTime);
            $this->setvalue("Ceol_Progress", $Progress);
            return $Progress;
	}

        
        
        
	//*****************************************************************************
	/* Function: discoverServer()
	...............................................................................
	Sucht alle UPNP  Server
	...............................................................................
	Parameters:  
            none
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
	public function discoverServer(){
		$ST_MS = "urn:schemas-upnp-org:device:MediaServer:1";
        $this->setvalue("Ceol_ServerArray", '');
			$SSDP_Search_Array = $this->mSearch($ST_MS);
			$SSDP_Array = $this->array_multi_unique($SSDP_Search_Array);
			//IPSLog('bereinigtes mSearch Ergebnis ',$SSDP_Array);
		 	$Server_Array = $this->create_Server_Array($SSDP_Array); 
 
             $this->setvalue("Ceol_ServerArray", $Server_Array);
           
 	 
	}        
        
        
        
        
        
        
        
 
        
	//*****************************************************************************
	/* Function:  search_key($which_key, $which_value, $array)
        ...............................................................................
        den $key des Elternelementes in einem mehrdimensionalen Array finden
        ...............................................................................
        Parameter:  
            * $which_key =    = zu durchsuchedes ArrayFeld = ['FriendlyName']
            * $which_value    = Suchwert = z.Bsp "CEOL"
            * $array          = zu durchsuchendes Array
        --------------------------------------------------------------------------------
        return:  
            * key = gefundener Datensatz index
        --------------------------------------------------------------------------------
        Status  checked  
        //////////////////////////////////////////////////////////////////////////////*/
        Protected function search_key(string $which_key, string $which_value, array $array){
            foreach ($array as $key => $value){
                if($value[$which_key] === $which_value){
                    return $key;
                }
                else{
                    //$this->SendDebug('Send', $which_value.' in Key: '.$key.' not found', 0);

                }
            }
        }

	//*****************************************************************************
	/* Function: Kernel()
        ...............................................................................
        Stammverzeichnis von IP Symcon
        ...............................................................................
        Parameter:  

        --------------------------------------------------------------------------------
        return:  

        --------------------------------------------------------------------------------
        Status  checked 11.6.2018
        //////////////////////////////////////////////////////////////////////////////*/
        Protected function Kernel(){ 
            $Kernel = str_replace("\\", "/", IPS_GetKernelDir());
            return $Kernel;
        }    
        
      
        
       
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
	/*//////////////////////////////////////////////////////////////////////////////
	Befehl: PlayFiles(array $files)
	...............................................................................
	 
	...............................................................................
	Parameter:  none
	--------------------------------------------------------------------------------
	SetVariable: 
	--------------------------------------------------------------------------------
	return:  
	--------------------------------------------------------------------------------
	Status: not implemented
	//////////////////////////////////////////////////////////////////////////////*/ 
        public function PlayFile(string $file){
   

           // $positionInfo       = $ceol->GetPositionInfo();

            //$mediaInfo          = $ceol->GetMediaInfo();

            //$transportInfo      = $ceol->GetTransportInfo();


           // foreach ($files as $key => $file) {

                // only files on SMB share or http server can be used
                if (preg_match('/^\/\/[\w,.,\d,-]*\/\S*/',$file) == 1){
                   $uri = "x-file-cifs:".$file;
                  
                }elseif (preg_match('/^https{0,1}:\/\/[\w,.,\d,-,:]*\/\S*/',$file) == 1){
                    $uri = $file;
                }else{
                   throw new Exception("File (".$file.") has to be located on a Samba share (e.g. //ipsymcon.fritz.box/tts/text.mp3) or a HTTP server (e.g. http://ipsymcon.fritz.box/tts/text.mp3)");
                }
                $this->SendDebug("Spiele File: ", $uri, 0);
                $this->SetAVTransportURI_AV($uri, "") ;
                $this->SetPlayMode_AV('NORMAL');	
                $this->Play_AV();
                IPS_Sleep(500);
/*
              $fileTransportInfo = $sonos->GetTransportInfo();

              while ($fileTransportInfo==1 || $fileTransportInfo==5){ 

                IPS_Sleep(200);

                $fileTransportInfo = $ceol->GetTransportInfo();

              }

           // }



            // reset to what was playing before

            $ceol->SetAVTransportURI($mediaInfo["CurrentURI"],$mediaInfo["CurrentURIMetaData"]);

            if($positionInfo["TrackDuration"] != "0:00:00" && $positionInfo["Track"] > 1)

              try {

                $ceol->Seek("TRACK_NR",$positionInfo["Track"]);

              } catch (Exception $e) { }

            if($positionInfo["TrackDuration"] != "0:00:00" && $positionInfo["RelTime"] != "NOT_IMPLEMENTED" )

              try {

                $ceol->Seek("REL_TIME",$positionInfo["RelTime"]);

              } catch (Exception $e) { }




            if ($transportInfo==1){

              $ceol->Play();

            }

  */              
            
        }
        
	/*//////////////////////////////////////////////////////////////////////////////
	Befehl: PlayFiles(array $files)
	...............................................................................
	 
	...............................................................................
	Parameter:  none
	--------------------------------------------------------------------------------
	SetVariable: 
	--------------------------------------------------------------------------------
	return:  
	--------------------------------------------------------------------------------
	Status: not implemented
	//////////////////////////////////////////////////////////////////////////////*/ 
        public function PlayM3U(){

            
        }     
        
	/*//////////////////////////////////////////////////////////////////////////////
	Befehl: getImageFromLastFM()
	...............................................................................
	Holt ein Bild anhand eines Interpreten aus LastFM API 
	...............................................................................
	Parameter:   
           $artist
           $size
              =  "small" => 0, "medium" => 1, "large" => 2, "extralarge" => 3, "mega" => 4 

        
	--------------------------------------------------------------------------------
	SetVariable: 
	--------------------------------------------------------------------------------
	return:  
	--------------------------------------------------------------------------------
	Status: 03.10.2018
	//////////////////////////////////////////////////////////////////////////////*/ 
        public function getImageFromLastFM(string $artist, int $size){
            $artisDec = urlencode($artist);
            $url    = "http://ws.audioscrobbler.com/2.0/?method=artist.getinfo&artist={$artisDec}&api_key=91770645e54b138f5187003fcb830865";
            
            if ($url == ""){
                $this->SendDebug("getImageFromLastFM: ", "URL für image not found.", 0);
                $url = "/var/lib/symcon/webfront/user/images/INetRadio1.png";
            }
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, $url);    // get the url contents

            $data = curl_exec($ch); // execute curl request
            curl_close($ch);

            $xml = simplexml_load_string($data); 
            $json = json_encode($xml);
            $array = json_decode($json,TRUE);
            $this->SendDebug("getImageFromLastFM: ", "ARRAY = ".$json, 0);
            if (isset($array)){
                $this->SendDebug("getImageFromLastFM: ", "Status = ".$array["@attributes"]["status"], 0);       
                if($array["@attributes"]["status"] = "OK"){
                    if(isset($array["artist"]["image"][$size])){
                        $imageUrl = $array["artist"]["image"][$size];
                        //prüfen ob übergebener Wert ein URL ist.
                        if (filter_var($url, FILTER_VALIDATE_URL) and $imageUrl != "extralarge") {
                            $this->SendDebug("getImageFromLastFM: ", $imageUrl, 0);
                            return $imageUrl;
                        }
                        else{
                           $this->SendDebug("getImageFromLastFM: ", "keine gültige URL", 0); 
                           return false; 
                        }
                    }
                    else{
                        return false;
                    }
                }
                else{
                    //kein Bild vorhanden
                    return false;
                }
            }
        } 
        

    

/*       
    _______________________________________________________________________
    Section: Private Funtions
    Die folgenden Funktionen sind nur zur internen Verwendung verfügbar
    Hilfsfunktionen
______________________________________________________________________
*/ 
    /* ----------------------------------------------------------------------------
    Function: createProfile
    ...............................................................................
    Erstellt ein neues Profil und ordnet es einer Variablen zu.
    ...............................................................................
    Parameters: 
        $Name, $Icon, $Prefix, $Suffix, $MinValue, $MaxValue, $StepSize, $Digits, $Vartype, $VarIdent, $Assoc
     * $Vartype: 0 boolean, 1 int, 2 float, 3 string,
     * $Assoc: array mit statustexte
     *         $assoc[0] = "aus";
     *         $assoc[1] = "ein";
     *  
    ..............................................................................
    Returns:   
        none
    ------------------------------------------------------------------------------- */
    protected function createProfile(string $Name, int $Vartype, $Assoc, $Icon="",  $Prefix="",  $Suffix="",   $MinValue=0 ,  $MaxValue,  $StepSize,  $Digits=0){
        if (!IPS_VariableProfileExists($Name)) {
            IPS_CreateVariableProfile($Name, $Vartype); // 0 boolean, 1 int, 2 float, 3 string,
            if(!is_Null($Icon)){
                IPS_SetVariableProfileIcon($Name, $Icon);
            }
            if(!is_Null($Prefix)){
                IPS_SetVariableProfileText($Name, $Prefix, $Suffix);
            }
            if(!is_Null($Digits)){
                IPS_SetVariableProfileDigits($Name, $Digits); //  Nachkommastellen
            }
            if(!is_Null($MinValue)){
                IPS_SetVariableProfileValues($Name, $MinValue, $MaxValue, $StepSize);
            }
            if(!is_Null($Assoc)){
                foreach ($Assoc as $key => $data) {
                    IPS_SetVariableProfileAssociation($Name, $data['value'], $data['text'], $data['icon']="", $data['color']=-1);  
                }
            }
        } 
        else {
            $profile = IPS_GetVariableProfile($Name);
            if ($profile['ProfileType'] != $Vartype){
                // $this->SendDebug("Alarm.Reset:", "Variable profile type does not match for profile " . $Name, 0);
            }
        }
    }	//Function: createProfile End
    
    
    
    /* ----------------------------------------------------------------------------
     Function: RegisterProfiles()
    ...............................................................................
        Profile für Variable anlegen falls nicht schon vorhanden
    ...............................................................................
    Parameters: 
        $Vartype => 0 boolean, 1 int, 2 float, 3 string
    ..............................................................................
    Returns:   
        $ipsversion
    ------------------------------------------------------------------------------- */
    protected function RegisterProfiles(){
       /*   Profile "Channel";  */ 
       $Assoc[0]['value'] = 0;
       $Assoc[1]['value'] = 1;
       $Assoc[2]['value'] = 2;
       $Assoc[3]['value'] = 3;
       $Assoc[4]['value'] = 4;
       $Assoc[5]['value'] = 5;
       $Assoc[6]['value'] = 6;
       $Assoc[7]['value'] = 7;
       $Assoc[8]['value'] = 8;
       $Assoc[0]['text'] = "Rock";
       $Assoc[1]['text'] = "SWR 1";
       $Assoc[2]['text'] = "Antenne Bayern";
       $Assoc[3]['text'] = "RP";
       $Assoc[4]['text'] = "Bayern 3";
       $Assoc[5]['text'] = "WDR 5";
       $Assoc[6]['text'] = "HR 3";
       $Assoc[7]['text'] = "SWR 3";
       $Assoc[8]['text'] = "SWR 4";
       $Name = "DenonCeol_Channel";
       $Vartype = 1;
       $MaxValue = 8;
       if (!IPS_VariableProfileExists($Name)){
           $this->createProfile($Name, $Vartype,  $Assoc, $Icon="", $Prefix="", $Suffix="", $MinValue=0, $MaxValue, $StepSize=0, $Digits=0);  
       }
        /*   Profile "Media_Status";  */ 
        $Assoc[0]['value'] = 0;
        $Assoc[1]['value'] = 1;
        $Assoc[2]['value'] = 2;
        $Assoc[3]['value'] = 3;
        $Assoc[4]['value'] = 4;
        $Assoc[5]['value'] = 5;
        $Assoc[6]['value'] = 6;
        $Assoc[7]['value'] = 7;
        $Assoc[8]['value'] = 8;
        $Assoc[0]['text'] = "-";
        $Assoc[1]['text'] = "FastForward";
        $Assoc[2]['text'] = "Next";
        $Assoc[3]['text'] = "Pause";
        $Assoc[4]['text'] = "Play";
        $Assoc[5]['text'] = "Previous";
        $Assoc[6]['text'] = "Rewind";
        $Assoc[7]['text'] = "StartOver";
        $Assoc[8]['text'] = "Stop";
        $Name = "Media_Status";
        $Vartype = 1;
        $MaxValue = 8;
        if (!IPS_VariableProfileExists($Name)){
            $this->createProfile($Name, $Vartype,  $Assoc, $Icon="", $Prefix="", $Suffix="", $MinValue=0, $MaxValue, $StepSize=1, $Digits=0);  
        }
       /*   Profile "Media_Status";  */ 
        $Assoc[0]['value'] = 0;
        $Assoc[1]['value'] = 1;
        $Assoc[2]['value'] = 2;
        $Assoc[3]['value'] = 3;
        $Assoc[4]['value'] = 4;
        $Assoc[5]['value'] = 5;
        $Assoc[6]['value'] = 6;
        $Assoc[7]['value'] = 7;
        $Assoc[8]['value'] = 8;
        $Assoc[0]['text'] = "-";
        $Assoc[1]['text'] = "FastForward";
        $Assoc[2]['text'] = "Next";
        $Assoc[3]['text'] = "Pause";
        $Assoc[4]['text'] = "Play";
        $Assoc[5]['text'] = "Previous";
        $Assoc[6]['text'] = "Rewind";
        $Assoc[7]['text'] = "StartOver";
        $Assoc[8]['text'] = "Stop";
        $Name = "Media_Status";
        $Vartype = 1;
        $MaxValue = 8;
        if (!IPS_VariableProfileExists($Name)){
            $this->createProfile($Name, $Vartype,  $Assoc, $Icon="", $Prefix="", $Suffix="", $MinValue=0, $MaxValue, $StepSize=1, $Digits=0);  
        }
       /*   Profile "DenonCEOL_Source";  */ 
        $Assoc[0]['value'] = 0;
        $Assoc[1]['value'] = 1;
        $Assoc[2]['value'] = 2;
        $Assoc[3]['value'] = 3;
        $Assoc[4]['value'] = 4;
        $Assoc[5]['value'] = 5;
        $Assoc[0]['text'] = "IRadio";
        $Assoc[1]['text'] = "Media";
        $Assoc[2]['text'] = "USB";
        $Assoc[3]['text'] = "IPOD";
        $Assoc[4]['text'] = "AUX A";
        $Assoc[5]['text'] = "AUX D";
        $Name = "DenonCEOL_Source";
        $Vartype = 1;
        $MaxValue = 8;
        if (!IPS_VariableProfileExists($Name)){
            $this->createProfile($Name, $Vartype,  $Assoc, $Icon, $Prefix, $Suffix, $MinValue, $MaxValue, $StepSize, $Digits);  
        }
       /*   Profile "UPNP_Playmode";  */ 
       $Assoc[0]['value'] = 0;
       $Assoc[1]['value'] = 1;
       $Assoc[2]['value'] = 2;
       $Assoc[3]['value'] = 3;
       $Assoc[0]['text'] = "NORMAL";
       $Assoc[1]['text'] = "RANDOM";
       $Assoc[2]['text'] = "REPEAT_ONE";
       $Assoc[3]['text'] = "REPEAT_ALL";
       $Name = "UPNP_Playmode";
       $Vartype = 1;
       $MaxValue = 3;
       if (!IPS_VariableProfileExists($Name)){
           $this->createProfile($Name, $Vartype,  $Assoc, $Icon, $Prefix, $Suffix, $MinValue, $MaxValue, $StepSize, $Digits);  
       }
       /*   Profile "DenonCEOL_Volume";  */ 
       $Assoc = NULL;
        $Name = "DenonCEOL_Volume";
       $Vartype = 1;
       $Suffix = "%";
       $MinValue = 0;
       $MaxValue = 100;
       $StepSize = 1;
       if (!IPS_VariableProfileExists($Name)){
           $this->createProfile($Name, $Vartype,  $Assoc, "", $Prefix, $Suffix, $MinValue, $MaxValue, $StepSize, $Digits);  
       }
              /*   Profile "DenonCEOL_Vol";  */ 
              $Assoc = NULL;
              $Name = "DenonCEOL_Vol";
             $Vartype = 1;
             $Suffix = "%";
             $MinValue = 0;
             $MaxValue = 10;
             $StepSize = 10;
             $Digits = 0;
             if (!IPS_VariableProfileExists($Name)){
                 $this->createProfile($Name, $Vartype,  $Assoc, "", $Prefix, $Suffix, $MinValue, $MaxValue, $StepSize, $Digits);  
             }
    } //Function: RegisterProfiles End

    
} // Ende Klasse
?>
