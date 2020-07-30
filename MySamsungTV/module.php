<?php
//zugehoerige TRAIT-Klassen    TEST xxxy

require_once(__DIR__ . "/../libs/NetworkTraits2.php");



class MySamsungTV extends IPSModule
{
    
    //externe Klasse einbinden - ueberlagern mit TRAIT.
    use MyDebugHelper2;
     
    //*****************************************************************************
    /* Function: Standardfunktinen für ein Modul. 
    ...............................................................................
    *  Create()
     * ApplyChanges()
     * 
    /* **************************************************************************** */
    public function Create()
    {
	//Never delete this line!
        parent::Create();
        //These lines are parsed on Symcon Startup or Instance creation   
        // Variable aus dem Instanz Formular registrieren (um zugänglich zu machen)
        $this->RegisterPropertyBoolean("aktiv", false);
        $this->RegisterPropertyBoolean("telnet", false);
        $this->RegisterPropertyString("ip", "192.168.178.35");
        $this->RegisterPropertyInteger("updateInterval", 10000);	
        $this->RegisterPropertyInteger("devicetype", 0);
        $this->RegisterPropertyInteger("PowerSwitch_ID", 0);
 
 }
    
    
    // ApplyChanges() wird einmalig aufgerufen beim Erstellen einer neuen Instanz und
    // bei Änderungen der Formular Parameter (form.json) (nach Übernahme Bestätigung.)
    // Überschreibt die intere IPS_ApplyChanges($id) Funktion
    /* **************************************************************************** */
    public function ApplyChanges() {
	//Never delete this line!
        parent::ApplyChanges();
            if($this->ReadPropertyBoolean("aktiv")){
                

            }
            else {

            }
    }
    
    


    //*****************************************************************************
    /* Function: Hilfs funktinen für ein Modul
    ...............................................................................
    *  SendToSplitter(string $payload)
     * GetIPSVersion()
     * RegisterProfile()
     * RegisterProfileAssociation()
     *  SetValue($Ident, $Value)
     */
    /* **************************************************************************** */

	protected function GetIPSVersion()
	{
		$ipsversion = floatval(IPS_GetKernelVersion());
		if ($ipsversion < 4.1) // 4.0
		{
			$ipsversion = 0;
		} elseif ($ipsversion >= 4.1 && $ipsversion < 4.2) // 4.1
		{
			$ipsversion = 1;
		} elseif ($ipsversion >= 4.2 && $ipsversion < 4.3) // 4.2
		{
			$ipsversion = 2;
		} elseif ($ipsversion >= 4.3 && $ipsversion < 4.4) // 4.3
		{
			$ipsversion = 3;
		} elseif ($ipsversion >= 4.4 && $ipsversion < 5) // 4.4
		{
			$ipsversion = 4;
		} else   // 5
		{
			$ipsversion = 5;
		}

		return $ipsversion;
	}

	//Profile
	protected function RegisterProfile($Name, $Icon, $Prefix, $Suffix, $MinValue, $MaxValue, $StepSize, $Digits, $Vartype)
	{

		if (!IPS_VariableProfileExists($Name)) {
			IPS_CreateVariableProfile($Name, $Vartype); // 0 boolean, 1 int, 2 float, 3 string,
		} else {
			$profile = IPS_GetVariableProfile($Name);
			if ($profile['ProfileType'] != $Vartype)
				$this->SendDebug("BMW:", "Variable profile type does not match for profile " . $Name, 0);
		}

		IPS_SetVariableProfileIcon($Name, $Icon);
		IPS_SetVariableProfileText($Name, $Prefix, $Suffix);
		IPS_SetVariableProfileDigits($Name, $Digits); //  Nachkommastellen
		IPS_SetVariableProfileValues($Name, $MinValue, $MaxValue, $StepSize); // string $ProfilName, float $Minimalwert, float $Maximalwert, float $Schrittweite
	}

	protected function RegisterProfileAssociation($Name, $Icon, $Prefix, $Suffix, $MinValue, $MaxValue, $Stepsize, $Digits, $Vartype, $Associations)
	{
		if (sizeof($Associations) === 0) {
			$MinValue = 0;
			$MaxValue = 0;
		}

		$this->RegisterProfile($Name, $Icon, $Prefix, $Suffix, $MinValue, $MaxValue, $Stepsize, $Digits, $Vartype);

		//boolean IPS_SetVariableProfileAssociation ( string $ProfilName, float $Wert, string $Name, string $Icon, integer $Farbe )
		foreach ($Associations as $Association) {
			IPS_SetVariableProfileAssociation($Name, $Association[0], $Association[1], $Association[2], $Association[3]);
		}

	}
        
