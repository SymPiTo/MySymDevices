<?php

 #******************************************************************************#
 # Title : MyComfee                                                             #
 #                                                                              #
 # Author: PiTo                                                                 #
 #                                                                              #
 # GITHUB: <https://github.com/SymPiTo/MySymDevices/tree/master/MyComfee>       #
 #                                                                              #
 # Version: 1.0.2  20230409                                                     #
 #******************************************************************************#
 # _____________________________________________________________________________#
 #    Section: Beschreibung                                                     #
 #    Das Modul dient zur Steuerung des Comfee                                  #
 #    Luftenfeuchters			                                                #
 #    Steuerung über Python Command line                                        #
 # _____________________________________________________________________________#
 
 require_once(__DIR__ . "/../libs/MyHelper.php");

 class MyComfee extends IPSModule{
	#Traits aufrufen
	use ProfileHelper;

#______________________________________________________________________________________________________________________________________________
#           Section: Internal Module Functions                                                                                                 
#           Die folgenden Funktionen sind Standard Funktionen zur Modul Erstellung                                                             
#______________________________________________________________________________________________________________________________________________

	/*
	#---------------------------------------------------------------------#
	#       Function: Create()                                            #
	#       Create() wird ausgeführt, beim Anlegen der Instanz.           #
	#       Wird ausgeführt beim symcon Neustart                          #
	#---------------------------------------------------------------------#
	*/
	public function Create() {
		parent::Create();


		# Properties registrieren
		$this->RegisterPropertyBoolean('active', 'false');
		$this->RegisterPropertyBoolean('wss', 'false');
		$this->RegisterPropertyInteger('interval', 0);
		$this->RegisterPropertyString('ip', '');
		$this->RegisterPropertyString('user', '');
		$this->RegisterPropertyString('pw', '');
		
		# Attribute registrieren
		$this->RegisterAttributeInteger("actIntervalTime", 0);
	
 

		$associations = [ 
			[1, 'Wireless', '', 0x00FF00],
			[2, 'Entfeuchten', '', 0x00FF00],
			[3, 'SMD', '', 0x00FF00],
			[4, 'Trocknen', '', 0x00FF00]
        ];
        $name = "COMF.Mode";
        $vartype = 1;
        $minvalue = 1;
        $maxvalue = 4;
        $stepsize = 1;
    	$this->RegisterProfile($vartype, $name, $icon = null, $prefix = '', $suffix = '', $minvalue, $maxvalue, $stepsize, $digits = 0, $associations);

		$associations = [ 
			[40, 'Low', '', 0x00FF00],
			[60, 'Mid', '', 0x00FF00],
			[80, 'High', '', 0x00FF00]
        ];
        $name = "COMF.FanSpeed";
        $vartype = 1;
        $minvalue = 20;
        $maxvalue = 80;
        $stepsize = 20;
    	$this->RegisterProfile($vartype, $name, $icon = null, $prefix = '', $suffix = '', $minvalue, $maxvalue, $stepsize, $digits = 0, $associations);


		# Variablen registrieren
		//Diese Variable kann über fritzBox Module getriggert werden
		$this->RegisterVariableBoolean('alive', 'Host is Alive', '', 0);
		$this->RegisterVariableBoolean('connected', 'Connected', '', 0);
		$this->RegisterVariableBoolean('power', 'Power', '~Switch', 1);
		$this->RegisterVariableInteger('curhumid', 'current_humidity', '', 2);
		$this->RegisterVariableInteger('targethumid', 'target_humidity', '', 3);
		$this->RegisterVariableInteger('mode', 'mode', 'COMF.Mode', 4);
		$this->RegisterVariableInteger('fanspeed', 'Fan Speed', 'COMF.FanSpeed', 5);
		$this->RegisterVariableBoolean('ion', 'ion', '', 6);
		$this->RegisterVariableBoolean('tankfull', 'Tank_Full', '', 7);
		$this->RegisterVariableBoolean('filter', 'Filter_Replacement', '', 8);
		$this->RegisterVariableBoolean('pumpstate', 'Pump_State', '', 9);
		$this->RegisterVariableBoolean('Sleepmode', 'Sleep_Mode', '', 10);
		$this->RegisterVariableBoolean('defrost', 'Defrosting', '', 11);
		$this->RegisterVariableString('errorcode', 'Error_Code', '', 12);
		$this->RegisterVariableInteger('temp', 'Temperature', '', 13);
		$varID = $this->RegisterVariableString('id', 'Comfee_ID', "", 14);
		IPS_SetHidden($varID, true); //Objekt verstecken


		$this->RegisterTimer('UpdateTimer', 0, 'COMF_Update($_IPS[\'TARGET\']);');
		//$this->RegisterTimer('UpdateTimer', 10, '$this->Update();');

		//Webfront Actions setzen
		$this->EnableAction("power");
		$this->EnableAction("mode");
		$this->EnableAction("fanspeed");
		$this->EnableAction('targethumid');
	}
	
	#--------------------------------------------------------------------------------#
	#       Function: ApplyChanges()                                                 #
	#       Einträge vor ApplyChanges() werden sowohl beim Systemstart               #
	#       als auch beim Ändern der Parameter in der Form ausgeführt.               #
	#       ApplyChanges() wird ausgeführt, beim Anlegen der Instanz                 #
	#       und beim ändern der Parameter in der Form                                #
	#--------------------------------------------------------------------------------#
	
	public function ApplyChanges(){
		$this->RegisterMessage(0, IPS_KERNELSTARTED);
		$this->RegisterMessage(0, IPS_KERNELSHUTDOWN);
		if (IPS_GetKernelRunlevel() <> KR_READY) {
			$this->LogMessage('ApplyChanges: Kernel is not ready! Kernel Runlevel = '.IPS_GetKernelRunlevel(), KL_ERROR);
			//ApplyChanges wird über MessageSink nachgestartet.
			return;
		}
		//Never delete this line!
		parent::ApplyChanges();
		# Variable für WebsocketServer Übertragung freigeben.
		if($this->ReadPropertyBoolean('wss')){ 
			IPS_SetInfo ($this->GetIDForIdent("power"), "WSS"); 
			IPS_SetInfo ($this->GetIDForIdent("connected"), "WSS"); 
			IPS_SetInfo ($this->GetIDForIdent("curhumid"), "WSS"); 
			IPS_SetInfo ($this->GetIDForIdent("targethumid"), "WSS"); 
			IPS_SetInfo ($this->GetIDForIdent("mode"), "WSS"); 
			IPS_SetInfo ($this->GetIDForIdent("fanspeed"), "WSS"); 
			IPS_SetInfo ($this->GetIDForIdent("tankfull"), "WSS"); 
			IPS_SetInfo ($this->GetIDForIdent("filter"), "WSS"); 
			IPS_SetInfo ($this->GetIDForIdent("pumpstate"), "WSS"); 
			IPS_SetInfo ($this->GetIDForIdent("Sleepmode"), "WSS"); 
			IPS_SetInfo ($this->GetIDForIdent("defrost"), "WSS"); 
			IPS_SetInfo ($this->GetIDForIdent("errorcode"), "WSS"); 
			IPS_SetInfo ($this->GetIDForIdent("temp"), "WSS"); 
		}
		if($this->ReadPropertyBoolean('active') and $this->ReadPropertyString('ip')<>''){
			$this->SetTimerInterval("UpdateTimer", $this->ReadPropertyInteger('interval'));
			$dis = $this->Discover();
			if($dis == false){
				
			}
			else{
				$this->setvalue('id', ltrim($dis['id']));
				$this->setvalue('power', $dis['running']);
				$this->setvalue('curhumid', $dis['humid%']);
				$this->setvalue('targethumid', $dis['target%']);
				$this->setvalue('mode', $dis['mode']);
				$this->setvalue('fanspeed', $dis['fan']);
				$this->setvalue('ion', $dis['ion']);
				$this->setvalue('tankfull', $dis['tank']);
				$this->setvalue('filter', $dis['filter']);
				$this->setvalue('pumpstate', $dis['pump']);
				$this->setvalue('Sleepmode', $dis['sleep']);
				$this->setvalue('defrost', $dis['defrost']);
				$this->setvalue('errorcode', $dis['error']);
				$this->setvalue('temp', $dis['temp']);
			}
		}
		else{
			$this->SetTimerInterval("UpdateTimer", 0);
		}
	}
	
	#------------------------------------------------------------------#
	#       Function: Destroy()                                        #
	#       Destroy() IPS Standard Funktion                            #
	#                                                                  #
	#------------------------------------------------------------------#
	
	public function Destroy(){

		//Never delete this line!
		parent::Destroy();

	}	


	#------------------------------------------------------------------#
	#       Function: RequestAction()                                  #
	#       RequestAction() IPS Standard Funktion                      #
	#       wird von schaltbaren Variablen aufgerufen                  #
	#------------------------------------------------------------------#
	public function RequestAction($Ident, $Value) {     
		switch($Ident) {
			case "power":
				if ($Value == true){ 
					$this->Set_Power(true);
					$this->setvalue("power",true);
				}
				else {
					$this->Set_Power(false);
					$this->setvalue("power",false);
				}
				break;
			case "mode":
				switch ($Value){
					case 0:
						break;
					case 1:
						$this->Set_OpMode(1);
						$this->SetValue("mode",1);
						break;
					case 2:
						$this->Set_OpMode(2);
						$this->SetValue("mode",2);
						break;
					case 3:
						$this->Set_OpMode(3);
						$this->SetValue("mode",3);
						break;
					case 4:
						$this->Set_OpMode(4);
						$this->SetValue("mode",4);
						break;
				}
				break;
				case "fanspeed":
					switch ($Value){
						case 40:
							$this->Set_FanSpeed(40);
							$this->SetValue("fanspeed", 40);
							break;
						case 60:
							$this->Set_FanSpeed(60);
							$this->SetValue("fanspeed", 60);
							break;
						case 80:
							$this->Set_FanSpeed(80);
							$this->SetValue("fanspeed", 80);
							break;
					}
					break;
				case 'targethumid':
					$this->Set_Humidity($Value);
					$this->SetValue('targethumid', $Value);
					break;
			default:
				throw new Exception("Invalid Ident");
		}
	} //Function: RequestAction End

	#--------------------------------------------------------------------------------------------#
	#       Function: MessageSink()                                                              #
	#       MessageSink() IPS Standard Funktion                                                  #
	#       auf System-oder eigen definierten Meldungen reagieren.                               #
	#--------------------------------------------------------------------------------------------#
	public function MessageSink($TimeStamp, $SenderID, $Message, $Data){
		Switch($Message) {
		Case IPS_KERNELSTARTED:
			$this->LogMessage('MessageSink: Kernel hochgefahren', KL_MESSAGE);
			$this->ApplyChanges();
			break;
		Case IPS_KERNELSHUTDOWN:
			$this->LogMessage('MessageSink: Kernel runtergefahren', KL_MESSAGE);
			# Timer ausschalten ausschalten.
			$this->SetTimerInterval("UpdateTimer", 0);
			break;
		}
	}

#________________________________________________________________________________________________________________________
# Section: Public Functions                                                                                               
# Die folgenden Funktionen stehen automatisch zur Verfügung, wenn das Modul über die 'Module Control' eingefügt wurden.   
# Die Funktionen werden, mit dem selbst eingerichteten Prefix, in PHP und JSON-RPC wie folgt zur Verfügung gestellt.      
# GS_XYFunktion($Instance_id, ... );                                                                                      
#________________________________________________________________________________________________________________________
	#-----------------------------------------------------------------------------
	# Function: update()                                                      
	#...............................................................................
	# Beschreibung : Timer gesteuerte Wertabfrage                                 
	#...............................................................................
	# Parameters:                                                                   
	#    none                                                                                                                            
	#...............................................................................
	# Returns : none                                                                     
	#------------------------------------------------------------------------------  
	public Function Update() {
		$intervalTime = $this->ReadPropertyInteger("interval");
		# Onlineabfrage

		if(Sys_Ping($this->ReadPropertyString("ip"), 1000)){
			$this->SetValue("alive", true);
		}
		else{
			$this->SetValue("alive", false);
		}
		#Wenn Online dann Statusabfrage
		if($this->GetValue("alive")){
			$status = $this->Get_Status();
			if($status != false){
				if($this->ReadAttributeInteger("actIntervalTime") > $intervalTime){
					$this->WriteAttributeInteger("actIntervalTime", $intervalTime);
					$this->SetTimerInterval("UpdateTimer", $intervalTime);
				}
			}
			else{
				$this->SetValue("alive", false);
			}

		}
		else{
			#Timerintervall verzehnfachen, wenn keine Verbindung
			$this->WriteAttributeInteger("actIntervalTime", $intervalTime * 10);
			$this->SetTimerInterval("UpdateTimer", $intervalTime * 10);

		}

	}


	#-----------------------------------------------------------------------------
	# Function: Set_Power                                                      
	#...............................................................................
	# Beschreibung : Schaltet Comfee ein/aus                                 
	#...............................................................................
	# Parameters:                                                                   
	#    bool cond  = 0/1  false/true                                                                                                                              
	#...............................................................................
	# Returns : none                                                                     
	#------------------------------------------------------------------------------  
	public Function Set_Power($cond) {
		return $this->RaspCmd("--running", json_encode($cond));	
	}
	#-----------------------------------------------------------------------------
	# Function: Set_Humidity                                                      
	#...............................................................................
	# Beschreibung :  Set target humidity                                 
	#...............................................................................
	# Parameters:                                                                   
	#    humidity  = 0...100                                                                                                                                              
	#...............................................................................
	# Returns : none                                                                     
	#------------------------------------------------------------------------------  
	public Function Set_Humidity(int $humidity) {
		return $this->RaspCmd("--target-humidity", strval($humidity));
	}
	#-----------------------------------------------------------------------------
	# Function: Set_OpMode                                                      
	#...............................................................................
	# Beschreibung :   Sets operation Mode                               
	#...............................................................................
	# Parameters:                                                                   
	#    mode: 1..4        
	#	1 wireless   
	# 	2 Cont.          
	#	3 SMD
	#	4 Dyrer
	#...............................................................................
	# Returns :  none                                                                    
	#------------------------------------------------------------------------------  
	public Function Set_OpMode(int $mode) {
		return $this->RaspCmd("--mode", strval($mode));
	}
	#-----------------------------------------------------------------------------
	# Function: Set_FanSpeed                                                      
	#...............................................................................
	# Beschreibung :  Sets Fan Speed                                 
	#...............................................................................
	# Parameters:                                                                   
	#    speed      40=LOW  // 60 = MID  // 80 = HIGH                                                                                                                                   
	#...............................................................................
	# Returns : none                                                                     
	#------------------------------------------------------------------------------  
	public Function Set_FanSpeed(int $speed) {
		if($this->GetValue('mode' != 4)){
			return $this->RaspCmd("--fan-speed", strval($speed));
		}
		else{
			return false;
		}
	}

	#-----------------------------------------------------------------------------
	# Function: Set_IonMode                                                      
	#...............................................................................
	# Beschreibung :                                   
	#...............................................................................
	# Parameters:                                                                   
	#    cond: 0/1                                                                                                                                             
	#...............................................................................
	# Returns : none                                                                  
	#------------------------------------------------------------------------------  
	public Function Set_IonMode(bool $cond) {
		return $this->RaspCmd("--ion-mode", json_encode($cond));
	}
	#-----------------------------------------------------------------------------
	# Function: Set_Pump                                                      
	#...............................................................................
	# Beschreibung :  Switch Pump On/Off                                 
	#...............................................................................
	# Parameters:                                                                   
	#    cond  = 0/1                                                                                                                                          
	#...............................................................................
	# Returns : none                                                                     
	#------------------------------------------------------------------------------  
	public Function Set_Pump($cond) {
		return $this->RaspCmd("--pump", json_encode($cond));
	}



	
#________________________________________________________________________________________________________________________
# Section: Private Functions                                                                                               
# Die folgenden Funktionen stehen automatisch zur Verfügung, wenn das Modul über die 'Module Control' eingefügt wurden.   
# Die Funktionen sind nur intern verwendbar                                                                               
#________________________________________________________________________________________________________________________
	#-----------------------------------------------------------------------------
	# Function: RaspCmd()                                                     
	#...............................................................................
	# Beschreibung : führt einen Kommandozeilenbefehl auf Raspberry Pi aus                              
	#...............................................................................
	# Parameters:                                                                   
	#    string: Befehl    
	#	 string: param                                                                                                                                         
	#...............................................................................
	# Returns :                                                                      
	#------------------------------------------------------------------------------  
	protected Function RaspCmd(string $Befehl, string $param) {
		if(Sys_Ping($this->ReadPropertyString("ip"), 1000)){
			$this->SetValue('connected', false);
			$Comfee_id = $this->GetValue('id');
			$user = $this->ReadPropertyString('user');
			$pw =  $this->ReadPropertyString('pw');

			$command = "/usr/local/bin/midea-beautiful-air-cli set"." --id ".$Comfee_id." --account ".$user." --password ".$pw." ".$Befehl." ".$param." --cloud";
			$out = shell_exec($command);

			return $command;
		}
		else{
			$this->SetValue('connected', false);
			return false;
		}
	}

	#-----------------------------------------------------------------------------
	# Function: Discover                                                      
	#...............................................................................
	# Beschreibung : 
	#   Ermittelt die ID, token und key des Comfee unter angegebener IP Adresse
	#	midea-beautiful-air-cli discover --account ACCOUNT_EMAIL --password PASSWORD --address 192.0.2.3 --credentials                                  
	#...............................................................................
	# Parameters:                                                                   
	#    none                                                                                                                                                                                                                  
	#...............................................................................
	# Returns :  Array[]  
	#	[id] =>  144036023368954
    #	[addr] =>  192.168.178.46
    #	[s/n] =>  000000P0000000Q104D6F4A133DA0000
    #	[model] =>  Dehumidifier
    #	[ssid] =>  net_a1_33DA
    #	[online] =>  True
    #	[name] =>  Entfeuchter_33DA
    #	[running] =>  False
    #	[humid%] =>  70
    #	[target%] =>  55
    #	[temp] =>  19.0
    #	[fan] =>  60
    #	[tank] =>  False
    #	[mode] =>  2
    #	[ion] =>  False
    #	[filter] =>  False
    #	[pump] =>  False
    #	[defrost] =>  False
    #	[sleep] =>  False
    #	[error] =>  0
    #	[supports] =>  {'fan_speed': 7, 'auto': 1, 'dry_clothes': 1}
    #	[version] =>  3
    #	[token] =>  8028555C6CC74CEC5C4B831EE015242154337D2B5A919B221B104DB6AB3AFE77D9206F7CF16D43CB407BB21E5D8B08E1153035D4B08A21E41352C073ADC7F100
    #	[key] =>  2a7d23d1bf0f44d4b7c39635fe7b66770ee474314d0040f98ec23bb9448c12b1                                                                  
	#------------------------------------------------------------------------------  
	Protected Function Discover() {
		$user = " --account ".$this->ReadPropertyString("user");
		$pw = " --password ".$this->ReadPropertyString("pw");
		$ip =  " --ip ".$this->ReadPropertyString("ip");

		//prüfen ob Comfee erreichbar ist
		if(Sys_Ping($this->ReadPropertyString("ip"), 1000)){
			$this->SetValue('connected', true);
			//Discovery - get token and key and Appliance ID
			$command = "/usr/local/bin/midea-beautiful-air-cli discover".$user.$pw.$ip." --credentials";
			$out = shell_exec($command);
			$arr = explode("\n", $out);
			foreach($arr as $key=>$line){
				$x = explode("=", $line);
				if(count($x)>1){
					$discover[trim($x[0])] = $x[1];
				}
			}
			return $discover;
		}
		else{
			$this->SetValue('connected', false);
			return false;
		}
	}
 	#-----------------------------------------------------------------------------
	# Function: Get_Status                                                      
	#...............................................................................
	# Beschreibung : get status of Comfee with Appliance ID 
	# 				(ID bleibt immer erhalten ist an cloud gebunden)                                  
	#...............................................................................
	# Parameters: none                                                                                                                                        
	#...............................................................................
	# Returns : Array[]     
	#	[id] =>  144036023368954
    #	[addr] =>  Unknown
    #	[s/n] =>  000000P0000000Q104D6F4A133DA0000
    #	[model] =>  Dehumidifier
    #	[ssid] =>  None
    #	[online] =>  True
    #	[name] =>  Entfeuchter_33DA
    #	[running] =>  True
    #	[humid%] =>  62
    #	[target%] =>  55
    #	[temp] =>  18.0
    #	[fan] =>  40
    #	[tank] =>  False
    #	[mode] =>  2
    #	[ion] =>  False
    #	[filter] =>  False
    #	[pump] =>  False
    #	[defrost] =>  False
    #	[sleep] =>  False
    #	[error] =>  0
    #	[supports] =>  {'fan_speed': 7, 'auto': 1, 'dry_clothes': 1}
    #	[version] =>  3                                                               
	#------------------------------------------------------------------------------  
	Public Function Get_Status() {
		$Comfee_id = $this->GetValue('id');
		$user = $this->ReadPropertyString('user');
		$pw =  $this->ReadPropertyString('pw');

		$command = "/usr/local/bin/midea-beautiful-air-cli status"." --id ".$Comfee_id." --account ".$user." --password ".$pw." --cloud";
		$out = shell_exec($command);
		if($out != NULL){
			$arr = explode("\n", $out);
			foreach($arr as $key=>$line){
				$x = explode("=", $line);
				if(count($x)>1){
					$status[trim($x[0])] = $x[1];
				}
			}
			$this->setvalue('id', $status['id']);
			$this->setvalue('power', $status['running']);
			$this->setvalue('curhumid', $status['humid%']);
			$this->setvalue('targethumid', $status['target%']);
			$this->setvalue('mode', $status['mode']);
			$this->setvalue('fanspeed', $status['fan']);
			$this->setvalue('ion', $status['ion']);
			$this->setvalue('tankfull', $status['tank']);
			$this->setvalue('filter', $status['filter']);
			$this->setvalue('pumpstate', $status['pump']);
			$this->setvalue('Sleepmode', $status['sleep']);
			$this->setvalue('defrost', $status['defrost']);
			$this->setvalue('errorcode', $status['error']);
			$this->setvalue('temp', $status['temp']);
			$this->setvalue('connected', $status['online']);
			
			return $status;	
		}
		else{
			return false;
		}
		
		
	}
}
