<?php
/***************************************************************************
 * Title: _TITEL_
 *
 * Author: _AUTOR_
 * 
 * GITHUB: <https://github.com/SymPiTo/MySymApps/tree/master/_TITEL_>
 * 
 * Version: _VERSION_
 *************************************************************************** */
require_once(__DIR__ . "/FB_Interface.php");
//require_once __DIR__ . '/../libs/_HELPERCLASS_';  // diverse Klassen
require_once(__DIR__ . "/../libs/NetworkTraits2.php");
class MyFB extends IPSModule {

    use FB_soap;
    use MyDebugHelper2;
/* 
___________________________________________________________________________ 
    Section: Internal Modul Funtions
    Die folgenden Funktionen sind Standard Funktionen zur Modul Erstellung.
___________________________________________________________________________ 
    */
    /* 
    ------------------------------------------------------------ 
        Function: Create  
        Create() Wird ausgeführt, beim anlegen der Instanz.
    -------------------------------------------------------------
    */
    public function Create() {
    //Never delete this line!
        parent::Create();

        //Register Properties from form.json
        $this->RegisterPropertyBoolean("active", false);

        //$this->ReadPropertyFloat("NAME", 0.0);

        $this->RegisterPropertyInteger("UpdateInterval", 0);

        $this->RegisterPropertyString("FBX_IP", "ip");
        $this->RegisterPropertyString("FBX_USERNAME", "user@user.com");
        $this->RegisterPropertyString("FBX_PASSWORD", "");


        // Register Profiles
        $this->RegisterProfiles();
 

        //Register Variables
        $variablenID = $this->RegisterVariableBoolean ("DSLState", "DSL Status", "", 1);
        IPS_SetInfo ($variablenID, "WSS");
        $variablenID = $this->RegisterVariableBoolean ("INetState", "Internet Status", "", 1);
        IPS_SetInfo ($variablenID, "WSS");
        $variablenID = $this->RegisterVariableBoolean ("Reboot", "Reboot FB", "", 1);
        $variablenID = $this->RegisterVariableBoolean ("ReConnect", "Reconnect DSL", "", 1);
        //IPS_SetHidden($variablenID, true); //Objekt verstecken

        //$variablenID = $this->RegisterVariableFloat ($Ident, $Name, $Profil, $Position);
        //IPS_SetInfo ($variablenID, "WSS");
        //IPS_SetHidden($variablenID, true); //Objekt verstecken

        $variablenID = $this->RegisterVariableInteger ("DSLUpRate", "DSL UpStream Rate", "", 2);
        IPS_SetInfo ($variablenID, "WSS");
        $variablenID = $this->RegisterVariableInteger ("DSLDownRate", "DSL DownStream Rate", "", 3);
        IPS_SetInfo ($variablenID, "WSS");
        //IPS_SetHidden($variablenID, true); //Objekt verstecken

        $variablenID = $this->RegisterVariableString("Hosts", "aktive hosts", "", 4);
        IPS_SetInfo ($variablenID, "WSS");
        $variablenID = $this->RegisterVariableString("callInPhone", "income call No", "", 5);
        IPS_SetInfo ($variablenID, "WSS");
        $variablenID = $this->RegisterVariableString("callInName", "income call Person", "", 6);
        IPS_SetInfo ($variablenID, "WSS");
        $variablenID = $this->RegisterVariableString("FbDynDns", "FB_DynDNS", "", 7);
        IPS_SetInfo ($variablenID, "WSS");
        $variablenID = $this->RegisterVariableString("ExtIP", "External IP", "", 8);
        IPS_SetInfo ($variablenID, "WSS");


        //IPS_SetHidden($variablenID, true); //Objekt verstecken

        //Register Timer
        $this->RegisterTimer('update', $this->ReadPropertyInteger("UpdateInterval"), 'FB_update($_IPS[\'TARGET\']);');
 

        
    

        //Webfront Actions setzen
        $this->EnableAction("Reboot");
        $this->EnableAction("ReConnect");
    } //Function: Create End
    /* 
    ------------------------------------------------------------ 
        Function: ApplyChanges  
        ApplyChanges() Wird ausgeführt, beim anlegen der Instanz.
        und beim ändern der Parameter in der Form
    -------------------------------------------------------------
    */
    public function ApplyChanges(){
        //Never delete this line!
        parent::ApplyChanges();

        //Messages registrieren
        $this->RegisterMessage(0, IPS_KERNELSTARTED);
        $this->RegisterMessage($this->InstanceID, FM_CONNECT);
        $this->RegisterMessage($this->InstanceID, FM_DISCONNECT);

        if($this->ReadPropertyBoolean("active")){
            $this->updateOnce();
            //Timer einschalten
            $this->SetTimerInterval("update", $this->ReadPropertyInteger("UpdateInterval"));
        }
        else {
            //Timer ausschalten
             $this->SetTimerInterval("update", 0);
        }                   
    } //Function: ApplyChanges  End
    /* 
    ------------------------------------------------------------ 
        Function: Destroy  
            Destroy() wird beim löschen der Instanz 
            und update der Module aufgerufen
    -------------------------------------------------------------
    */
    public function Destroy() {
        //Never delete this line!
        parent::Destroy();
    } //Function: Destroy End
    /* 
    ------------------------------------------------------------ 
        Function: RequestAction  
            RequestAction() wird von schaltbaren Variablen 
            aufgerufen.
    -------------------------------------------------------------
    */ 
    public function RequestAction($Ident, $Value) {     
        switch($Ident) {
            case "Reboot":
                if ($Value == true){ 
                    $this->Reboot();
                    IPS_sleep(1000);
                    $this->setvalue("Reboot",false);
                }
                else {

                }
                break;
            case "ReConnect":
                if ($Value == true){ 
                    $this->ForceTermination();
                    IPS_sleep(1000);
                    $this->setvalue("ReConnect",false);
                }
                else {

                }
            default:
                throw new Exception("Invalid Ident");
            }
    } //Function: RequestAction End
    /* 














    /* 




























 

/* 
___________________________________________________________________________________________________________________
    Section: Public Funtions
    Die folgenden Funktionen stehen automatisch zur Verfügung, wenn das Modul über die "Module Control" eingefügt wurden.
    Die Funktionen werden, mit dem selbst eingerichteten Prefix, in PHP und JSON-RPC wie folgt zur Verfügung gestellt:
    
    FSSC_XYFunktion($Instance_id, ... );
________________________________________________________________________________________________________________________ 
*/
    //-----------------------------------------------------------------------------
    /* Function:  
    ...............................................................................
    Beschreibung: listet alle aktiven hosts
    ...............................................................................
    Parameters: 
        none
    ...............................................................................
    Returns:    
        list of hosts as array
    ------------------------------------------------------------------------------  */
    public function update(){
        $portOpen = $this->checkPort("192.168.178.1", "49000");
        if($portOpen == 0){

            $this->SetValue("DSLUpRate", $this->DSL_GetInfo()['NewUpstreamCurrRate']);
            $this->SetValue("DSLDownRate", $this->DSL_GetInfo()['NewDownstreamCurrRate']);
            $DSL = ($this->DSL_GetInfo()['NewStatus'] == "Up" ? true : false); 
            $this->SetValue("DSLState", $DSL);
            $this->SetValue("ExtIP", $this->GetExternalIPAddress);
            $Istate = $this->GetInfo_connection();
            $c = ($Istate["NewConnectionStatus"]  == "Connected") ? true : false;
            $this->SetValue("INetState", $c);

            $this->get_hosts();
            IPS_Sleep(1000);
        }
        else{
            $this->SendDebug('SocketOpen:', "Port ist blockiert:".$portOpen , 0);
        }
    }  
 

