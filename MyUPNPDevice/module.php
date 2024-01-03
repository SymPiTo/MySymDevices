<?php

 #******************************************************************************#
 # Title : MyUPNP Device                                                 #
 #                                                                              #
 # Author: PiTo                                                                 #
 #                                                                              #
 # GITHUB: <https://github.com/SymPiTo/MySymDevices/tree/master/MyUPNPDiscovery>#
 #                                                                              #
 # Version: 1.0.2  20240101                                                     #
 #******************************************************************************#
 # _____________________________________________________________________________#
 #    Section: Beschreibung                                                     #
 #    Das Modul                            #
 #    Server        			                                                #
 #    Clients                                                                   #
 # _____________________________________________________________________________#
 
 require_once(__DIR__ . "/../libs/MyHelper.php");

 class MyUPNPDevice extends IPSModule{
	#Traits aufrufen
	//use ProfileHelper;
    use DebugHelper;

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
		$this->RegisterPropertyBoolean('open', 'false');
		$this->RegisterPropertyString('ip', '');
		$this->RegisterPropertyInteger('port', 0);

		$this->RegisterVariableString('Minimum', 'Minimum', '', 1);
    }

	#--------------------------------------------------------------------------------#
	#       Function: ApplyChanges()                                                 #
	#       Einträge vor ApplyChanges() werden sowohl beim Systemstart               #
	#       als auch beim Ändern der Parameter in der Form ausgeführt.               #
	#       ApplyChanges() wird ausgeführt, beim Anlegen der Instanz                 #
	#       und beim ändern der Parameter in der Form                                #
	#--------------------------------------------------------------------------------#
	
    public function ApplyChanges()
    {
        parent::ApplyChanges();
    }

 }