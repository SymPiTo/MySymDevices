<?php

 #******************************************************************************#
 # Title : MyComfee                                                        #
 #                                                                              #
 # Author: PiTo                                                                 #
 #                                                                              #
 # GITHUB: <https://github.com/SymPiTo/MySymDevices/tree/master/DenonCeol>      #
 #                                                                              #
 # Version: 1.0.1  20220604                                                     #
 #******************************************************************************#
 # _____________________________________________________________________________#
 #    Section: Beschreibung                                                     #
 #    Das Modul dient zur Steuerung des Denon CEOL Players.                     #
 #    Über eine UPNP Schnittstelle.                                             #
 #                                                                              #
 # _____________________________________________________________________________#
class MyComfee extends IPSModule{

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
		$this->RegisterPropertyString('ip', '');
		$this->RegisterPropertyString('user', '');
		$this->RegisterPropertyString('pw', '');
        # Variablen registrieren
		$this->RegisterVariableBoolean('power', 'Power', '', 0);
		$this->RegisterVariableInteger('curhumid', 'current_humidity', '', 1);
		$this->RegisterVariableInteger('targethumid', 'target_humidity', '', 2);
		$this->RegisterVariableInteger('mode', 'mode', '', 3);
		$this->RegisterVariableInteger('fanspeed', 'Fan Speed', '', 4);
		$this->RegisterVariableBoolean('ion', 'ion', '', 5);
		$this->RegisterVariableBoolean('tankfull', 'Tank_Full', '', 6);
		$this->RegisterVariableBoolean('filter', 'Filter_Replacement', '', 7);
		$this->RegisterVariableBoolean('pumpstate', 'Pump_State', '', 8);
		$this->RegisterVariableBoolean('Sleepmode', 'Sleep_Mode', '', 9);
		$this->RegisterVariableBoolean('defrost', 'Defrosting', '', 10);
		$this->RegisterVariableString('errorcode', 'Error_Code', '', 11);
		$this->RegisterVariableInteger('temp', 'Temperature', '', 12);
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

	}

#________________________________________________________________________________________________________________________
# Section: Public Functions                                                                                               
# Die folgenden Funktionen stehen automatisch zur Verfügung, wenn das Modul über die 'Module Control' eingefügt wurden.   
# Die Funktionen werden, mit dem selbst eingerichteten Prefix, in PHP und JSON-RPC wie folgt zur Verfügung gestellt.      
# GS_XYFunktion($Instance_id, ... );                                                                                      
#________________________________________________________________________________________________________________________

	#-----------------------------------------------------------------------------
	# Function: GetPlantData                                                      
	#...............................................................................
	# Beschreibung : Holt die Sensor Daten per API                                  
	#...............................................................................
	# Parameters:                                                                   
	#    none                                                                       
	#...............................................................................
	# Returns :                                                                      
	#------------------------------------------------------------------------------  

	public Function x() {

	}

#________________________________________________________________________________________________________________________
# Section: Private Functions                                                                                              
# Die folgenden Funktionen sind nur zur internen Verwendung verfügbar                                                     
# Hilfsfunktionen                                                                                                         
# GS_XYFunktion($Instance_id, ... );                                                                                      
#________________________________________________________________________________________________________________________

	#------------------------------------------------------------------------------
	# Function: GetPlantData                                                      
	#...............................................................................
	# Beschreibung : Holt die Sensor Daten per API                                  
	#...............................................................................
	# Parameters:                                                                   
	#    none                                                                       
	#...............................................................................
	# Returns :                                                                     
	#------------------------------------------------------------------------------  */

	protected Function y() {

	}

}
