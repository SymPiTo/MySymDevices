<?php
require_once(__DIR__ . "/UpnpDiscoveryClassTrait.php");
require_once(__DIR__ . "/UpnpClassTrait.php");
require_once(__DIR__ . "/../libs/NetworkTraits2.php");
            
require_once(__DIR__ . "/../libs/Array2XML.php");  // diverse Klassen

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the  editor.
 */

/**
 * class: MyUpnp extends IPSModule
 * 
 * 
 */
class MyUpnp extends IPSModule {

    //Traits verbinden
        /* About: TRAITS
         * UpnpClassTrait.php
         */
    use upnp,
        UpnpDiscoveryClassTrait,
        MyDebugHelper2 ;
    
    /* Constructor: 
    Der Konstruktor des Moduls
    Überschreibt den Standard Kontruktor von IPS
     */
    public function __construct($InstanceID) {
        // Diese Zeile nicht löschen
        parent::__construct($InstanceID);
    }
    //*****************************************************************************
    /* Function: Create()    
    * Create() wird einmalig beim Erstellen einer neuen Instanz ausgeführt
    * Überschreibt die interne IPS_Create($id)  Funktion
    Variable:
     * *STATUSVARIABLE
    --- Code
        * (String)  upnp_Artist             =>  DIDL_Artist [dc:creator]
        * (String)  upnp_Album              =>  DIDL_Album [upnp:album]
        * (String)  upnp_Title              =>  DIDL_Titel [dc:title]
        * (String)  upnp_Actor              =>  DIDL_Actor [upnp:actor]
        * (String)  upnp_AlbumArtUri        =>  DIDL_AlbumArtURI [upnp:albumArtURI]
        * (String)  upnp_Genre              =>  DIDL_Genre [upnp:genre]
        * (String)  upnp_Genre              =>  DIDL_Genre [upnp:genre]
        * (String)  upnp_Date               =>  DIDL_Date [dc:date]
        * (Integer) upnp_Progress           =>  Progress, "UPNP_Progress
        * (Integer) upnp_LoopStart          =>  Play, "UPNP_LoopStart
        * (Integer) upnp_LoopStop           =>  Play, "UPNP_LoopStop
        * (Bool)    upnp_Seq                =>  Play, "UPNP_Seq
        * (Integer) upnp_Track              =>  Pos:Track", "" 
        * (String)  upnp_RELTIME            =>  RelTime", "" 
        * (String)  upnp_Transport_Status   =>  Pos:Transport_Status
        * (String)  upnp_ClientArray        =>  Client:Array")
        * (String)  upnp_ClientControlURL   =>  Client:ControlURL")
        * (String)  upnp_ClientIcon         =>  Client:Icon
        * (String)  upnp_ClienIP            =>  Client:IP 
        * (String)  upnp_ClientKey          =>  Client:Key", ""
        * (String)  upnp_ClientName         =>  Client:Name");
        * (String)  upnp_ClientPort         =>  Client:Port");
        * (String)  upnp_ServerArray        =>  Server:Array
        * (String)  upnp_ServerIcon         =>  Server:Icon
        * (String)  upnp_ServerIP           =>  Server:IP");
        * (String)  upnp_ServerKey          =>  Server:Key", ""
        * (String)  upnp_ServerName         =>  Server:Name
        * (String)  upnp_ServerPort         =>  Server:Port
        * (Integer) upnp_NoTracks           =>  No of tracks, ""
        * (String)  upnp_PlaylistName       =>  PlaylistName
        * (String)  upnp_Playlist_XML       =>  Playlist_XML  
        * (String)  upnp_ClientRenderingControlURL  =>  Client:RenderingControlURL   
        * (String)  upnp_ServerContentDirectory     =>  Server:ContentDirectory  
        * (String)  upnp_PlexID [ARRAY]     =>  PlexID['music']   ['video']   ['photo']   ['audio']   
        * RegisterTimer("upnp_PlayInfo", 1000,  'UPNP_GetPosInfo(' . $this->InstanceID . ');');
     * 
    --- 
    */
    public function Create() {
        // Diese Zeile nicht löschen.
        parent::Create();
        
        $this->RegisterPropertyBoolean("active", false);

        //$this->RegisterProfiles();
        
        // Category anlegen
        // Anlegen einer neuen Kategorie 
       // $KategorieID = @IPS_GetCategoryIDByName("DIDL", $this->InstanceID);
        //if ($KategorieID === false){
            //$CatID = IPS_CreateCategory();       // Kategorie anlegen
            //IPS_SetName($CatID, "DIDL"); // Kategorie benennen
            //IPS_SetParent($CatID, $this->InstanceID); // Kategorie einsortieren unter dem Objekt mit der ID "12345"  
              //Status Variable anlegen
            $variablenID = $this->RegisterVariableString("upnp_Artist", "DIDL_Artist [dc:creator]");
            IPS_SetInfo ($variablenID, "WSS"); 
            $variablenID = $this->RegisterVariableString("upnp_Album", "DIDL_Album [upnp:album]");
            IPS_SetInfo ($variablenID, "WSS"); 
            $variablenID = $this->RegisterVariableString("upnp_Title", "DIDL_Titel [dc:title]");
            IPS_SetInfo ($variablenID, "WSS"); 
            $variablenID = $this->RegisterVariableString("upnp_Actor", "DIDL_Actor [upnp:actor]");
            IPS_SetInfo ($variablenID, "WSS"); 
            $variablenID = $this->RegisterVariableString("upnp_AlbumArtUri", "DIDL_AlbumArtURI [upnp:albumArtURI]");
            IPS_SetInfo ($variablenID, "WSS"); 
            $variablenID = $this->RegisterVariableString("upnp_Genre", "DIDL_Genre [upnp:genre]");
            IPS_SetInfo ($variablenID, "WSS"); 
            $variablenID = $this->RegisterVariableString("upnp_Date", "DIDL_Date [dc:date]");
            IPS_SetInfo ($variablenID, "WSS"); 
            $variablenID = $this->RegisterVariableString("upnp_TrackNo", "DIDL_TrackNumber [upnp:originalTrackNumber]");
            IPS_SetInfo ($variablenID, "WSS"); 
            
            //$ID_CatDIDL =  IPS_GetCategoryIDByName("DIDL", $this->InstanceID);
            //Verschieben der Variable unter Ordner DIDL
            //IPS_SetParent($this->GetIDForIdent("upnp_Album"), $ID_CatDIDL);
            //IPS_SetParent($this->GetIDForIdent("upnp_Title"), $ID_CatDIDL);
            //IPS_SetParent($this->GetIDForIdent("upnp_Description"), $ID_CatDIDL);
            //IPS_SetParent($this->GetIDForIdent("upnp_AlbumArtUri"), $ID_CatDIDL);
            //IPS_SetParent($this->GetIDForIdent("upnp_Genre"), $ID_CatDIDL);
            //IPS_SetParent($this->GetIDForIdent("upnp_Date"), $ID_CatDIDL);
            //IPS_SetParent($this->GetIDForIdent("upnp_TrackNo"), $ID_CatDIDL);
            //IPS_SetParent($this->GetIDForIdent("upnp_Artist"),$ID_CatDIDL);              
        //}

        //$KategorieID = @IPS_GetCategoryIDByName("PositionInfo", $this->InstanceID);
        //if ($KategorieID === false){        
            //$CatID = IPS_CreateCategory();       // Kategorie anlegen
            //IPS_SetName($CatID, "PositionInfo"); // Kategorie benennen
            //IPS_SetParent($CatID, $this->InstanceID); 
            //Status Variable anlegen;
            $variablenID = $this->RegisterVariableInteger("upnp_PlayMode", "PlayMode", "UPNP_Playmode");
            IPS_SetInfo ($variablenID, "WSS"); 
            $variablenID = $this->RegisterVariableBoolean("upnp_Mute", "Mute");
            IPS_SetInfo ($variablenID, "WSS"); 
            $variablenID = $this->RegisterVariableBoolean("upnp_Seq", "Sequece");
            IPS_SetInfo ($variablenID, "WSS"); 
            $variablenID = $this->RegisterVariableFloat("upnp_Volume", "Volume", "");
            IPS_SetInfo ($variablenID, "WSS"); 
            $variablenID = $this->RegisterVariableInteger("upnp_Progress", "Progress", "UPNP_Progress");
            IPS_SetInfo ($variablenID, "WSS"); 
            $variablenID = $this->RegisterVariableInteger("upnp_Track", "Pos:Track", "");
            IPS_SetInfo ($variablenID, "WSS"); 
            $variablenID = $this->RegisterVariableInteger("upnp_Status", "Control", "UPNP_Status");
            IPS_SetInfo ($variablenID, "WSS");

             $variablenID = $this->RegisterVariableString("upnp_Transport_Status", "Pos:Transport_Status");
            IPS_SetInfo ($variablenID, "WSS"); 
            $variablenID = $this->RegisterVariableString("upnp_RelTime", "RelTime");
            IPS_SetInfo ($variablenID, "WSS"); 
            $variablenID = $this->RegisterVariableString("upnp_TrackDuration", "TrackDuration");
            IPS_SetInfo ($variablenID, "WSS"); 
            $variablenID = $this->RegisterVariableString("upnp_MediaType", "MediaType");
            IPS_SetInfo ($variablenID, "WSS"); 
            $variablenID = $this->RegisterVariableInteger("upnp_Browse", "BrowseDir", "UPNP_Direction");
            IPS_SetInfo ($variablenID, "WSS"); 
          
            $this->RegisterVariableString("upnp_DIDLRessource", "DIDL_ressource");
            $this->RegisterVariableString("upnp_BrowseTitle", "BrowseTitle");
            $this->RegisterVariableString("upnp_BrowseContent", "BrowseContent");
            $this->SetValue("upnp_Browse",2);
            $content = array(
                "ObjectID" => "0",
                "ParentID" => "0",
                "PrevID" => "0",
                "NextID" => "0",
                "TotalNo" => 1,
                "CurrentNo" => 0,
                "class" => "object",
                "Title" => "root",
            );
            $this->SetValue("upnp_BrowseTitle", $content['Title']);
            $this->SetValue("upnp_BrowseContent", serialize($content));
       
            
            $this->RegisterVariableString("upnp_PlexID", "PlexID");
            $PlexID = array(
                "music" => "0",
                "audio" => "0",
                "video" => "0",
                "photo" => "0",
            );
            $this->SetValue("upnp_PlexID", serialize($PlexID)); 

            //$this->RegisterVariableString("upnp_TrackDuration", "Pos:TrackDuration [upnp:album]");
            //$this->RegisterVariableString("upnp_TrackMetaData", "Pos:TrackMetaData");
            //$this->RegisterVariableString("upnp_TrackURI", "Pos:TrackURI");
            //$this->RegisterVariableString("upnp_RelTime", "Pos:RelTime");
            //$this->RegisterVariableString("upnp_AbsTime", "Pos:AbsTime");
            //$this->RegisterVariableString("upnp_RelCount", "Pos:RelCount");
            //$this->RegisterVariableString("upnp_AbsCount", "Pos:AbsCount");
            //$ID_PosInfo =  IPS_GetCategoryIDByName("PositionInfo", $this->InstanceID);
            //Verschieben der Variable unter Ordner PositionInfo
            //IPS_SetParent($this->GetIDForIdent("upnp_Progress"), $ID_PosInfo);
            //IPS_SetParent($this->GetIDForIdent("upnp_Transport_Status"), $ID_PosInfo);
            //IPS_SetParent($this->GetIDForIdent("upnp_TrackDuration"), $ID_PosInfo);
            //IPS_SetParent($this->GetIDForIdent("upnp_TrackMetaData"), $ID_PosInfo);
            //IPS_SetParent($this->GetIDForIdent("upnp_TrackURI"), $ID_PosInfo);
            //IPS_SetParent($this->GetIDForIdent("upnp_RelTime"), $ID_PosInfo);
            //IPS_SetParent($this->GetIDForIdent("upnp_AbsTime"), $ID_PosInfo);
            //IPS_SetParent($this->GetIDForIdent("upnp_RelCount"), $ID_PosInfo);
            //IPS_SetParent($this->GetIDForIdent("upnp_AbsCount"), $ID_PosInfo);
            //IPS_SetParent($this->GetIDForIdent("upnp_Track"), $ID_PosInfo);       
        //}
        // Variable aus dem Instanz Formular registrieren (zugänglich zu machen)
            //$this->RegisterPropertyBoolean("active", false);
            //$this->RegisterPropertyString("IPAddress", "");
            //$this->RegisterPropertyInteger("UpdateInterval", 30);
           

        $this->RegisterVariableString("upnp_ObjectID", "BrowseContent:ObjectID");
        $this->RegisterVariableInteger("upnp_SaveAsPlayList", "BrowseSaveAsPlayList", "");
        $this->EnableAction("upnp_SaveAsPlayList");    
        
        $variablenID = $this->RegisterVariableString("upnp_ClientArray", "Client:Array");
        IPS_SetInfo ($variablenID, "WSS"); 
        $this->RegisterVariableString("upnp_ClientControlURL", "Client:ControlURL");
        $variablenID = $this->RegisterVariableString("upnp_ClientIcon", "Client:Icon");
        IPS_SetInfo ($variablenID, "WSS"); 
        $this->RegisterVariableString("upnp_ClienIP", "Client:IP");  
        $variablenID = $this->RegisterVariableInteger("upnp_ClientKey", "Client:Key", "");
        IPS_SetInfo ($variablenID, "WSS"); 
        $variablenID = $this->RegisterVariableString("upnp_ClientName", "Client:Name");
        IPS_SetInfo ($variablenID, "WSS"); 
        $this->RegisterVariableString("upnp_ClientPort", "Client:Port");
        $this->RegisterVariableString("upnp_ClientRenderingControlURL", "Client:RenderingControlURL");
        
        $variablenID = $this->RegisterVariableString("upnp_ServerArray", "Server:Array");
        IPS_SetInfo ($variablenID, "WSS"); 
        $this->RegisterVariableString("upnp_ServerContentDirectory", "Server:ContentDirectory");
        $variablenID = $this->RegisterVariableString("upnp_ServerIcon", "Server:Icon");
        IPS_SetInfo ($variablenID, "WSS"); 
        $this->RegisterVariableString("upnp_ServerIP", "Server:IP");
        $variablenID = $this->RegisterVariableInteger("upnp_ServerKey", "Server:Key", "");
        IPS_SetInfo ($variablenID, "WSS"); 
        $variablenID = $this->RegisterVariableString("upnp_ServerName", "Server:Name");
        IPS_SetInfo ($variablenID, "WSS"); 
        $this->RegisterVariableString("upnp_ServerPort", "Server:Port");
        

        $variablenID = $this->RegisterVariableString("upnp_LoopStart", "Loop:Start", "");
        IPS_SetInfo ($variablenID, "WSS"); 
        $variablenID = $this->RegisterVariableString("upnp_LoopSTop", "Loop:Stop", "");
        IPS_SetInfo ($variablenID, "WSS"); 

        $variablenID = $this->RegisterVariableInteger("upnp_NoTracks", "No of tracks", "");
        IPS_SetInfo ($variablenID, "WSS"); 
        $variablenID = $this->RegisterVariableString("upnp_PlaylistName", "PlaylistName");
        IPS_SetInfo ($variablenID, "WSS"); 
        $this->RegisterVariableString("upnp_Playlist_XML", "Playlist_XML");       
        $this->RegisterVariableString("upnp_Message", "Message");
        
            //$this->RegisterVariableBoolean("CeolPower", "Power");        
            $this->EnableAction("upnp_Mute");
            IPS_SetVariableCustomProfile($this->GetIDForIdent("upnp_Mute"), "~Switch");
            $this->EnableAction("upnp_Seq");
            IPS_SetVariableCustomProfile($this->GetIDForIdent("upnp_Seq"), "~Switch");
            $this->EnableAction("upnp_PlayMode");
            IPS_SetVariableCustomProfile($this->GetIDForIdent("upnp_PlayMode"), "UPNP_Playmode");
            $this->EnableAction("upnp_Browse");
            IPS_SetVariableCustomProfile($this->GetIDForIdent("upnp_Browse"), "UPNP_Direction");
            
            
        // Timer erstellen
        $this->RegisterTimer("upnp_PlayInfo", 0,  'UPNP_GetPosInfo(' . $this->InstanceID . ');');
    }
    