        /*
  	//Add this Polyfill for IP-Symcon 4.4 and older
	protected function SetValue($Ident, $Value)
	{

		if (IPS_GetKernelVersion() >= 5) {
			parent::SetValue($Ident, $Value);
		} else {
			SetValue($this->GetIDForIdent($Ident), $Value);
		}
	}

*/
        
        
	/*//////////////////////////////////////////////////////////////////////////////
	Function:  watchdog()
	...............................................................................
	Funktion wird über Timer alle 60 Sekunden gestartet
         *  call SubFunctions:   
	...............................................................................
	Parameter:  none
	--------------------------------------------------------------------------------
	SetVariable:    
	--------------------------------------------------------------------------------
	return: none  
	--------------------------------------------------------------------------------
	Status: checked 2018-06-03
	//////////////////////////////////////////////////////////////////////////////*/       
        public function watchdog() {

        }

	/*//////////////////////////////////////////////////////////////////////////////
	Function:  update()
	...............................................................................
	Funktion wird über Timer alle x Sekunden gestartet, wenn TV über ping erreichbar = eingeschaltet
         *  call SubFunctions:   
	...............................................................................
	Parameter:  none
	--------------------------------------------------------------------------------
	SetVariable:    
	--------------------------------------------------------------------------------
	return: none  
	--------------------------------------------------------------------------------
	Status: checked 2018-06-03
	//////////////////////////////////////////////////////////////////////////////*/       
        public function update() {
    
        }


    //*****************************************************************************
    /* Function: Eigene Public Funktionen
    /* **************************************************************************** */	
        
     //*****************************************************************************
    /* Function: getVolume()
    ...............................................................................
     * gibt den Lautstärke Wert als Integer zurück.
     * und schreibt Ergebnis in die Variable Volume
    ...............................................................................
    Parameters: none
    --------------------------------------------------------------------------------
    Returns:  
     * $volume (integer)
    --------------------------------------------------------------------------------
    Status:  17.07.2018 - OK  - (TelNet = OK)
    //////////////////////////////////////////////////////////////////////////////*/  
    public function getVolume() {

    }   
  
   
        
     //*****************************************************************************
    /* Function: getChannel()
    ...............................................................................
     * gibt den aktuell eingestellten SendeKanal zurück
     * und schreibt Ergebnis in die Variable Channel 
    ...............................................................................
    Parameters: none
    --------------------------------------------------------------------------------
    Returns:  
     * $channel (xml-string)
    --------------------------------------------------------------------------------
    Status:  17.07.2018 - OK  - (Telnet = NOK)
    //////////////////////////////////////////////////////////////////////////////*/  
    public function getChannel() {

    } 

     //*****************************************************************************
    /* Function: getChannelName()
    ...............................................................................
     * gibt den Namen des aktuellen Senders zurück 
     * und schreibt Ergebnis in die Variable ChannelName. 
    ...............................................................................
    Parameters: none
    --------------------------------------------------------------------------------
    Returns:  
     * $chName (string)
    --------------------------------------------------------------------------------
    Status:  17.07.2018 - OK   - (Telnet = NOK) 
    //////////////////////////////////////////////////////////////////////////////*/  
    public function getCurrentChannelName() {

    }   
  
    
    
    
    
    
     //*****************************************************************************
    /* Function: setChannelbyName(string $ChName) 
    ...............................................................................
     * schaltet auf den übergebenen SenderNamen um
     *  
    ...............................................................................
    Parameters: none
    --------------------------------------------------------------------------------
    Returns:  
     * $chName (string)
    --------------------------------------------------------------------------------
    Status:  17.07.2018 - OK    - (Telnet = OK) 
    //////////////////////////////////////////////////////////////////////////////*/  
    public function setChannelbyName(string $ChName) {

    }   
    
    
     //*****************************************************************************
    /* Function: getTVGuide(channel as array)
    ...............................................................................
     *  
     *  
    ...............................................................................
    Parameters: none
    --------------------------------------------------------------------------------
    Returns:  
     * $channel (array)
     * $channels= array("Das Erste HD", 
     *                  "ZDF HD", 
     *                  "RTL Television", 
     *                  "ProSieben", 
     *                  "kabel eins", 
     *                   "RTL2", 
     *                   "SAT.1", 
     *                   "3sat", 
     *                   "VOX", 
     *                   "Tele 5", 
     *                   "ONE HD", 
     *                   "RTLplus" );
     *
    --------------------------------------------------------------------------------
    Status:    27.12.2019 OK - Telnet OK  
    //////////////////////////////////////////////////////////////////////////////*/  
    public function getTVGuide() {  

    }

    
    //*****************************************************************************
    /* Function: getSourceList() 
    ...............................................................................
     * liest die vorhandenen Sources aus und welcher aktiv ist
     *  
    ...............................................................................
    Parameters: none
    --------------------------------------------------------------------------------
    Returns:  
     *  SourceList
     *      => Array
            (
                [SOURCETYPE] => TV
                [ID] => 0
                [EDITABLE] => No
                [DEVICENAME] => 3
                [CONNECTED] => Yes
                [SUPPORTVIEW] => Yes
                [active] => Yes
            )
     
    --------------------------------------------------------------------------------
    Status:   - Telnet NOK
    //////////////////////////////////////////////////////////////////////////////*/  
    public function getSourceList()  {

    }
    
    
    //*****************************************************************************
    /* Function: getCurrentSource() 
    ...............................................................................
     * liest die aktuell angewählte source Kanal aus
     *  
    ...............................................................................
    Parameters: none
    --------------------------------------------------------------------------------
    Returns:  
     *  Source
    --------------------------------------------------------------------------------
    Status:   - Telnet NOK
    //////////////////////////////////////////////////////////////////////////////*/  
    public function getCurrentSource()  {

    }      
    