    //-----------------------------------------------------------------------------
    /* Function: get_hosts
    ...............................................................................
    Beschreibung: listet alle aktiven hosts
    ...............................................................................
    Parameters: 
        none
    ...............................................................................
    Returns:    
        list of hosts as array
    ------------------------------------------------------------------------------  */
    public function get_hosts(){
        $hosts = array();
        $No_hosts = $this->GetHostNumberOfEntries();
            for ($i = 0; $i < $No_hosts; $i++) {
                $hosts[$i] = $this->GetGenericHostEntry($i);
                IPS_Sleep(100);
            }

            $this->setvalue("Hosts", json_encode($hosts));
           // $this->SendDebug("get_hosts:",  $hosts, 0);
        return $hosts;
    }   




    public function QueryDasOertlicheDe($phoneNumber)
    {
        $record = false;
        $url = "http://www.dasoertliche.de/Controller?form_name=search_inv&ph=".$phoneNumber;
        # Create a DOM parser object
        $dom = new DOMDocument();
        # Parse the HTML from klicktel
        # The @ before the method call suppresses any warnings that
        # loadHTMLFile might throw because of invalid HTML or URL.
        @$dom->loadHTMLFile($url);
         
        if ($dom->documentURI == null)
        {
            IPS_LogMessage(IPS_GetObject($this->InstanceID)['ObjectName'], "Timeout bei Abruf der Webseite ".$url);
            return false;
        }
        $finder = new DomXPath($dom);
        $classname="hit clearfix";
        $nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), '$classname')]");
        
       
        if ($nodes->length == 0) return false;
        $cNode = $nodes->item(0); //div left
        if ($cNode->nodeName != 'div') return false;
        if (!$cNode->hasChildNodes()) return false;
        $ahref = $cNode->childNodes->item(1); // a href
        if (!$ahref->hasChildNodes()) return false;
        foreach ($ahref->childNodes as $div)
        {
            if ($div->nodeName == "h2" ) break;
        }
        $record = array(
                        'Name' => trim($div->nodeValue)
                        );
        return $record;
    }   