    //*****************************************************************************
    /* Function: ApplyChanges()
    * ApplyChanges() wird einmalig aufgerufen beim Erstellen einer neuen Instanz und
    * bei Änderungen der Formular Parameter (form.json) (nach Übernahme Bestätigung)
    * Überschreibt die intere IPS_ApplyChanges($id) Funktion
    * 
    */
    public function ApplyChanges() {
        // Diese Zeile nicht löschen
        parent::ApplyChanges();
            if($this->ReadPropertyBoolean("active")){
                
                // Status auf Stop stellen
                setvalue($this->GetIDForIdent("upnp_Status"), 3);
                
            }
            else {
                $this->SetTimerInterval("upnp_PlayInfo", 0);
                
            }   

    }
    
    public function RequestAction($Ident, $Value) {
        switch($Ident) {
            case "upnp_Seq":
                if($Value){
                    SetValue("upnp",true);
                }
                else{
                    SetValue("upnp",false);
                }
            break;
            case "upnp_Mute":
                //Hier würde normalerweise eine Aktion z.B. das Schalten ausgeführt werden
                //Ausgaben über 'echo' werden an die Visualisierung zurückgeleitet
                if($Value){
                    $this->SetMute_AV($ClientIP, $ClientPort, $RenderingControlURL, '1');
                    SetValue($this->GetIDForIdent("upnp_Mute"), true);
                }
                else{
                    $this->SetMute_AV($ClientIP, $ClientPort, $RenderingControlURL, '0');
                    SetValue($this->GetIDForIdent("upnp_Mute"), false);
                }
                break;
            case "upnp_PlayMode":
                switch ($Value){
                    case 0:
                        $playmode = "NORMAL";
                        break;
                    case 1:
                         $playmode = "RANDOM";
                        break;
                    case 2:
                         $playmode = "REPEAT_ONE";
                        break;
                    case 3;
                        $playmode = "REPEAT_ALL";
                        break;
                }
                $this->setPlayMode($playmode);
                break;
            case "upnp_Browse":
                $this->BrowseNav($Value);
                break;
             case "upnp_SaveAsPlayList":
                $id = getvalue($this->GetIDForIdent("upnp_ObjectID"));
                $No = getvalue($this->GetIDForIdent("upnp_PlaylistName"));
                $this->createPlaylist($id,$No);
                break;
            default:
                throw new Exception("Invalid Ident");
        }

    } 
        


        /* About: Public Funtions
        * Die folgenden Funktionen stehen automatisch zur Verfügung, wenn das Modul über die "Module Control" eingefügt wurden.
        * Die Funktionen werden, mit dem selbst eingerichteten Prefix, in PHP und JSON-RPC wie folgt zur Verfügung gestellt:
        *
        * CEOL_XYFunktion($Instance_id, ... );
        *
        */
        public $n = 0;
        public $PrevID = array( 0 => '0');

        public function BrowseNav($Direction){
            //global $n, $PrevID;
                    
            $object = unserialize(getvalue($this->GetIDForIdent("upnp_BrowseContent")));
            $this->SendDebug('BrowseNav: ', "Starte Funktion ------>   Browse Nav", 0);
            $this->SendDebug('PREV ID: ', $this->PrevID, 0);
            $this->SendDebug('n: ', $this->n, 0);
            $this->SendDebug('$object: ', $object, 0);
            $ServerIP = getvalue($this->GetIDForIdent("upnp_ServerIP"));
            $ServerPort = getvalue($this->GetIDForIdent("upnp_ServerPort"));
            $Kernel = str_replace("\\", "/", IPS_GetKernelDir());
            $ServerContentDirectory  = getvalue($this->GetIDForIdent("upnp_ServerContentDirectory"));
            $BrowseFlag = "BrowseDirectChildren";
            $Filter = "*";
            $SortCriteria = "";
                    
            switch($Direction){
                case 0;
                    $ObjectID = $object['ParentID'];
                    $StartingIndex = $object['CurrentNo'] - 1;
                    if ($StartingIndex < 0){
                        $StartingIndex = 0;
                    }
                    $RequestedCount = '1';
                    break;
                case 1:
                    $this->n = $this->n - 1;
                    if ($this->n<0){$this->n=0;}
                    $this->PrevID[$this->n] = $object['ParentID'];
                    $ObjectID = $object['PrevID'];
                    $StartingIndex = 0;
                    $RequestedCount = '1'; 
                    break;
                case 2:
                    $this->n = $this->n + 1;
                    $this->PrevID[$this->n] = $object['ParentID'];
                    $ObjectID = $object['ObjectID'];
                    $StartingIndex = $object['CurrentNo'];
                    $RequestedCount = '1';
                    break;
                case 3:
                    $ObjectID = $object['ParentID'];
                    $StartingIndex = $object['CurrentNo'] + 1;
                    if ($StartingIndex > $object['TotalNo']){
                        $StartingIndex = $object['CurrentNo'] - 1;
                    }
                    $RequestedCount = '1';
                    break;
            } 
            if ($ObjectID === "leer" or $ObjectID === NULL){
                $ObjectID = "0";
            }
            $this->SendDebug('Starte ContentDirectory_Browse: ', "", 0);
            $this->SendDebug('$ObjectID: ', $ObjectID, 0);
            $this->SendDebug('$StartingIndex: ', $StartingIndex, 0);
            $this->SendDebug('$RequestedCount: ', $RequestedCount, 0);
                
            if ($StartingIndex < $object['TotalNo']){
                $BrowseResult = $this->ContentDirectory_Browse($ServerIP, $ServerPort, $Kernel, $ServerContentDirectory, $ObjectID, $BrowseFlag, $Filter, $StartingIndex, $RequestedCount, $SortCriteria);
                // Auswertung des Ergebnisses
                $Result_xml = $BrowseResult['Result'] ;
                
                $NumberReturned = intval($BrowseResult['NumberReturned']);
                $TotalMatches = intval($BrowseResult['TotalMatches']);
                //vom Server zurückgegebene Liste untersuchen
                $liste = $this->BrowseList($Result_xml);
                //$this->SendDebug('$liste: ', $liste, 0);
                if ($liste){
                    if($this->n>0){
                      $this->PrevID = $this->PrevID[($this->n-1)] ; 
                    }else{

                            $this->PrevID = $this->PrevID[0] ;
                    }
                    $content = array(
                        "ObjectID" => $liste[0]['id'],
                        "ParentID" => $liste[0]['parentid'],
                        "PrevID" =>  $this->PrevID,

                        "TotalNo" => $TotalMatches,
                        "CurrentNo" => $StartingIndex,
                        "class" => $liste[0]['class'],
                        "Title" => $liste[0]['title'],
                    );
                    if($liste[0]['parentid'] = ""){
                        $content["ParentID"] = "0";
                    }
                    //falls class = object.item.audioItem.musicTrack -> dann weiter DIDL abspeichern
                    if($content['class'] === 'object.item.audioItem.musicTrack'){
                            setvalue($this->GetIDForIdent("upnp_DIDLRessource"), $liste[0]['resource']);
                            setvalue($this->GetIDForIdent("upnp_Artist"), $liste[0]['artist']);
                            setvalue($this->GetIDForIdent("upnp_AlbumArtUri"), $liste[0]['albumArtURI']);    
                    }
                    //falls class = object.container.album.musicAlbum -> nächste Ebene browsen und Cover auslesen
                    $ObjectID = $content["ObjectID"];
                    $StartingIndex = 0;
                    $RequestedCount = '1';
                    if($content['class'] === 'object.container.album.musicAlbum'){
                        $BrowseResult = $this->ContentDirectory_Browse($ServerIP, $ServerPort, $Kernel, $ServerContentDirectory, $ObjectID, $BrowseFlag, $Filter, $StartingIndex, $RequestedCount, $SortCriteria);
                        // Auswertung des Ergebnisses
                        $Result_xml = $BrowseResult['Result'] ;
                        $NumberReturned = intval($BrowseResult['NumberReturned']);
                        $TotalMatches = intval($BrowseResult['TotalMatches']);
                        //vom Server zurückgegebene Liste untersuchen
                        $liste = $this->BrowseList($Result_xml);
                        //Musik Track gefunden nun Cover holen
                        if( $liste[0]['class'] === 'object.item.audioItem.musicTrack'){
                            setvalue($this->GetIDForIdent("upnp_DIDLRessource"), $liste[0]['resource']);
                            setvalue($this->GetIDForIdent("upnp_Artist"), $liste[0]['artist']);
                            setvalue($this->GetIDForIdent("upnp_AlbumArtUri"), $liste[0]['albumArtURI']);
                        }
                    }
                    $this->SendDebug('$content: ', $content, 0);
                    setvalue($this->GetIDForIdent("upnp_BrowseTitle"), $content['Title']);
                    setvalue($this->GetIDForIdent("upnp_BrowseContent"), serialize($content));
                    setvalue($this->GetIDForIdent("upnp_ObjectID"), $content['ObjectID']);
                }
            }
        }

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
			SetValue($this->GetIDForIdent("upnp_ClientArray"), 'suche Devices ...');
			$SSDP_Search_Array = $this->mSearch($ST_MR);
			//IPSLog('mSearch Ergebnis ',$SSDP_Search_Array);
			
			$SSDP_Array = $this->array_multi_unique($SSDP_Search_Array);
			//IPSLog('bereinigtes mSearch Ergebnis ',$SSDP_Array);
			