    //*****************************************************************************
    /* Function: sendWWW(string $URL) 
    ...............................................................................
     * startet browser mit www Seite
     *  
    ...............................................................................
    Parameters: $URL
    --------------------------------------------------------------------------------
    Returns:  
     *  
    --------------------------------------------------------------------------------
    Status:   
    //////////////////////////////////////////////////////////////////////////////*/  
    public function sendWWW(string $URL)  {
       
    }  
    
    
    /*//////////////////////////////////////////////////////////////////////////////
    Befehl: ToggleMute()
    ...............................................................................
    Toggled Befehl "Mute"
    ...............................................................................
    Parameter:  none
    --------------------------------------------------------------------------------
    SetValue:   
    --------------------------------------------------------------------------------
    return: none 
    --------------------------------------------------------------------------------
    Status:    - Telnet OK
    //////////////////////////////////////////////////////////////////////////////*/	
    Public function ToggleMute(){
 

    }   
    
    
    /*//////////////////////////////////////////////////////////////////////////////
    Befehl: incVolume()
    ...............................................................................
    Lautstärke erhöhen
    ...............................................................................
    Parameter:  none
    --------------------------------------------------------------------------------
    SetValue:   -
    --------------------------------------------------------------------------------
    return: none 
    --------------------------------------------------------------------------------
    Status:   25.7.2018 - Telnet OK
    //////////////////////////////////////////////////////////////////////////////*/	
    Public function incVolume(){   

    
    }
    
    
    /*//////////////////////////////////////////////////////////////////////////////
    Befehl: decVolume()
    ...............................................................................
    Lautstärke verringern 
    ...............................................................................
    Parameter:  none
    --------------------------------------------------------------------------------
    SetValue:   
    --------------------------------------------------------------------------------
    return: none 
    --------------------------------------------------------------------------------
    Status:    25.7.2018 - Telnet OK
    //////////////////////////////////////////////////////////////////////////////*/	
    Public function decVolume(){   

    }    
    
    /*//////////////////////////////////////////////////////////////////////////////
    Befehl: nextChannel()
    ...............................................................................
    auf nächsten Sender schalten 
    ...............................................................................
    Parameter:  none
    --------------------------------------------------------------------------------
    SetValue:   
    --------------------------------------------------------------------------------
    return: none 
    --------------------------------------------------------------------------------
    Status:    28.12.2019 - Telnet OK
    //////////////////////////////////////////////////////////////////////////////*/	
    Public function nextChannel(){   

    }    

    /*//////////////////////////////////////////////////////////////////////////////
    Befehl: prevChannel()
    ...............................................................................
    auf vorherigen Sender schalten 
    ...............................................................................
    Parameter:  none
    --------------------------------------------------------------------------------
    SetValue:   
    --------------------------------------------------------------------------------
    return: none 
    --------------------------------------------------------------------------------
    Status:    28.12.2019 - Telnet OK
    //////////////////////////////////////////////////////////////////////////////*/	
    Public function prevChannel(){   

    }   