/* 
_______________________________________________________________________
    Section: Private Funtions
    Die folgenden Funktionen sind nur zur internen Verwendung verfügbar
    Hilfsfunktionen
______________________________________________________________________
*/ 

    //-----------------------------------------------------------------------------
    /* Function:    updateOnce)=
    Beschreibung:   wird einmalig ausgeführt wenn Modul auf aktiv geschaltet wird.
    ...............................................................................
    Parameters:     none
    Returns:        none
    ------------------------------------------------------------------------------  */
    public function updateOnce(){
       $result = $this->Get_MyFritz_DynDNS();
       $this->setvalue("FbDynDns", $result["NewDynDNSName"]);
    }  


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
    }   //Function: createProfile End
    
    
    
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
       /*   Profile "UPNP_Browse";   
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
        */
    } //Function: RegisterProfiles End

    /** Wird ausgeführt wenn der Kernel hochgefahren wurde. */
    protected function KernelReady(){
        $this->ApplyChanges();
    }
    /* ----------------------------------------------------------------------------
     Function: GetIPSVersion()
    ...............................................................................
        gibt die eingestezte IPS Version zurück
    ...............................................................................
    Parameters: 
        none
    ..............................................................................
    Returns:   
        $ipsversion
    ------------------------------------------------------------------------------- */
    protected function GetIPSVersion(){
        $ipsversion = floatval(IPS_GetKernelVersion());
        if ($ipsversion < 4.1) {    // 4.0
            $ipsversion = 0;
        } elseif ($ipsversion >= 4.1 && $ipsversion < 4.2){ // 4.1
            $ipsversion = 1;
        } elseif ($ipsversion >= 4.2 && $ipsversion < 4.3){ // 4.2
            $ipsversion = 2;
        } elseif ($ipsversion >= 4.3 && $ipsversion < 4.4){ // 4.3
            $ipsversion = 3;
        } elseif ($ipsversion >= 4.4 && $ipsversion < 5){ // 4.4
            $ipsversion = 4;
        } else {  // 5
            $ipsversion = 5;
        }
        return $ipsversion;
    } //Function: GetIPSVersion End
    /* --------------------------------------------------------------------------- 
    Function: RegisterEvent
    ...............................................................................
    legt einen Event an wenn nicht schon vorhanden
      Beispiel:
      ("Wochenplan", "SwitchTimeEvent".$this->InstanceID, 2, $this->InstanceID, 20);  
      ...............................................................................
    Parameters: 
      $Name        -   Name des Events
      $Ident       -   Ident Name des Events
      $Typ         -   Typ des Events (0=ausgelöstes 1=cyclic 2=Wochenplan)
      $Parent      -   ID des Parents
      $Position    -   Position der Instanz
    ...............................................................................
    Returns:    
        none
    -------------------------------------------------------------------------------*/
    private function RegisterEvent($Name, $Ident, $Typ, $Parent, $Position){
        $eid = @$this->GetIDForIdent($Ident);
        if($eid === false) {
                $eid = 0;
        } elseif(IPS_GetEvent($eid)["EventType"] <> $Typ) {
                IPS_DeleteEvent($eid);
                $eid = 0;
        }
        //we need to create one
        if ($eid == 0) {
                $EventID = IPS_CreateEvent($Typ);
                IPS_SetParent($EventID, $Parent);
                IPS_SetIdent($EventID, $Ident);
                IPS_SetName($EventID, $Name);
                IPS_SetPosition($EventID, $Position);
                IPS_SetEventActive($EventID, false);  
        }
    } //Function: RegisterEvent End


 
    /***************************************************************************
    * Name:  CheckPort
    * 
    * Description: prüft ob ein Port offen/ansprechbar ist
    *
    * Parameters:  ip, port
    * 
    * Returns:  Error nummber 0 = OK
    *************************************************************************** */
    private function CheckPort($ip, $port){
        $connection = @fsockopen($ip, $port, $errno, $errstr, 20);
        @fclose($connection);  
        $this->SendDebug('SocketOpen', $errstr , 0);
        return $errno;
    } //Function: End


} //end Class