		 	$UPNP_Device_Array = $this->create_UPNP_Device_Array($SSDP_Array); 
			//IPSLog('create Device Ergebnis ',$UPNP_Device_Array);
			//Ergebnis wird als ARRAY in ID_CLIENT_ARRAY in Subfunctions gespeichert;
			$array = getvalue($this->GetIDForIdent("upnp_ClientArray"));
			if ($array){$result = true;}
			else{$result = false;}
		}
		if ($member == "server"){
			setvalue($this->GetIDForIdent("upnp_ServerArray"), 'suche Server ...');
			$SSDP_Search_Array = $this->mSearch($ST_MS);
			$SSDP_Array = $this->array_multi_unique($SSDP_Search_Array);
			//IPSLog('bereinigtes mSearch Ergebnis ',$SSDP_Array);
		 	$UPNP_Server_Array = $this->create_UPNP_Server_Array($SSDP_Array); 
			//Ergebnis wird als ARRAY in ID_Server_ARRAY in Subfunctions gespeichert;
			$array = getvalue($this->GetIDForIdent("upnp_ServerArray"));
			if ($array){$result = true;}
			else{$result = false;}
		}
		//IPSLog('FERTIG ', $result);
		return($result);
	}


	//*****************************************************************************
	/* Function: setClient($ClientName)
	...............................................................................
	Umschalten auf Client mit der IP Adresse
        ...............................................................................
	Parameters:  
            $ClientName - Friendly Name des Clients.
	--------------------------------------------------------------------------------
	Returns: 
            $key - Nummer des Client Arrays
        --------------------------------------------------------------------------------
	Status: checked 2018-06-10
	//////////////////////////////////////////////////////////////////////////////*/
	public function setClient(string $key){
            /*
            $which_key = "FriendlyName";
            $which_value = $ClientName;

            $key = $this->search_key($which_key, $which_value, $Client_Array);
            $this->SendDebug('Send','setze Client '.$ClientName , 0);
            $Client_Array[$key]['DeviceActiveIcon'] = "image/button_ok_blue_80x80.png";
            */
            $array = getvalue($this->GetIDForIdent("upnp_ClientArray"));
            $Client_Array = json_decode($array, JSON_OBJECT_AS_ARRAY);
            
            $ClientIP                   = $Client_Array[$key]['DeviceIP'];
            $ClientPort                 = $Client_Array[$key]['DevicePort'];
            $friendlyName               = $Client_Array[$key]['FriendlyName'];
            $ClientControlServiceType   = $Client_Array[$key]['DeviceControlServiceType'];
            $ClientControlURL           = $Client_Array[$key]['DeviceControlURL'];
            $ClientRenderingServiceType = $Client_Array[$key]['DeviceRenderingServiceType'];
            $ClientRenderingControlURL  = $Client_Array[$key]['DeviceRenderingControlURL'];
            $ClientIconURL              = $Client_Array[$key]['IconURL'];
            SetValue($this->GetIDForIdent("upnp_ClienIP"), $ClientIP);
            SetValue($this->GetIDForIdent("upnp_ClientPort"), $ClientPort);
            SetValue($this->GetIDForIdent("upnp_ClientName"), $friendlyName);
            setvalue($this->GetIDForIdent("upnp_ClientKey"), $key);
            //SetValue(UPNP_Device_ControlServiceType, $DeviceControlServiceType);
            SetValue($this->GetIDForIdent("upnp_ClientControlURL"), $ClientControlURL);
            //SetValue(UPNP_Device_RenderingServiceType, $DeviceRenderingServiceType);
            SetValue($this->GetIDForIdent("upnp_ClientRenderingControlURL"), $ClientRenderingControlURL);
            SetValue($this->GetIDForIdent("upnp_ClientIcon"), $ClientIconURL);
            return $key;
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
	Status: 
	//////////////////////////////////////////////////////////////////////////////*/
	public function setServer(string $key){
                
                /*
		//IPSLog("Starte Funktion : ", 'setServer');
		$which_key = "FriendlyName";
		$which_value = $serverName;
		$array = getvalue($this->GetIDForIdent("upnp_ServerArray"));
		$Server_Array = json_decode($array);
		$key = $this->search_key($which_key, $which_value, $Server_Array);
                */
                $array = getvalue($this->GetIDForIdent("upnp_ServerArray"));
                $Server_Array = json_decode($array, JSON_OBJECT_AS_ARRAY);
                
		$Server_Array[$key]['ServerActiveIcon'] = "image/button_ok_blue_80x80.png";
		$ServerIP                   = $Server_Array[$key]['ServerIP'];
		$ServerPort                 = $Server_Array[$key]['ServerPort'];
		$friendlyName               = $Server_Array[$key]['FriendlyName'];
		$ServerServiceType          = $Server_Array[$key]['ServerServiceType'];
		$ServerContentDirectory     = $Server_Array[$key]['ServerContentDirectory'];
		$ServerActiveIcon           = $Server_Array[$key]['ServerActiveIcon'];
		$ServerIconURL              = $Server_Array[$key]['IconURL'];
		SetValue($this->GetIDForIdent("upnp_ServerIP"), $ServerIP);
		SetValue($this->GetIDForIdent("upnp_ServerPort"), $ServerPort);
		SetValue($this->GetIDForIdent("upnp_ServerName"), $friendlyName);
		setvalue($this->GetIDForIdent("upnp_ServerKey"), $key);
		//SetValue(UPNP_Server_ServiceType, $ServerServiceType);
		SetValue($this->GetIDForIdent("upnp_ServerContentDirectory"), $ServerContentDirectory);
		SetValue($this->GetIDForIdent("upnp_ServerIcon"), $ServerIconURL);
		
		return $key;
	}	
	
	//*****************************************************************************
	/* Function: setVolume($value)
	...............................................................................
	UPNP Client Lautstärke einstellen
        ...............................................................................
	Parameters: 
            $value - 'up' // 'down' // 21
	--------------------------------------------------------------------------------
	Returns:  
            none.
	--------------------------------------------------------------------------------
	Status:  
	//////////////////////////////////////////////////////////////////////////////*/
	public function setVolume(string $value){
            $ClientIP   = getvalue($this->GetIDForIdent("upnp_ClienIP"));
            $ClientPort = getvalue($this->GetIDForIdent("upnp_ClientPort")); 
            $RenderingControlURL = getvalue($this->GetIDForIdent("upnp_ClientRenderingControlURL"));
            $UpnpVol = $this->GetVolume();
             
            switch ($value){
                case 'up':
                    $vol = intval($UpnpVol) + 5;
                    $this->SetVolume_AV($ClientIP, $ClientPort, $RenderingControlURL, (string)$vol);
                
                    break;
                case 'down':
                    $vol = intval($UpnpVol) - 5;
                    $this->SetVolume_AV($ClientIP, $ClientPort, $RenderingControlURL, (string)$vol);
                
                    break;
                default :
                    $this->SetVolume_AV($ClientIP, $ClientPort, $RenderingControlURL, $value);
            }
            
                    
        }

	//*****************************************************************************
	/* Function: setPlayMode($Playmode)
	...............................................................................
	Playmode auswählen
        ...............................................................................
	Parameters: 
            $Playmode = 'NORMAL' , 'RANDOM', 'REPEAT_ONE', REPEAT_ALL,
	--------------------------------------------------------------------------------
	Returns:  
            none.
	--------------------------------------------------------------------------------
	Status:  
	//////////////////////////////////////////////////////////////////////////////*/
	public function setPlayMode(string $Playmode){
            $ClientIP   = getvalue($this->GetIDForIdent("upnp_ClienIP"));
            $ClientPort = getvalue($this->GetIDForIdent("upnp_ClientPort")); 
            $ControlURL = getvalue($this->GetIDForIdent("upnp_ClientControlURL"));
                switch ($Playmode){
                    case 'NORMAL':
                        $value = 0;
                        break;
                    case 'RANDOM':
                         $value = 1;
                        break;
                    case 'REPEAT_ONE':
                         $value = 2;
                        break;
                    case 'REPEAT_ALL';
                        $value = 3;
                        break;
                }    
                $this->Playmode_AV( $ClientIP,  $ClientPort,  $ControlURL,  $Playmode);
                SetValue($this->GetIDForIdent("upnp_PlayMode"),$value);
                $this->SendDebug("set PlayMode to : ", $Playmode , 0);
        }        
        
	//*****************************************************************************
	/* Function: setMute($value)
	...............................................................................
	UPNP Client Stumm schalten
        ...............................................................................
	Parameters: 
            $value - 'on' 'off' 'toggle'
	--------------------------------------------------------------------------------
	Returns:  
            none
	--------------------------------------------------------------------------------
	Status:   checked 1.7.2018
	//////////////////////////////////////////////////////////////////////////////*/
	public function setMute($value){	
            $ClientIP   = getvalue($this->GetIDForIdent("upnp_ClienIP"));
            $ClientPort = getvalue($this->GetIDForIdent("upnp_ClientPort")); 
            $RenderingControlURL = getvalue($this->GetIDForIdent("upnp_ClientRenderingControlURL"));
            switch ($value){
		case 'on':
                    $this->SetMute_AV($ClientIP, $ClientPort, $RenderingControlURL, '1');
                    SetValue($this->GetIDForIdent("upnp_Mute"), true);
                    break;
		case 'off':
                    $this->SetMute_AV($ClientIP, $ClientPort, $RenderingControlURL, '0');
                    SetValue($this->GetIDForIdent("upnp_Mute"), false);
                    break;
 		case 'toggle':
                    $state = GetValue($this->GetIDForIdent("upnp_Mute"));
                    if($state){
                        $this->SetMute_AV($ClientIP, $ClientPort, $RenderingControlURL, '0');
                        SetValue($this->GetIDForIdent("upnp_Mute"), false);
                    }
                    else {
                        $this->SetMute_AV($ClientIP, $ClientPort, $RenderingControlURL, '1');
                        SetValue($this->GetIDForIdent("upnp_Mute"), true);
                    }
                    break;
                default :
                    $this->SendDebug("Error_setMute: ", 'wrong parameter.', 0);
              }    
        }
        





	//*****************************************************************************
	/* Function: play()
	...............................................................................
	vorgewählte Playlist abspielen ab Track und RealTime
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
		//IPSLog("start play", "play");
		$ControlURL = getvalue($this->GetIDForIdent("upnp_ClientControlURL"));
		$ClientIP   = getvalue($this->GetIDForIdent("upnp_ClienIP"));
		$ClientPort = getvalue($this->GetIDForIdent("upnp_ClientPort"));
        
        $PlaylistName =  getvalue($this->GetIDForIdent("upnp_PlaylistName"));
        $PlaylistFile = $PlaylistName.'.xml';
        $mediatype = getvalue($this->GetIDForIdent("upnp_MediaType"));
		$xml = simplexml_load_file($this->Kernel()."media/Multimedia/Playlist/".$mediatype."/".$PlaylistFile);
        $this->SendDebug("PLAY ", "Play-Liste lade".$PlaylistFile, 0); 
        // Status auf Play stellen
        setvalue($this->GetIDForIdent("upnp_Status"), 1);
        $this->SendDebug("PLAY ", "Status auf PLAY setzten", 0); 
        //track holen und zugeh. res und meta daten laden
 		$TrackNo = getvalue($this->GetIDForIdent("upnp_Track"));
        $track = ("Track".strval($TrackNo));
        $this->SendDebug("PLAY ", "Track Nummer holen: ".$track, 0); 
        $res = $xml->$track->resource; // gibt resource des Titels aus
        $this->SendDebug("PLAY ", $res, 0);
        $metadata = $xml->$track->metadata; // gibt resource des Titels aus
        $this->SendDebug("PLAY ", $metadata, 0);
		//UPNP_GetPositionInfo_Playing abschalten zum Ausführen des Transitioning
		//IPS_SetScriptTimer($this->GetIDForIdent("upnp_PlayInfo"), 0);
        $this->SetTimerInterval('upnp_PlayInfo', 0);
        $this->SendDebug("PLAY ", "Timer anhalten.", 0);
        if ($TrackNo == 1){	
			$this->Stop_AV($ClientIP, $ClientPort, $ControlURL);
		}
               // if ($ClientPort == '52235'){
                  //  $metadata='';
                //}

        //PlayMode setzen
        $this->setPlayMode("REPEAT_ALL");

		//Transport starten
            $this->SetAVTransportURI($ClientIP, $ClientPort, $ControlURL, (string) $res, (string) $metadata);
            $this->SendDebug("PLAY ", 'SetAVTransportURI', 0);
        //auf Anfangsposition stellen.
            //wenn Sequece Schalter true dann auf LoopStart Position stellen
            if($this->GetValue("upnp_Seq")){
                $position = $this->GetValue("upnp_LoopStart");
                $this->Seek_AV($ClientIP,  $ClientPort,  $ControlURL, $position );   
            }
            else{
                $position = $this->getvalue("upnp_RelTime");
                $this->Seek_AV($ClientIP,  $ClientPort,  $ControlURL, $position );  
            }


		//Stream ausführen	
		    $this->Play_AV($ClientIP, $ClientPort, $ControlURL);
            $this->SendDebug("PLAY ", 'Play_AV', 0);
            
		// Postion Timer starten
		//IPS_SetEventActive($this->GetIDForIdent("upnp_PlayInfo"), true);  // Aktivert Ereignis
            $this->SetTimerInterval('upnp_PlayInfo', 1000);
            $this->SendDebug("PLAY ", 'Position-Timer aktivieren', 0);
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
        $this->SetTimerInterval('upnp_PlayInfo', 0); //Timer ausschalten
		$ControlURL = getvalue($this->GetIDForIdent("upnp_ClientControlURL"));
		$ClientIP 	= getvalue($this->GetIDForIdent("upnp_ClienIP"));
		$ClientPort = getvalue($this->GetIDForIdent("upnp_ClientPort"));
		//track hochzählen
		$trackNo 		= getvalue($this->GetIDForIdent("upnp_Track"))+1;
		setvalue($this->GetIDForIdent("upnp_Track"), $trackNo);
        $track 	= ("Track".strval($trackNo));
        $this->SendDebug("PlayNextTrack ", 'nächster Titel: '.$track, 0);
        $PlaylistName =  getvalue($this->GetIDForIdent("upnp_PlaylistName"));
        $PlaylistFile = $PlaylistName.'.xml';
        $mediatype = getvalue($this->GetIDForIdent("upnp_MediaType"));
		$xml = simplexml_load_file($this->Kernel()."media/Multimedia/Playlist/".$mediatype."/".$PlaylistFile);
  
        $res = $xml->$track->resource; // gibt resource des Titels aus
        $this->SendDebug("PLAY ", $res, 0);
        $metadata = $xml->$track->metadata; // gibt resource des Titels aus
        $this->SendDebug("PLAY ", $metadata, 0);

 		$this->SetAVTransportURI($ClientIP, $ClientPort, $ControlURL, (string) $res, (string) $metadata);
        $this->Play_AV($ClientIP, $ClientPort, $ControlURL);
        $this->SendDebug("PlayNextTrack ", 'nächster Titel spielen. ', 0);
        setvalue($this->GetIDForIdent("upnp_Status"), 1);  //Status auf Play stellen
        $this->SendDebug("PlayNextTrack ", 'Status auf Play stellen. ', 0);
        $this->SetTimerInterval('upnp_PlayInfo', 1000); // Timer einschalten
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
            $ControlURL = getvalue($this->GetIDForIdent("upnp_ClientControlURL"));
            $ClientIP   = getvalue($this->GetIDForIdent("upnp_ClienIP"));
            $ClientPort = getvalue($this->GetIDForIdent("upnp_ClientPort"));
            $mediatype = getvalue($this->GetIDForIdent("upnp_MediaType"));
            $currentTrack = getvalue($this->GetIDForIdent("upnp_Track"));
            $currentRelTime = getvalue($this->GetIDForIdent("upnp_RelTime"));
            //letzte 4 Zeichen holen und in Zahl umwandeln
            $PlayListName = getvalue($this->GetIDForIdent("upnp_PlaylistName"));
            $mediaNo = intval(substr($PlayListName, -4));
            $this->SendDebug('STOP:$mediaNo', $mediaNo, 0);

            $this->SendDebug('STOP', 'Stream stoppen', 0);
            /*Timer abschalten--------------------------------------------------------*/
            $this->SetTimerInterval('upnp_PlayInfo', 0);
            /*Stream stoppen--------------------------------------------------------*/
            $this->Stop_AV($ClientIP, $ClientPort, $ControlURL);
            $this->SendDebug('STOP', 'LastPos: '.$currentRelTime, 0);
			/* Transport Status abfragen */
			$Playing = $this->GetTransportInfo($ClientIP, $ClientPort, $ControlURL);  
            setvalue($this->GetIDForIdent("upnp_Transport_Status"), $Playing['CurrentTransportState']);
            //Player Status STOP setzen
            setvalue($this->GetIDForIdent("upnp_Status"), 3);
            //Playliste holen und letzte Position abspeichern
            $PlaylistDB = simplexml_load_file($this->Kernel()."media/Multimedia/Playlist/".$mediatype."/DB.xml");
            $PlaylistDB->media[$mediaNo]->lasttrack = $currentTrack;
            $PlaylistDB->media[$mediaNo]->lastpos = $currentRelTime;
            $PlaylistDB->asXML($this->Kernel()."media/Multimedia/Playlist/".$mediatype."/DB.xml");
            $this->SendDebug('Stop:PlaylistDB', (string) $PlaylistDB->media[$mediaNo]->lastpos, 0);
 
            //Transport Status zurücksetzen auf Anfang zurücksetzen
            //setvalue($this->GetIDForIdent("upnp_Transport_Status"), '');
            
            //$Playlist = getvalue($this->GetIDForIdent("upnp_Playlist_XML"));
            //$xml = new SimpleXMLElement($Playlist);
            //$SelectedFile = GetValue($this->GetIDForIdent("upnp_Track")); 
            //$track = ("Track".($SelectedFile));

            //$DIDL_Lite_Class = $xml->$track->class;

                
            /*Timer abschalten--------------------------------------------------------*/
           // $class = $DIDL_Lite_Class;
                
		//if($class == "object.item.audioItem.musicTrack"){
                    //$this->SetTimerInterval('upnp_PlayInfo', 0);
		//}

		//if($class == "object.item.videoItem") {
			//IPS_SetScriptTimer(UPNP_GetPositionInfo_Playing, 0); //GetPositionInfo abschalten
		//}

		//if($class == "object.item.imageItem.photo"){
				//IPS_SetScriptTimer(UPNP_SlideShow, 0); //GetPositionInfo abschalten
		//}
                
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
		$ControlURL = getvalue($this->GetIDForIdent("upnp_ClientControlURL"));
		$ClientIP 	= getvalue($this->GetIDForIdent("upnp_ClienIP"));
		$ClientPort = getvalue($this->GetIDForIdent("upnp_ClientPort"));
        $this->Pause_AV($ClientIP, $ClientPort, $ControlURL);
        // Status auf Pause stellen
        setvalue($this->GetIDForIdent("upnp_Status"), 2);
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
        // Status auf Next stellen
        setvalue($this->GetIDForIdent("upnp_Status"), 4);
        $maxTrack = GetValue($this->GetIDForIdent("upnp_NoTracks")); 
        $currentTrack = GetValue($this->GetIDForIdent("upnp_Track")); 
        if($currentTrack < $maxTrack-1){
            $this->SendDebug('next', $currentTrack."- ".$maxTrack, 0);
            $newTrack = $currentTrack + 1;
            SetValue($this->GetIDForIdent("upnp_Track"), $newTrack);
            //RealTime zurücksetzen, da play von Anfang des Tracks
            SetValue($this->GetIDForIdent("upnp_RelTime"), "0:00:00");
            $this->stop();
            $this->play();
        }
        else {
            // es ist bereits der letzte Track
            $this->SendDebug('next', "das ist bereits der Letzte Track", 0);
        }

	}	
	
	
	
	//*****************************************************************************
	/* Function: Previous()
        -------------------------------------------------------------------------------
        springe zum vorherigem Track 
        ...............................................................................
	Parameters:
            none.
        --------------------------------------------------------------------------------
	Returns:
            none.
        //////////////////////////////////////////////////////////////////////////////*/
	public function previous()
	{	
        // Status auf Next stellen
        setvalue($this->GetIDForIdent("upnp_Status"), 4);
        $currentTrack = GetValue($this->GetIDForIdent("upnp_Track")); 
        if($currentTrack > 0){
            $newTrack = $currentTrack - 1;
            SetValue($this->GetIDForIdent("upnp_Track"), $newTrack);
            //RealTime zurücksetzen, da play von Anfang des Tracks
            SetValue($this->GetIDForIdent("upnp_RelTime"), "0:00:00");
            $this->stop();
            $this->play();
        }
        else {
            // es gibt  nur einen Track
        }

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
            $ControlURL = getvalue($this->GetIDForIdent("upnp_ClientControlURL"));
            $ClientIP 	= getvalue($this->GetIDForIdent("upnp_ClienIP"));
            $ClientPort = getvalue($this->GetIDForIdent("upnp_ClientPort"));
            $postime = getvalue($this->GetIDForIdent("upnp_RelTime"));
            $seconds = 20;
            $time_now = "00:00:00.000";
            $this->SendDebug('seekForward', $postime, 0);
            $position = date("H:i:s.000", (strtotime(date($postime)) + $seconds));

            $this->SendDebug('seekForward', $position, 0);
            $this->Seek_AV($ClientIP, $ClientPort, $ControlURL, $position);
    }
    //*****************************************************************************
	/* Function: seekStart()
        -------------------------------------------------------------------------------
        spult Lied zum Anfang
        ...............................................................................
	Parameters:
            none.
        --------------------------------------------------------------------------------
	Returns:
            none.
        //////////////////////////////////////////////////////////////////////////////*/
	public function seekStart(){	
        $ControlURL = getvalue($this->GetIDForIdent("upnp_ClientControlURL"));
        $ClientIP 	= getvalue($this->GetIDForIdent("upnp_ClienIP"));
        $ClientPort = getvalue($this->GetIDForIdent("upnp_ClientPort"));
        $position = "0:00:00";
         
        $this->SendDebug('seekStartPosition', $position, 0);
        $this->Seek_AV($ClientIP, $ClientPort, $ControlURL, (string) $position);
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
            $ControlURL = getvalue($this->GetIDForIdent("upnp_ClientControlURL"));
            $ClientIP 	= getvalue($this->GetIDForIdent("upnp_ClienIP"));
            $ClientPort = getvalue($this->GetIDForIdent("upnp_ClientPort"));
            $postime = getvalue($this->GetIDForIdent("upnp_RelTime"));
            $seconds = 20;
            $time_now = "00:00:00.000";
            $this->SendDebug('seekForward', $postime, 0);
            $position = date("H:i:s.000", (strtotime(date($postime)) - $seconds));
            $this->SendDebug('seekBackward', $position, 0);
            $this->Seek_AV($ClientIP, $ClientPort, $ControlURL, (string) $position);
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
	public function seekPos($Seek){	
            $ControlURL = getvalue($this->GetIDForIdent("upnp_ClientControlURL"));
            $ClientIP 	= getvalue($this->GetIDForIdent("upnp_ClienIP"));
            $ClientPort = getvalue($this->GetIDForIdent("upnp_ClientPort"));
            $GetPositionInfo = $this->GetPositionInfo($ClientIP, $ClientPort, $ClientControlURL);
            $Duration = $GetPositionInfo['TrackDuration'];
            $duration = explode(":", $Duration);
            $seconds = round(((($duration[0] * 3600) + ($duration[1] * 60) + ($duration[2])) * ($Seek/100)), 0, PHP_ROUND_HALF_UP);
            $position = gmdate('H:i:s', $seconds);
            $this->Seek_AV($ClientIP,  $ClientPort,  $ControlURL, $position );
	}
        
	//*****************************************************************************
	/* Function: loadPlaylist($AlbumNo)
	...............................................................................
	Playlist aus Datei laden (XML) und in Variable Playlist_XML schreiben
	...............................................................................
	Parameters:  
            $AlbumNo - Album Nummer = 0  für die Erste Playliste.
            $mediatype = "Musik" // "Audio" // "Video" // "Foto"
	--------------------------------------------------------------------------------
	Returns:  
            $xml - Playlist as XML 
	--------------------------------------------------------------------------------
	Status:  
	//////////////////////////////////////////////////////////////////////////////*/
	public function loadPlaylist(string $AlbumNo, string $mediatype){	
        $Server = getvalue($this->GetIDForIdent("upnp_ServerName"));   

            $this->SendDebug('Send','lade Play Liste' , 0);
            
            setvalue($this->GetIDForIdent("upnp_MediaType"), $mediatype);
            $PlaylistName = $Server.$AlbumNo;
            setvalue($this->GetIDForIdent("upnp_PlaylistName"), $PlaylistName);
            $PlaylistFile = $PlaylistName.'.xml';

            $Playlist = simplexml_load_file($this->Kernel()."media/Multimedia/Playlist/".$mediatype."/".$PlaylistFile);
          
            if(!$Playlist){
                $this->SendDebug('loadPlaylist','Playlist not found.' , 0);
            }else{
                $this->SendDebug('loadPlaylist','Playlist found.' , 0);
                //Daten aus Erstem Datensatz der Playliste holen
                
                $album = (string) $Playlist->Track0->album; //  
                $cover = (string) $Playlist->Track0->albumArtURI; //  
                $artist = (string) $Playlist->Track0->artist; // 
                $tracks = $Playlist->count();
            
                setvalue($this->GetIDForIdent("upnp_Album"), $album);
                setvalue($this->GetIDForIdent("upnp_AlbumArtUri"), $cover);
                setvalue($this->GetIDForIdent("upnp_Artist"), $artist);
                setvalue($this->GetIDForIdent("upnp_NoTracks"), $tracks);
                setvalue($this->GetIDForIdent("upnp_Actor"), "");
                setvalue($this->GetIDForIdent("upnp_Title"), "");
                // Playlist Datenbank laden
               
                $PlaylistDB = simplexml_load_file($this->Kernel()."media/Multimedia/Playlist/".$mediatype."/DB.xml");
                $lasttrack = (string) $PlaylistDB->media[intval($AlbumNo)]->lasttrack; // letzter abgespielter track
                $lastpos = (string) $PlaylistDB->media[intval($AlbumNo)]->lastpos; // letzte Position des tracks
                setvalue($this->GetIDForIdent("upnp_Track"), intval($lasttrack));
                setvalue($this->GetIDForIdent("upnp_RelTime"), $lastpos);
            
            }

   


 
            $vars 				= explode(".", $PlaylistFile);
            $PlaylistName 			= $vars[0];
            $PlaylistExtension		= $vars[1];

            //$xml = new SimpleXMLElement($Playlist);

            return $Playlist;
         
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
		$ControlURL = getvalue($this->GetIDForIdent("upnp_ClientControlURL"));
		$ClientIP   = getvalue($this->GetIDForIdent("upnp_ClienIP"));
		$ClientPort = getvalue($this->GetIDForIdent("upnp_ClientPort"));
		$RenderingControlURL = getvalue($this->GetIDForIdent("upnp_ClientRenderingControlURL"));
		
		$fsock = fsockopen($ClientIP, $ClientPort, $errno, $errstr, $timeout = '1');
		if ( !$fsock ){
                    //nicht erreichbar --> Timer abschalten--------------------------------
                    $this->SendDebug('Send', $ClientIP.'ist nicht erreichbar!', 0);
                    // Status auf Stop stellen
                    setvalue($this->GetIDForIdent("upnp_Status"), 3);
		}
		else{
			/*///////////////////////////////////////////////////////////////////////////
			Auswertung nach CurrentTransportState "PLAYING" oder "STOPPED"
			bei "PLAYING" -> GetPositionInfo -> Progress wird angezeigt
			bei "STOPPED" -> nächster Titel wird aufgerufen
			/*///////////////////////////////////////////////////////////////////////////
            $PlaylistName =  getvalue($this->GetIDForIdent("upnp_PlaylistName"));
            $PlaylistFile = $PlaylistName.'.xml';
            $mediatype = getvalue($this->GetIDForIdent("upnp_MediaType"));
            $xml = simplexml_load_file($this->Kernel()."media/Multimedia/Playlist/".$mediatype."/".$PlaylistFile);

            $SelectedTrack = GetValue($this->GetIDForIdent("upnp_Track")); 
			$track = ("Track".($SelectedTrack));
				
			$DIDL_Lite_Class = $xml->$track->class;
			$this->SendDebug("GetPosInfo ", 'class des Tracks abfragen: '.$DIDL_Lite_Class , 0);

			/* Transport Status abfragen */
			$PlayMode = $this->GetTransportSettings($ClientIP, $ClientPort,  $ControlURL);
                        //$this->IPSLog("Playmode Array", $PlayMode); 
                        $this->SendDebug("GetPosInfo ", 'Playmode: '.$PlayMode['PlayMode'] , 0);
                        switch ($PlayMode['PlayMode']) {
                            case 'NORMAL':
                                $PlayModeIndex = 0;
                                break;
                            case 'RANDOM':
                                $PlayModeIndex = 1;
                                break;
                            case 'REPEAT_ONE':
                                $PlayModeIndex = 2;
                                break;   
                            case 'REPEAT_ALL':
                                $PlayModeIndex = 3;
                                break;
                            default:
                                break;
                        }
                        setvalue($this->GetIDForIdent("upnp_PlayMode"), $PlayModeIndex);
 			/* Transport Status abfragen */
			$Playing = $this->GetTransportInfo($ClientIP, $ClientPort, $ControlURL);                       
 			setvalue($this->GetIDForIdent("upnp_Transport_Status"), $Playing['CurrentTransportState']);
            $this->SendDebug("GetPosInfo ", 'Transport Status abfragen: '.$Playing['CurrentTransportState'] , 0);
            $Status = getvalue($this->GetIDForIdent("upnp_Status"));
            $this->SendDebug("GetPosInfo ", "Play Status abfragen: ".$Status, 0); 
			//Transport Status auswerten
			switch ($Playing['CurrentTransportState']){
                            case 'NO_MEDIA_PRESENT':
                                $Status = getvalue($this->GetIDForIdent("upnp_Status"));
                                $this->SendDebug("GetPosInfo ", 'NO_MEDIA_PRESENT', 0); 
                                $this->SetTimerInterval('upnp_PlayInfo', 0);  // DeAktiviert Ereignis
                                setvalue($this->GetIDForIdent("upnp_Progress"),0);
                                
                            break;
                            case 'STOPPED':
                                $Status = getvalue($this->GetIDForIdent("upnp_Status"));
                                // Status steht auf Play - komplette Playlist abspielen
                                if ($Status === 1){
                                    $this->SendDebug("GetPosInfo ", 'Titel ist zu Ende', 0);
                                    // wurde das Ende der Playlist erreicht, falls nicht nächsten Track spielem
                                    $lastTrack = getvalue($this->GetIDForIdent("upnp_Track"));
                                    $maxTrack = getvalue($this->GetIDForIdent("upnp_NoTracks"))-1;
                                    $this->SendDebug("GetPosInfo ", 'lastTrack: '.$lastTrack.' - maxTrack: '.$maxTrack, 0);
                                    if ($lastTrack >= 0  AND $lastTrack < $maxTrack){
                                        $this->SendDebug("GetPosInfo ", 'nächster Titel Spielen', 0);
                                            setvalue($this->GetIDForIdent("upnp_Status"), 4);
                                            $this->PlayNextTrack();		
                                    }
                                    else {
                                        $this->SendDebug("GetPosInfo ", 'letzter Titel wurde gespielt alles zurücksetzen', 0);
                                        $this->SetTimerInterval('upnp_PlayInfo', 0);  // DeAktivert Ereignis
                                        setvalue($this->GetIDForIdent("upnp_Progress"), 0);
                                        setvalue($this->GetIDForIdent("upnp_Track"), 0);
                                        setvalue($this->GetIDForIdent("upnp_RelTime"), "0:00:00");
                                        $this->stop();
                                    }
                                }
                                // Status steht auf Stop
                                elseif($Status === 3) {
                                    $this->SendDebug("GetPosInfo ", 'Stop wurde gedrückt', 0);
                                    $this->SetTimerInterval('upnp_PlayInfo', 0);  // DeAktiviert Ereignis
                                    setvalue($this->GetIDForIdent("upnp_Progress"), 0);
                                   
                                    $this->stop();    
                                }
                            break;
                            case 'PLAYING':
                                if($DIDL_Lite_Class == "object.item.audioItem.musicTrack"){
                                    $this->SendDebug("GetPosInfo ", 'progress aufrufen', 0);
                                    $fortschritt = $this->progress($ClientIP, $ClientPort, $ControlURL);
                                }
                                else if($DIDL_Lite_Class == "object.item.videoItem.movie"){
                                    $this->SendDebug("GetPosInfo ", 'progress aufrufen', 0);
                                    $fortschritt = $this->progress($ClientIP, $ClientPort, $ControlURL);
                                    //Falls Sequence Mode aktive dann stop wenn LoopStop erreicht.
                                    if($this->GetValue("upnp_Seq")){
                                        $TrackDuration = $this->GetValue("upnp_TrackDuration");
                                        $duration = explode(":", $TrackDuration);
                                        $LoopStop = $this->GetValue("upnp_LoopSTop");
                                        $StopTime = explode(":", $LoopStop);
                                        $StopPosition = round((((($StopTime[0] * 3600) + ($StopTime[1] * 60) + ($StopTime[2]))* 100) / (($duration[0] * 3600) + ($duration[1] * 60) + ($duration[2]))), 0, PHP_ROUND_HALF_UP);
                                        if($fortschritt >= $StopPosition){
                                            $this->stop();
                                        }
                                    }
                                }
                                else if($DIDL_Lite_Class == "object.item.imageItem.photo"){
                                        //include_once ("57444 /*[Multimedia\Core\UPNP_SlideShow]*/.ips.php"); //UPNP_SlideShow
                                }
                                else {$this->stop();}
                            break;
                            case 'PAUSED_PLAYBACK':

                            break;
			}
		}
	}
        
	//*****************************************************************************
	/* Function: GetVolume()
	...............................................................................
	Lautstärke auslesen des UPNP Clients
	...............................................................................
	Parameters:
            none.
	--------------------------------------------------------------------------------
	Returns:  
            $UpnpVol - Lautstärke als integer
	--------------------------------------------------------------------------------
	Status:  
	//////////////////////////////////////////////////////////////////////////////*/
	public function GetVolume(){ 
            $ClientIP   = getvalue($this->GetIDForIdent("upnp_ClienIP"));
            $ClientPort = getvalue($this->GetIDForIdent("upnp_ClientPort"));
            $RenderingControlURL = getvalue($this->GetIDForIdent("upnp_ClientRenderingControlURL"));
            $UpnpVol = $this->GetVolume_AV($ClientIP, $ClientPort, $RenderingControlURL);
            setvalue($this->GetIDForIdent("upnp_Volume"), $UpnpVol);
            return $UpnpVol;
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
	Protected function progress(string $ClientIP, string $ClientPort, string $ControlURL){	
            $GetPositionInfo = $this->GetPositionInfo($ClientIP, $ClientPort, $ControlURL);
             
            $Duration = (string) $GetPositionInfo['TrackDuration']; //Duration
            setvalue($this->GetIDForIdent("upnp_TrackDuration"), (string) $Duration);           
            $RelTime = (string) $GetPositionInfo['RelTime']; //RelTime
            setvalue($this->GetIDForIdent("upnp_RelTime"), (string) $RelTime);          
            $this->SendDebug("progress ", ' GetRelTIME PositionInfo: '.$RelTime, 0);
            $TrackMeta = (string) $GetPositionInfo['TrackMetaData'];
            $b = htmlspecialchars_decode($TrackMeta);
             
            $didlXml = simplexml_load_string($b); 
            $this->SendDebug("progress-DIDL INFO ", $TrackMeta , 0);
            $creator = (string)$didlXml->item[0]->xpath('dc:creator')[0];
            $title = (string) $didlXml->item[0]->xpath('dc:title')[0];

            if(array_key_exists(0, $didlXml->item[0]->xpath('upnp:album')))
            {
                        $album = (string)$didlXml->item[0]->xpath('upnp:album')[0];
            }
            else{
                $album = (string)$didlXml->item[0]->xpath('dc:description')[0];
            }
            if(array_key_exists(0, $didlXml->item[0]->xpath('upnp:originalTrackNumber')))
            {
                        $TrackNo = (string)$didlXml->item[0]->xpath('upnp:originalTrackNumber')[0];
            }
            else{
                $TrackNo =  0;
            }
            if(array_key_exists(0, $didlXml->item[0]->xpath('upnp:artist')))
            {
                        $actor = (string)$didlXml->item[0]->xpath('upnp:artist')[0];
            }
            else{
                $actor = (string)$didlXml->item[0]->xpath('upnp:actor')[0];
            }
            $AlbumArtURI = (string)$didlXml->item[0]->xpath('upnp:albumArtURI')[0];
            $genre = (string)$didlXml->item[0]->xpath('upnp:genre')[0];
            //$date = (string)$didlXml->item[0]->xpath('dc:date')[0];

            setvalue($this->GetIDForIdent("upnp_Artist"),  $creator);
            setvalue($this->GetIDForIdent("upnp_Title"),  $title);
            setvalue($this->GetIDForIdent("upnp_Album"),  $album);		
            setvalue($this->GetIDForIdent("upnp_TrackNo"),  $TrackNo);
            setvalue($this->GetIDForIdent("upnp_Actor"),  $actor);
            //setvalue($this->GetIDForIdent("upnp_Date"),  $date);
            //setvalue($this->GetIDForIdent("upnp_AlbumArtUri"), (string) $AlbumArtURI);
            setvalue($this->GetIDForIdent("upnp_Genre"),  $genre);
                function get_time_difference($Duration, $RelTime){
                        $duration = explode(":", $Duration);
                        $reltime = explode(":", $RelTime);
                        $time_difference = round((((($reltime[0] * 3600) + ($reltime[1] * 60) + ($reltime[2]))* 100) / (($duration[0] * 3600) + ($duration[1] * 60) + ($duration[2]))), 0, PHP_ROUND_HALF_UP);
                        return ($time_difference);
                }
            if($Duration == "0:00:00"){
                    $Duration = (string) $GetPositionInfo['AbsTime']; //AbsTime
            }
            $Progress = get_time_difference($Duration, $RelTime);
            SetValueInteger($this->GetIDForIdent("upnp_Progress"), $Progress);
            return $Progress;
	}


	//*****************************************************************************
	/* Function: browseContainerServer($ObjectID)
	...............................................................................
	Liest alle Objecte eines Containers/Folders mit ID aus 
	...............................................................................
	Parameters:  
            $ObjectID - ID des Objectes.
	--------------------------------------------------------------------------------
	Returns:    
            * _array: [$liste][$i]_.
              * - //01: container/item.
                - //02: id.
                - //03: refID.
                - //04: parentid.
                - //05: restricted.
                - //06: artist.
                - //07: album.
                - //08: title.
                - //09: resource.
                - //10: duration.
                - //11: size.
                - //12: bitrate.
                - //13: albumArtURI.
                - //14: genre.
                - //15: date.
                - //16: originalTrackNumber.
                - //17: class.
                - //18: extension.
	--------------------------------------------------------------------------------
	Status:  
	//////////////////////////////////////////////////////////////////////////////*/
	Public function browseContainerServer(string $ObjectID){	
		//IPSLog("Starte Funktion: browseServer mit : ",$ObjectID);
		$ServerContentDirectory = GetValue($this->GetIDForIdent("upnp_ServerContentDirectory"));
		$ServerIP= GetValue($this->GetIDForIdent("upnp_ServerIP"));
		$ServerPort= GetValue($this->GetIDForIdent("upnp_ServerPort"));
		
		//Suchvariablen-----------------------------------------------------------------
		$BrowseFlag = "BrowseDirectChildren"; //"BrowseMetadata"; //"BrowseDirectChildren";
		$Filter = "*"; //GetValue();
		$StartingIndex = "0"; //GetValue();
		$RequestedCount = "0"; //GetValue();
		$SortCriteria = ""; //GetValue();
		
		$Kernel = $this->Kernel();

		//Function ContentDirectory_Browse aufrufen-------------------------------------
        try {
            $BrowseResult = $this->ContentDirectory_Browse ( $ServerIP, $ServerPort, $Kernel, $ServerContentDirectory, $ObjectID, $BrowseFlag, $Filter, $StartingIndex, $RequestedCount, $SortCriteria);
            sleep(2);
            $Result_xml = $BrowseResult['Result'] ;
            $NumberReturned = $BrowseResult['NumberReturned'];
            $TotalMatches = $BrowseResult['TotalMatches'];
            $UpdateID = $BrowseResult['UpdateID'];
                    
            if ($NumberReturned == $TotalMatches){
                if ($NumberReturned == "0"){
                    $liste[0]['title']="leer";
                    $liste[0]['id']="0";
                    $liste[0]['artist'] = "";
                    $liste[0]['resource'] = "";
                    $liste[0]['parentid'] = "";
                    $liste[0]['albumArtURI'] = "";
                    }
                else{
                    // Result mit gefundenden media files bearbeiten 
                    $liste = $this->BrowseList($Result_xml);
                            }
                    }
            //wenn nur Teilrückgabe, dann mehrfach auslesen	  
            if ($NumberReturned < $TotalMatches) {
                $liste = $this->BrowseList($Result_xml);
                for($i = 0; $NumberReturned*$i < $TotalMatches; ++$i){
                    $StartingIndex = $NumberReturned*$i;
                    $BrowseArray_add =  $this->ContentDirectory_Browse ( $ServerIP, $ServerPort, $Kernel, $ServerContentDirectory, $ObjectID, $BrowseFlag, $Filter, $StartingIndex, $RequestedCount, $SortCriteria);
                    $BrowseResult_add = $BrowseArray_add['Result'];
                    $liste_add = $this->BrowseList($BrowseResult_add);
                    $liste = array_merge($liste, $liste_add);
                }
            }
                    
                    //$this->SendDebug('browseContainerServer', $liste, 0);
            return $liste;

        } catch (Exception $e) {
            $this->Meldung('Exception abgefangen: '.  $e->getMessage(). "\n");
            return false;
        }
        



	}


	//*****************************************************************************
	/* Function: syncLib($Mediatype)
	...............................................................................
         holt alle Ordner - ID's vom Server und erstellt alle Playlisten neu-
         Wichtig: das Stammverzeichnis ist fix im Code eingetragen und´muss stimmen. 
	...............................................................................
	Parameters:  
            $Mediatype - 'Musik' // 'Audio' // 'Foto' // 'Video'
	--------------------------------------------------------------------------------
	Returns:   
      
	--------------------------------------------------------------------------------
	Status:  
    //////////////////////////////////////////////////////////////////////////////*/
    Public function syncLib(string $Mediatype){
        $cont = $this->getContainerServer($Mediatype);
        if($cont != false){
            $ok = $this->syncDB($cont, $Mediatype);
        }
        if($ok){
            $this->createAllPlaylist($Mediatype);
        }
        
    }

	//*****************************************************************************
	/* Function: FindStartID($Server)
	...............................................................................
          eines angewählten Servers ab Stammverzeichnis 
        $Mediatype oder ab Filter auslesen 
       
	...............................................................................
	Parameters:  
            $Mediatype - 'Musik' // 'Audio' // 'Foto' // 'Video'
	--------------------------------------------------------------------------------
	Returns:   
    
	--------------------------------------------------------------------------------
	Status:  
    //////////////////////////////////////////////////////////////////////////////*/
    Public function FindPlexID(){
        $ServerName = $this->GetValue("upnp_ServerName");
        if($ServerName == "Plex"){
            $ServerContentDirectory = $this->GetValue("upnp_ServerContentDirectory");
            $ServerIP= $this->GetValue("upnp_ServerIP");
            $ServerPort = $this->GetValue("upnp_ServerPort");

    
            //Suchvariablen-----------------------------------------------------------------
            $BrowseFlag = "BrowseDirectChildren"; //"BrowseMetadata"; //"BrowseDirectChildren";
            $Filter = "*"; //GetValue();
            $StartingIndex = 0; //GetValue();
            $RequestedCount = "0"; //GetValue();
            $SortCriteria = ""; //GetValue();

            $Kernel = str_replace("\\", "/", IPS_GetKernelDir());
            $ObjectID = "0";
            try {
                //Function ContentDirectory_Browse aufrufen-------------------------------------
                $BrowseResult = $this->ContentDirectory_Browse ($ServerIP, $ServerPort, $Kernel, $ServerContentDirectory, $ObjectID, $BrowseFlag, $Filter, $StartingIndex, $RequestedCount, $SortCriteria);
                        
            } catch (Exception $e) {
                //echo 'Exception abgefangen: ',  $e->getMessage(), "\n";
            }
            $Result_xml = $BrowseResult['Result'] ;
            $liste = $this->BrowseList($Result_xml);
            //Ergebnisse aus Object ID 0
            foreach($liste as $typ){
                        switch ($typ['title']) {
                            case 'Video':
                                $OID['video'] = $typ['id']; 
                                break;
                            case 'Music':
                                $OID['music'] = $typ['id'];
                                break;
                            case 'Photos':
                                $OID['photo'] = $typ['id'];
                                break;
                        } 
            }

            foreach($OID as  $ObjectID){		 
                try {
                        //Function ContentDirectory_Browse aufrufen-------------------------------------
                        $BrowseResult = $this->ContentDirectory_Browse ($ServerIP, $ServerPort, $Kernel, $ServerContentDirectory, $ObjectID, $BrowseFlag, $Filter, $StartingIndex, $RequestedCount, $SortCriteria);
                            
                } catch (Exception $e) {
                    //echo 'Exception abgefangen: ',  $e->getMessage(), "\n";
                }
                $Result_xml = $BrowseResult['Result'] ;
                $liste = $this->BrowseList($Result_xml);
                foreach($liste as $typ){
                            switch ($typ['title']) {
                                case 'Videos':
                                    $OID['video'] = $typ['id']; 
                                    break;
                                case 'Musik':
                                    $OID['music'] = $typ['id'];
                                    break;
                                case 'Fotos':
                                    $OID['photo'] = $typ['id'];
                                    break;
                                case 'AudioBook':
                                    $OID['audio'] = $typ['id'];
                                    break;
                            } 
                }
            }
            foreach($OID as  $ObjectID){		 
                try {
                        //Function ContentDirectory_Browse aufrufen-------------------------------------
                        $BrowseResult = $this->ContentDirectory_Browse ($ServerIP, $ServerPort, $Kernel, $ServerContentDirectory, $ObjectID, $BrowseFlag, $Filter, $StartingIndex, $RequestedCount, $SortCriteria);
                } catch (Exception $e) {
                    //echo 'Exception abgefangen: ',  $e->getMessage(), "\n";
                }
                $Result_xml = $BrowseResult['Result'] ;
                $liste = $this->BrowseList($Result_xml);
                foreach($liste as $typ){
                            switch ($typ['parentid']) {
                                case  $OID['video']:
                                    if($typ['title']=="By Folder"){
                                        $OID['video'] = $typ['id']; 
                                    }
                                    break;
                                case $OID['music']:
                                    if($typ['title']=="By Folder"){
                                        $OID['music'] = $typ['id'];
                                    }
                                    break;
                                case $OID['photo']:
                                    if($typ['title']=="All Photos"){
                                        $OID['photo'] = $typ['id'];
                                    }
                                    break;
                                case $OID['audio']:
                                    if($typ['title']=="By Folder"){
                                        $OID['audio'] = $typ['id'];
                                    }
                                    break;
                            } 
                }
            }
            $this->SetValue("upnp_PlexID", serialize($OID)); 
        }
        $Meldung = "Start ObjectID's für Plex Server: ".$OID;
        $this->SendDebug('UPNP: ', $Meldung, 0);
        return $OID;
    }



	//*****************************************************************************
	/* Function: getContainerServer($Mediatype)
	...............................................................................
        Alle Container/Folder eines angewählten Servers ab Stammverzeichnis 
        $Mediatype oder ab Filter auslesen 
        Ergebnis wird als compressed Array in File geschrieben
        /media/Multimedia/Playlist/Musik/".$ServerName."_Musik_Container.con"
	...............................................................................
	Parameters:  
            $Mediatype - 'Musik' // 'Audio' // 'Foto' // 'Video'
	--------------------------------------------------------------------------------
	Returns:   
            * array: [$container]
		  - ['class']  
		  - ['id']  
          - ['title']  
          - ['album'] 
          - ['no'] 
	--------------------------------------------------------------------------------
	Status:  
	//////////////////////////////////////////////////////////////////////////////*/
	Public function getContainerServer(string $Mediatype){
        $this->Meldung( 'suche Verzeichnisse"');
		$ServerContentDirectory = GetValue($this->GetIDForIdent("upnp_ServerContentDirectory"));
		$ServerIP= GetValue($this->GetIDForIdent("upnp_ServerIP"));
		$ServerPort = GetValue($this->GetIDForIdent("upnp_ServerPort"));
		$ServerName = GetValue($this->GetIDForIdent("upnp_ServerName"));
        $Meldung = "Starte Funktion: getContainerServer mit : ".$Mediatype. " - ".$ServerName ;
        $this->SendDebug('UPNP: ', $Meldung, 0);
		//Suchvariablen-----------------------------------------------------------------
		$BrowseFlag = "BrowseDirectChildren"; //"BrowseMetadata"; //"BrowseDirectChildren";
		$Filter = "*"; //GetValue();
		$StartingIndex = 0; //GetValue();
		$RequestedCount = "0"; //GetValue();
		$SortCriteria = ""; //GetValue();

		$Kernel = str_replace("\\", "/", IPS_GetKernelDir());

/* ---------------- Start ID = Rootverzeichnis von upnp Sever --------------- */
		$container[0]['id'] = '0';
		$n = 0;
		$i = 0;
		$SI = 0;
        // Server spezifische Filter = Stammverzeichnis
        
		if($ServerName == "Plex"){
            $OID = $this->FindPlexID();
 
            switch ($Mediatype) {
                case 'Musik':
                    $container[0]['id'] = $OID['music']; 
                    break;
                case 'Audio':
                    $container[0]['id'] = $OID['audio']; 
                    break;
                case 'Foto':
                    $container[0]['id'] = $OID['photo']; 
                    break;
                case 'Video':
                    $container[0]['id'] = $OID['video']; 
                    break;
                default:
                    $container[0]['id'] = '0';
                    break;
            } 
     
         } 
         
		if($ServerName == "AVM"){
			$AuswahlA = "Ordner";
            $AuswahlB = "Musik";
            switch ($Mediatype) {
                case 'Musik':
                    $container[0]['id'] = '0';
                    break;
                case 'Audio':
                    $container[0]['id'] = '0';
                    break;
                case 'Foto':
                    $container[0]['id'] = '0';
                    break;
                case 'Video':
                    $container[0]['id'] = '0';
                    break;
                default:
                    $container[0]['id'] = '0';
                    break;
            } 
		} 
		for($n = 0; $n <= $i; ++$n){	
            $ObjectID = $container[$n]['id'];
            $Meldung = "Starte ContentDirectory_Browse mit ObjectID: ".$ObjectID;
            $this->SendDebug('UPNP: ', $Meldung, 0);
            try {
                //Function ContentDirectory_Browse aufrufen-------------------------------------
                $BrowseResult = $this->ContentDirectory_Browse ($ServerIP, $ServerPort, $Kernel, $ServerContentDirectory, $ObjectID, $BrowseFlag, $Filter, $StartingIndex, $RequestedCount, $SortCriteria);
                $Result_xml = $BrowseResult['Result'] ;
                $this->SendDebug('UPNP_Object-ID:'.$ObjectID, $Result_xml, 0);
                $NumberReturned = intval($BrowseResult['NumberReturned']);
                $TotalMatches = intval($BrowseResult['TotalMatches']);
        
                if ($NumberReturned == $TotalMatches){
                    $liste = $this->BrowseList($Result_xml);
        
                    $this->SendDebug('UPNP_Listen EInträge: '.$i.' - '.$n.' - ', $TotalMatches, 0);
                    foreach ($liste as $value) {
                        $this->SendDebug('UPNP Inhalt: ', $value, 0);
                                            // nur die storagefolder Container auslesen
                        if($value['class'] === 'object.container' or $value['class'] == 'object.container.storageFolder' or $value['class'] == 'object.container.album.musicAlbum' or $value['class'] == 'object.container.album.photoAlbum'){
                            /*
                            if(($value['title'] == $AuswahlB) or ($value['title'] == "My".$Mediatype) or ($value['title'] == $AuswahlA)){
                                $i = 0;
                                $n = 0;
                                unset($container);
                                $this->SendDebug('UPNP_Musik_Folder_ID:'.$value['title'],  $value['id'], 0);
                            }	
                            */
                                $i = $i + 1;
                                $container[$i]['class'] = $value['typ'];
                                $container[$i]['id'] = $value['id'];
                                $container[$i]['title'] = $value['title'];	
                                $container[$i]['album'] = $value['album'];
                                $container[$i]['no'] = substr($value['title'], 0, 4);
                                
                                
                        }
                        else{
                            $this->SendDebug('UPNP:', "Liste fehlt", 0);
                        }

                    }
                }
                //wenn nur Teilrückgabe, dann mehrfach auslesen
                elseif ($NumberReturned == 0){
                    //IPSLog("Wert ist Null", $NumberReturned );
                }
                else if ($NumberReturned < $TotalMatches){
                    //$SI = 0;	
                    $StartingIndex = 0;
                    if ($NumberReturned > 0){
                        for($SI = 0; $NumberReturned*$SI < $TotalMatches; ++$SI){
                            $StartingIndex = $NumberReturned*$SI;
                            //IPSLog('StartIndex', $StartingIndex );

                            $BrowseResult = $this->ContentDirectory_Browse ($ServerIP, $ServerPort, $Kernel, $ServerContentDirectory, $ObjectID, $BrowseFlag, $Filter, $StartingIndex, $RequestedCount, $SortCriteria);
                            $Result_xml = $BrowseResult['Result'] ;
                            $liste = $this->BrowseList($Result_xml);

                            foreach ($liste as $value) {
                                if($value['typ'] == 'container'){
                                    $i = $i + 1;
                                    $container[$i]['class'] = $value['typ'];
                                    $container[$i]['id'] = $value['id'];
                                    $container[$i]['title'] = $value['title'];	
                                    $container[$i]['album'] = $value['album'];
                                    $container[$i]['no'] = substr($value['album'], 0, 4);
                                     
                                }
                            }
                        }
                    }
                }
            } catch (Exception $e) {
                $this->Meldung('Exception abgefangen: '.  $e->getMessage(). "\n");
                return false;
            }
		}	
        //Erster Array Wert enthält die ID des Main Objectes Dieser muss bereinigt werden.
        unset($container[0]);
        $container = array_values($container); //all keys will be reindexed from 0
		//Serialize the array.
		$serialized = serialize($container);
        //Save the serialized array to a text file.
        switch ($Mediatype) {
            case 'Musik':
                file_put_contents($Kernel."media/Multimedia/Playlist/Musik/".$ServerName."_Musik_Container.con", $serialized); 
                break;
            case 'Audio':
                file_put_contents($Kernel."media/Multimedia/Playlist/Audio/".$ServerName."_Audio_Container.con", $serialized);  
                break;
            case 'Foto':
                file_put_contents($Kernel."media/Multimedia/Playlist/Foto/".$ServerName."_Foto_Container.con", $serialized); 
                break;
            case 'Video':
                file_put_contents($Kernel."media/Multimedia/Playlist/Video/".$ServerName."_Video_Container.con", $serialized); 
                break;
            default:
                 
                break;
        } 
        $this->Meldung( 'Verzeichnis Liste wurde erstellt!');
        return $container;

	}
	//*****************************************************************************
	/* Function: syncDB($container)
	...............................................................................
	Alle Container/Folder eines Servers ab Stammverzeichnis $Mediatype oder ab Filter auslesen 
	...............................................................................
	Parameters:  
             
  	--------------------------------------------------------------------------------
	Returns:  
 ------------------------------------------------------
	Status: 
	//////////////////////////////////////////////////////////////////////////////*/
    public function syncDB($container, $mediatype){
        //Medien Datenbank füllen.
        $mediaDB = simplexml_load_file($this->Kernel()."media/Multimedia/Playlist/".$mediatype."/DB.xml");
        $this->Meldung( 'synchronisiere DB');
        $this->SendDebug('syncDB-Container Inhalt: ', $container, 0); 
        foreach ($container as $value) {
            $playlistname = $value['no'];
            $No = intval($playlistname);
             
            $mediaDB->media[$No]->playlistname = $playlistname;
            $mediaDB->media[$No]->id = $value['id'];
            $mediaDB->media[$No]->album = $value['title'];
            $mediaDB->media[$No]->mediatype = $mediatype;
            
            //Erstes media file -----------------------------------------------------------------
            $ServerContentDirectory = GetValue($this->GetIDForIdent("upnp_ServerContentDirectory"));
            $ServerIP= GetValue($this->GetIDForIdent("upnp_ServerIP"));
            $ServerPort = GetValue($this->GetIDForIdent("upnp_ServerPort"));
            $BrowseFlag = "BrowseDirectChildren"; //"BrowseMetadata"; //"BrowseDirectChildren";
            $Filter = "*"; //GetValue();
            $StartingIndex = 0; //GetValue();
            $RequestedCount = "1"; //GetValue();
            $SortCriteria = ""; //GetValue();

            $Kernel = str_replace("\\", "/", IPS_GetKernelDir());
            $ObjectID = $value['id'];
            $this->SendDebug('syncDB-Object Id browsen: ', $ObjectID, 0); 
            try {
                //Function ContentDirectory_Browse aufrufen-------------------------------------
                $BrowseResult = $this->ContentDirectory_Browse ($ServerIP, $ServerPort, $Kernel, $ServerContentDirectory, $ObjectID, $BrowseFlag, $Filter, $StartingIndex, $RequestedCount, $SortCriteria);
                $Result_xml = $BrowseResult['Result'] ;
                $liste = $this->BrowseList($Result_xml);
                if($liste){
                    $cover = $liste[0]['albumArtURI'];
                    $mediaDB->media[$No]->icon = $cover;
                    $total = $BrowseResult['TotalMatches'];
                    $mediaDB->media[$No]->totaltrack = $total;
                    $mediaDB->asXML($this->Kernel()."media/Multimedia/Playlist/".$mediatype."/DB.xml");

                }
            } catch (Exception $e) {
                $this->Meldung('Exception abgefangen: '.  $e->getMessage(). "\n");
                return false;
            } 
        }
        //XML in Array umwandeln
        //$xml = simplexml_load_string($mediaDB, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($mediaDB);
         
        // in file schreiben
        $handle = fopen($this->Kernel()."media/Multimedia/Playlist/".$mediatype."/DBasJson.txt", "w");
        fwrite($handle, $json);
        fclose($handle);
        $this->Meldung( 'Datenbank wurde aktualisiert!');
        return true;
    }



	//*****************************************************************************
	/* Function: BrowseList($Result)
	...............................................................................
	Alle Container/Folder eines Servers ab Stammverzeichnis $Mediatype oder ab Filter auslesen 
	...............................................................................
	Parameters:  
            $Mediatype - 'Musik' // 'Audiobook' // 'Foto' // 'Video'
  	--------------------------------------------------------------------------------
	Returns:  
            * _array: [$liste]_
		*	- //01: container/item
			- //02: id
			- //03: refID
			- //04: parentid
			- //05: restricted
			- //06: artist
			- //07: album
			- //08: title
			- //09: resource
			- //10: duration
			- //11: size
			- //12: bitrate
			- //13: albumArtURI
			- //14: genre
			- //15: date
			- //16: originalTrackNumber
			- //17: class
			- //18: extension
	--------------------------------------------------------------------------------
	Status: 
	//////////////////////////////////////////////////////////////////////////////*/
	public function BrowseList($Result_xml){
		$xml = simplexml_load_string($Result_xml); //
		$skip = false;
		$liste = array();
		
		for($i=0,$size=count($xml);$i<$size;$i++)
		
		/*///////////////////////////////////////////////////////////////////////////
		///////////////////////Ereignisbaum container oder item//////////////////////
		///////////////////////////////////////////////////////////////////////////*/
		{
			if(isset($xml->container[$i])) //Container vorhanden also Verzeichnis
		      {
				$node = $xml->container[$i];
				$attribut = $xml->container[$i]->attributes();
				$liste[$i]['typ'] = "container";
				//print_r ($liste[$i]['typ']);
			  }
			else if(isset($xml->item[$i])) //Item vorhanden also item (Musik, Bild, Video)
				{
					$node = $xml->item[$i];
					$attribut = $xml->item[$i]->attributes();
					$liste[$i]['typ'] = "item";
			
					//MetaData für jeden Titel zusammenstellen--------------------------------
					$metadata_header 			= '<DIDL-Lite xmlns="urn:schemas-upnp-org:metadata-1-0/DIDL-Lite/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:upnp="urn:schemas-upnp-org:metadata-1-0/upnp/" xmlns:dlna="urn:schemas-dlna-org:metadata-1-0/">';
					$raw_metadata_string 	= $xml->item[$i]->asxml();
					//$metadata_string 			= str_replace(array("<", ">"), array("&lt;", "&gt;"), $raw_metadata_string);
					//$metadata_string    = htmlspecialchars ($raw_metadata_string);
                                        //$metadata_string    =  htmlentities ($raw_metadata_string, ENT_NOQUOTES | ENT_SUBSTITUTE, "UTF-8");
                                        
                                       $metadata_string =   $raw_metadata_string;
                                        $metadata_close  = '</DIDL-Lite>';
                            
                                        $metadata_string1 = str_replace('&amp;', '&', $metadata_string);
                            
					$metadata	= $metadata_header.$metadata_string1.$metadata_close;
                                        
                            
					$liste[$i]['metadata']	= $metadata;
                            
		
				}
			else
				{
					$skip = true;
					//IPSLog("nicht lesbar !!!" , $i); //Fehler aufgetreten
					//IPSLog('Container ', $xml->container[$i]);
					//IPSLog('Item ', $xml->item[$i]);
					//IPSLog('XML ', $xml);
		   			//return;
				}
				//////////////////////////////Ende  Ereignisbaum//////////////////////////////
			if ($skip == false){
				/*//////////////////////////////////////////////////////////////////////////////
				//////////////////////////////////////////////////////////////////////////////*/
				if(isset($attribut['id']) && !empty($attribut['id'])) {
						$id = $attribut['id'];
						$liste[$i]['id']=(string)$id;
					}else{
						$liste[$i]['id']="leer";
						}
				if(isset($attribut['refID']) && !empty($attribut['refID'])) {
						$refID = $attribut['refID'];
						$liste[$i]['refid']=(string)$refID;
					}else{
						$liste[$i]['refid']="leer";
						}
				if(isset($attribut['parentID']) && !empty($attribut['parentID'])) {
				      $parentID = $attribut['parentID'];
						$liste[$i]['parentid']=(string)$parentID;
					}else{
						$liste[$i]['parentid']="leer";
						}
				if(isset($attribut['restricted']) && !empty($attribut['restricted'])) {
						$restricted = $attribut['restricted'];
						$liste[$i]['restricted']=(string)$restricted;
					}else{
						$liste[$i]['restricted']="leer";
						}
				if($node->xpath("dc:creator")) {
						$interpret = $node->xpath("dc:creator");
						$liste[$i]['artist']=utf8_decode((string)$interpret[0]);
					}else{
						$liste[$i]['artist']="leer";
						}
				if($node->xpath("upnp:album")) {
						$album = $node->xpath("upnp:album");
                        //$liste[$i]['album']=utf8_decode((string)$album[0]);
                        $liste[$i]['album']=(string)$album[0];
					}else{
						$liste[$i]['album']="leer";
						}
				if($node->xpath("dc:title")) {
						$titel = $node->xpath("dc:title");
                                                $liste[$i]['title']= (string)$titel[0];
                        //$liste[$i]['title']=utf8_decode((string)$titel[0]);
                        
					}else{
						$liste[$i]['title']="leer";
						}
				if($node->xpath("upnp:albumArtURI")) {
						$albumart = $node->xpath("upnp:albumArtURI");
						$liste[$i]['albumArtURI']=(string)$albumart[0];
					}else{
						$liste[$i]['albumArtURI'] ="leer";
						}
				if($node->xpath("upnp:genre")) {
						$genre = $node->xpath("upnp:genre");
						$liste[$i]['genre']=utf8_decode((string)$genre[0]);
					}else{
						$liste[$i]['genre']="leer";
						}
				if($node->xpath("dc:date")) {
						$date = $node->xpath("dc:date");
						$liste[$i]['date']=(string)$date[0];
					}else{
						$liste[$i]['date']="leer";
						}
				if($node->xpath("upnp:originalTrackNumber")) {
						$originalTrackNumber = $node->xpath("upnp:originalTrackNumber");
						$liste[$i]['originalTrackNumber']=(string) $originalTrackNumber[0];
					}else{
						$liste[$i]['originalTrackNumber']="leer";
						}
				if($node->xpath("upnp:class")) {
						$class = $node->xpath("upnp:class");
						$liste[$i]['class']=(string)$class[0];
					}else{
						$liste[$i]['class']="leer";
						}
				//der einzige Node ohne Namespace !
				if(isset($node->res)){
					$res = $node->res;
					$liste[$i]['resource'] = (string)$res[0];
					$resattribut = $res[0]->attributes();;
					}
				else {
					$liste[$i]['resource'] = "leer";
				}
				//Resource-Attribute auslesen---------------------------------------------------
				if(isset($resattribut['duration'])) {
					$liste[$i]['duration']=(string)$resattribut['duration'];
				}else{
					$liste[$i]['duration']="leer";
					}
				if(isset($resattribut['size'])) {
					$liste[$i]['size']=(string)$resattribut['size'];
				}else{
					$liste[$i]['size']="leer";
					}
				if(isset($resattribut['bitrate'])) {
					$liste[$i]['bitrate']=(string)$resattribut['bitrate'];
				}else{
					$liste[$i]['bitrate']="leer";
					}
			}
			$skip=false;
        }	
            
            return ($liste); //Rückgabe
 
	}





	//*****************************************************************************
	/* Function: createPlaylist($id, $PlaylistNo)
        ...............................................................................
        Erzeugt eine Playliste aus dem Container mit der ID und 
        bennent sie nach Servername + PlaylistNo
        "AVM0001.xml"
        ...............................................................................
        Parameters:  
            $id         - Container ID.
            $PlaylistNo - Playlist Nummer z.Bsp "0001".
 	--------------------------------------------------------------------------------
	Returns:    
            * schreibt FILE.
                * $Kernel."media/Multimedia/Playlist/Musik/".$PlaylistName.".xml"].  
        --------------------------------------------------------------------------------
        Status: 
        //////////////////////////////////////////////////////////////////////////////*/
        Public function createPlaylist(string $id, string $mediatype, string $PlaylistNo){
                //IPSLog("Starte Funktion CREATEPLAYLIST mit Parameter ", $id.' - '.$PlaylistNo);
                $PlaylistArray = array();


                //es wird der angewählte Server durchsucht
                $ServerName = getvalue($this->GetIDForIdent("upnp_ServerName"));
                //IPSLog('ServerName', $ServerName);
                //------------------------------------------------
                // alle media files in Ordner mit ID  = $id suchen
                //------------------------------------------------
                $result = $this->browseContainerServer($id);

                //Browse als XML-Datei zwischenspeichern

                //Numerische Keys durch Track[Nr.] ersetzen-------------------------------------
                $prefix = "Track";
                $BrowselistArray = $this->rekey_array( $result , $prefix );
                
                //print_r ($BrowselistArray);

                $xml = new SimpleXMLElement('<Playlist/>');
                $xml = Array2XML::createXML('Playlist' , $BrowselistArray);
                
                $Kernel = $this->Kernel();
                
                $PlaylistName = $ServerName.$PlaylistNo;                
                $Playlist = $xml->saveXML();
                
                //XML-Datei in \\IPS-RASPI\varlibsymcon\media\Multimedia\Playlist\$mediatype
               
                $handle = fopen($Kernel."media/Multimedia/Playlist/".$mediatype."/".$PlaylistName.".xml", "w");
                fwrite($handle, $Playlist);
                fclose($handle);
               
                $this->SendDebug('UPNP Playlist erstellt: ', $PlaylistName, 0);
        }



	//*****************************************************************************
	/* Function: createAllPlaylist($mediatype)
        ...............................................................................
        Vorbedingung:
        getContainerServer(string $Mediatype)
        muss vorher ausgeführt worden sein,
        
        Erzeugt alle Playlisten vom Typ Mediatype  
        bennent sie nach Servername + PlaylistNo + .xml
        ...............................................................................
        Parameters:  
            $mediatype - "Musik" // "Audio" // "Video" // "Foto"
 	--------------------------------------------------------------------------------
	Returns: 
            * schreibt FILES (Playlisten)
                * _$Kernel."media/Multimedia/Playlist/Musik/".$ServerName."PlaylistNo".xml_.
        --------------------------------------------------------------------------------
        Status: 
        //////////////////////////////////////////////////////////////////////////////*/
        Public function createAllPlaylist(string $mediatype){
            $this->Meldung( 'erzeuge Playlisten!');
                $ServerName = getvalue($this->GetIDForIdent("upnp_ServerName"));

                /*
                if ($mediatype == 'Fotos'){
                        $DB_Fotos_Compressed = getvalue(45521 );
                        $DB_Fotos = unserialize($DB_Fotos_Compressed);
                        foreach($DB_Fotos as $Foto){
                                $id = $Foto['ID_Plex'];
                                $PlaylistNo = $Foto['No'];
                                $this->createPlaylist($id, $mediatype, $PlaylistNo);
                        }
                }
                */

 
           
                    //Retrieve the serialized string.
                    $Kernel = $this->Kernel();
                    $fileContents = file_get_contents($Kernel."media/Multimedia/Playlist/".$mediatype."/".$ServerName."_".$mediatype."_Container.con");
                    //Unserialize the string back into an array.
                    $MediaContainer = unserialize($fileContents);
                    //load DB
                    $dbXML = simplexml_load_file($this->Kernel()."media/Multimedia/Playlist/".$mediatype."/DB.xml");

                    foreach ($MediaContainer as $key => $value) {
                            $id = $value['id'];		
                            $PlaylistNo = substr($value['title'],0,4);
                            $this->createPlaylist($id, $mediatype, $PlaylistNo);
                            $this->Meldung('erzeuge Playlist: '.$ServerName.$PlaylistNo.'.mp3');
                    }
                    $this->Meldung( 'Fertig - alle Playlisten erzeugt!');
       
        }










    //*****************************************************************************
	/*  - Sammlung / Tools
    /*//////////////////////////////////////////////////////////////////////////////
    //
	//*****************************************************************************
	/* Function: Meldung($Meldung)
    ...............................................................................
    	Anzeige im Meldungsfenster (Textbox) ObjectID 11701
    ...............................................................................
        Parameters: 
            $Meldung - Ausgabetext in Debug Fenster
 	--------------------------------------------------------------------------------
		Returns: 
    -----------------------------------------------------------------------------
    	Status: 
    *****************************************************************************/
    Protected function Meldung(string $Meldung){
        $this->SendDebug('UPNP: ', $Meldung, 0);
        setvalue($this->GetIDForIdent("upnp_Message"), $Meldung);
    }  

                            
        
                            
	//*****************************************************************************
	/* Function: rekey_array($input, $prefix)
        ...............................................................................
        umbenennen der im Array enthaltenen numerischen Keys mit einem Präfix
        ...............................................................................
        Parameters: 
            *  $input - ""
            *  $prefix - "" 
 	--------------------------------------------------------------------------------
	Returns: 
        -----------------------------------------------------------------------------
        Status: 
        *****************************************************************************/
        Public function rekey_array($input, $prefix)
                {
                $out = array();
                foreach($input as $i => $v)
                        {
                        if(is_numeric($i))
                                {
                                $out[$prefix . $i] = $v;
                                continue;
                                }
                        $out[$i] = $v;
                        }
                        return $out;
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
                $this->Meldung($IP.": erreichbar\r\n\r\n");
                return ("true");
            }
        }


	//*****************************************************************************
	/* Function: search_exclude_value($array, $key, $value)
        ...............................................................................
        function search_exclude_value($array, $key, $value)
        ein mehrdimensionales Array wird durchsucht nach allen Subarrays welche nicht
        den Wert ($value) enthalten
        $array -> das mehrdimensionale Array
        $key   -> in welchen Key
        $value -> auszuschliessender Wert
        Rückgabe eines Array, welches nur die Subarrays enthält, welche nicht den Wert
        (z.B. '', also leer)  enthalten
        ...............................................................................
        Parameters: 
            *  $array - das mehrdimensionale Array
            *  $key - in welchen Key
            *  $value - auszuschliessender Wert
 	--------------------------------------------------------------------------------
	Returns: 
         * bereinigtes array
        -----------------------------------------------------------------------------
        Status: 
        *****************************************************************************/
        Protected function search_exclude_value($array, $key, $value){
            $results = array();
            if (is_array($array)){
                if (isset($array[$key]) && $array[$key] !== $value){
                    $results[] = $array;
                    foreach ($array as $subarray){
                        $results = array_merge($results, search_exclude_value($subarray, $key, $value));
                    }
                }
            }
            else{
                // Array empty - search clients and Server
                $this->SendDebug("Array empty", "Starte Suchlauf für Clients und/oder Server", 0);
            }
            return $results;
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
        Status  checked 11.6.2018
        //////////////////////////////////////////////////////////////////////////////*/
        Protected function search_key($which_key, $which_value, $array){
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
        

     /* ----------------------------------------------------------------------------
     Function: RegisterProfile
    ...............................................................................
    Erstellt ein neues Profil und ordnet es einer Variablen zu.
    ...............................................................................
    Parameters: 
        $Name, $Icon, $Prefix, $Suffix, $MinValue, $MaxValue, $StepSize, $Digits, $Vartype, $VarIdent, $Assoc
     * $Vartype: 0 boolean, 1 int, 2 float, 3 string,
     * $Assoc: array mit statustexte
     *         $assoc[0] = "aus";
     *         $assoc[1] = "ein";
     * RegisterProfile("Rollo.Mode", "", "", "", "", "", "", "", 0, "", $Assoc)
    ..............................................................................
    Returns:   
        none
    ------------------------------------------------------------------------------- */
    protected function createProfile(string $Name, int $Vartype, $Assoc, $Icon,  $Prefix,  $Suffix,   $MinValue,   $MaxValue,  $StepSize,  $Digits){
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
                        if(is_null($data['icon'])){$data['icon'] = "";}; 
                        if(is_null($data['color'])){$data['color'] = "";}; 
                        IPS_SetVariableProfileAssociation($Name, $data['value'], $data['text'], $data['icon'], $data['color']);  
                    }
                }
            } 
            else {
                $profile = IPS_GetVariableProfile($Name);
                if ($profile['ProfileType'] != $Vartype){
                       // $this->SendDebug("Alarm.Reset:", "Variable profile type does not match for profile " . $Name, 0);
                }
            }
    }	
    
    
    
    /* ----------------------------------------------------------------------------
     Function: Registerrofiles()
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
                 
        $Assoc[0]['value'] = 0;
        $Assoc[1]['value'] = 1;
        $Assoc[2]['value'] = 2;
        $Assoc[3]['value'] = 3;
        $Assoc[0]['text'] = "Up";
        $Assoc[1]['text'] = "Select";
        $Assoc[2]['text'] = "Left";
        $Assoc[3]['text'] = "Right";
        $Assoc[0]['icon'] = NULL;
        $Assoc[1]['icon'] = NULL;
        $Assoc[2]['icon'] = NULL;
        $Assoc[3]['icon'] = NULL;
        $Assoc[0]['color'] = NULL;
        $Assoc[1]['color'] = NULL;
        $Assoc[2]['color'] = NULL;
        $Assoc[3]['color'] = NULL;
        $Name = "UPNP_Browse";
        $Vartype = 1;
        $Icon = NULL;
        $Prefix = NULL;
        $Suffix = NULL;
        $MinValue = 0;
        $MaxValue = 3;
        $StepSize = 1;
        $Digits = 0;
        if (!IPS_VariableProfileExists($Name)){
            $this->createProfile($Name, $Vartype,  $Assoc, $Icon, $Prefix, $Suffix, $MinValue, $MaxValue, $StepSize, $Digits);  
        }
    }
    
   
}