     //*****************************************************************************
    /* Function: setSource
    ...............................................................................
     *  
     *  
    ...............................................................................
    Parameters: $source
    --------------------------------------------------------------------------------
    Returns:  
     * true, false
    --------------------------------------------------------------------------------
    Status:      
    //////////////////////////////////////////////////////////////////////////////*/  
    public function setSource($source) { 

    }     
    
    
    
    
     //*****************************************************************************
    /* Function: getChExtTVlist(string $ChName) 
    ...............................................................................
     *  COMEDY CENTRAL
     *  ProSieben MAXX
     *  Das Erste
     *  ZDF 
     *  RTL
     *  SAT.1
     *  ProSieben 
     *  kabel eins
     *  RTL II
     *  VOX
     *  TELE 5
     *  3sat
     *  ARTE
     *  ZDFneo
     *  ONE
     *  ServusTV Deutschland
     *  NITRO
     *  DMAX
     *  sixx
     *  SAT.1 Gold
     * 
     *
    ...............................................................................
    Parameters: none
    --------------------------------------------------------------------------------
    Returns:  
     * $chName (string)
    --------------------------------------------------------------------------------
    Status:   27.12.2019 all OK
    //////////////////////////////////////////////////////////////////////////////*/  
    public function getChExtTVlist(string $ChName) {

    }
    
 
    
    
    
    
    //*****************************************************************************
    /* Function: buildChannelList() 
    ...............................................................................
     * erzeugt ein Array aller Channels
     * 
     *  
    ...............................................................................
    Parameters: none
    --------------------------------------------------------------------------------
    Returns:  (array)
     * [Pr#] =>  
     * [ChannelName] =>  
     * [ChType] =>  
     * [MAJORCH] =>  
     * [MINORCH] =>  
     * [PTC] =>  
     * [PROGNUM] =>  
     * -
    --------------------------------------------------------------------------------
    Status:  17.07.2018 - OK  - Telnet NOK
    //////////////////////////////////////////////////////////////////////////////*/  
    public function buildChannelList() {
 
    }    
        
    
        
    //*****************************************************************************
    /* Function: Eigene Interne Funktionen.
    /* **************************************************************************** */	        
      
        
        
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
        
        
	Protected function IPSLog($Text, $array) {
		$Directory=""; 
		$File="";
		
		if (!$array){
		
			$array = '-';
		}
		
		
		if ($File == ""){
		
			$File = 'IPSLog.log';
		}
		if ($Directory == "") {
			$Directory = "/home/pi/pi-share/";
			//$Directory = IPS_GetKernelDir().'/';
			//if (function_exists('IPS_GetLogDir'))
			//	$Directory = IPS_GetLogDir();
		}
		
		if(($FileHandle = fopen($Directory.$File, "a")) === false) {
			//SetValue($ID_OutEnabled, false);
			Exit;
		}
		if (is_array($array)){
			//$comma_seperated=implode("\r\n",$array);
			$comma_seperated=print_r($array, true);
		}
		else {
			$comma_seperated=$array;
		}
		fwrite($FileHandle, $Text.": ");
		fwrite($FileHandle, $comma_seperated."\r\n");
		fclose($FileHandle);
                
        }        

       
        Protected function searcharray($value, $key, $array) {
           foreach ($array as $k => $val) {
                $this->SendDebug("searcharray vergleiche: ", $val[$key]." mit ".  $value  , 0);
               if ($val[$key] == $value) {
                   $this->SendDebug("Suchergebnis ", $key, 0);
                   return $k;
               }
           }
           $this->SendDebug("searcharray ", "nicht GEFUNDEN!", 0);
           return null;
        }
  
        
        
        
        Protected function searchForValue($value, $prop, $array) {
           $this->SendDebug("searchForValue ", $value." in Prop ".$prop, 0);
           if ($prop=="ChannelName"){
               // $this->SendDebug("searchForValue ", $array, 0);
           }
           foreach ($array as $key => $val) {
               $x =  $val[$prop]; 
               //$this->SendDebug("searchForValue vergleiche: ", $x." mit ".strval($value), 0);
               if ( $x == $value ) {
                   
                   $this->SendDebug("searchForValue ", $value." Wert  gefunden.", 0);
                   return $key;
               }
           }
           $this->SendDebug("searchForValue ",  " Wert  nicht gefunden.", 0);
           return "null" ;
        }
        
        
        Protected function readChannelFile() {
            // Read JSON file
            $dataPath = IPS_GetKernelDir() . '/modules/MySymDevices/MySamsungTV/';
            $json = file_get_contents($dataPath.'channels.json');
            //Decode JSON
            // true = json als array ausgeben
            $json_data = json_decode($json,true);
            return $json_data;
        }    

        Public function MakeChannelList(){
            //DB.xml holen und in JSON umwandeln
            $mediaDB = simplexml_load_file($this->Kernel()."media/Multimedia/Playlist/TV/DB.xml");

            //Medien Datenbank füllen.

            //XML in Array umwandeln
            $json = json_encode($mediaDB);
            
            // in file schreiben
            $handle = fopen($this->Kernel()."media/Multimedia/Playlist/TV/DBasJson.txt", "w");
            fwrite($handle, $json);
            fclose($handle);
            
        }

        /*//////////////////////////////////////////////////////////////////////////////
        Funktion SetPower($status)
        ...............................................................................
        TV Schaltsteckdose Ein / Aus Schalten
        ...............................................................................
        Parameter:  $status = "On" // "Off" 
        --------------------------------------------------------------------------------
         
        --------------------------------------------------------------------------------
        return: $status = 'on' / 'off'  // 0 / 1 
        --------------------------------------------------------------------------------
        Status:  26.12.2019
        //////////////////////////////////////////////////////////////////////////////*/        
        Public function SetPower($status){
             

        }  
}
