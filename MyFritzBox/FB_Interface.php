<?php

trait FB_soap  
{





	/*//////////////////////////////////////////////////////////////////////////////
	--------------------------------------------------------------------------------
	Funktion 	:	Gibt den DynDNSName von MyFritz zurück
						 
	Befehl		:	 
	--------------------------------------------------------------------------------
	Soap / upnp-command
	--------------------------------------------------------------------------------
	Parameter:		 none
					
	Rückgabewert: 	 Array
							[NewEnabled] => 1
							[NewDeviceRegistered] => 1
							[NewDynDNSName] => ylgsiletvcrasj6i.myfritz.net
							[NewPort] => 55759
	//////////////////////////////////////////////////////////////////////////////*/
	  public function Get_MyFritz_DynDNS(){
	    return $this->processSoapCall(
						"/upnp/control/x_myfritz",

					    "urn:dslforum-org:service:X_AVM-DE_MyFritz:1",

					    "GetInfo",

					       array(
							 

							)
		);
	  }

	/*//////////////////////////////////////////////////////////////////////////////
	--------------------------------------------------------------------------------
	Funktion 	:	 
						 
	Befehl		:	 
	--------------------------------------------------------------------------------
	Soap / upnp-command
	--------------------------------------------------------------------------------
	Parameter:		 none
					
	Rückgabewert: 	 Array
							[NewEnabled] => 1
							[NewDeviceRegistered] => 1
							[NewDynDNSName] => ylgsiletvcrasj6i.myfritz.net
							[NewPort] => 55759
	//////////////////////////////////////////////////////////////////////////////*/
	public function GetNumberOfServices(){
	    return $this->processSoapCall(
						"/upnp/control/x_myfritz",

					    "urn:dslforum-org:service:X_AVM-DE_MyFritz:1",

					    "GetNumberOfServices",

					       array(
								 

							)
		);
	  }

	/*//////////////////////////////////////////////////////////////////////////////
	--------------------------------------------------------------------------------
	Funktion 	:	 
						 
	Befehl		:	 
	--------------------------------------------------------------------------------
	Soap / upnp-command
	--------------------------------------------------------------------------------
	Parameter:		 none
					
	Rückgabewert: 	 Array
						[NewEnabled] => 1
						[NewName] => Kodi-Alexa-Tablet
						[NewScheme] => http://
						[NewPort] => 2340
						[NewURLPath] => 
						[NewType] => 
						[NewIPv4ForwardingWarning] => 0
						[NewIPv4Addresses] => 192.168.178.25
						[NewIPv6Addresses] => 
						[NewIPv6InterfaceIDs] => ::4c4f:e72c:de8e:2776
						[NewMACAddress] => 88:70:8C:50:A8:63
						[NewHostName] => Lenovo-PC
						[NewDynDnsLabel] => lenovo-pc
						[NewStatus] => 200
	//////////////////////////////////////////////////////////////////////////////*/
	public function GetServiceByIndex($NewIndex ){
	    return $this->processSoapCall(
						"/upnp/control/x_myfritz",

					    "urn:dslforum-org:service:X_AVM-DE_MyFritz:1",

					    "GetServiceByIndex",

					       array(
								new SoapParam($NewIndex   ,"NewIndex" )

							)
		);
	}

	/*//////////////////////////////////////////////////////////////////////////////
	--------------------------------------------------------------------------------
	Funktion 	:	 
						 
	Befehl		:	 
	--------------------------------------------------------------------------------
	Soap / upnp-command
	--------------------------------------------------------------------------------
	Parameter:		 none
					
	Rückgabewert: 	  integer
 
	//////////////////////////////////////////////////////////////////////////////*/
	public function GetNumberOfClients(){
	    return $this->processSoapCall(
						"/upnp/control/x_voip",

					    "urn:dslforum-org:service:X_VoIP:1",

					    "X_AVM-DE_GetNumberOfClients",

					       array(
								 

							)
		);
	}

	/*//////////////////////////////////////////////////////////////////////////////
	--------------------------------------------------------------------------------
	Funktion 	:	 
						 
	Befehl		:	 
	--------------------------------------------------------------------------------
	Soap / upnp-command
	--------------------------------------------------------------------------------
	Parameter:		 ClientIndex
					
	Rückgabewert: 	 Array
		[NewX_AVM-DE_ClientUsername] => 4962214309070
		[NewX_AVM-DE_ClientRegistrar] => 192.168.178.1
		[NewX_AVM-DE_ClientRegistrarPort] => 5060
		[NewX_AVM-DE_PhoneName] => IP-Symcon
		[NewX_AVM-DE_OutGoingNumber] => 4309070
	//////////////////////////////////////////////////////////////////////////////*/
	public function GetClient($ClientIndex){
	    return $this->processSoapCall(
						"/upnp/control/x_voip",

					    "urn:dslforum-org:service:X_VoIP:1",

					    "X_AVM-DE_GetClient",

					       array(
								new SoapParam($ClientIndex   ,"NewX_AVM-DE_ClientIndex" )

							)
		);
	}

	/*//////////////////////////////////////////////////////////////////////////////
	--------------------------------------------------------------------------------
	Funktion 	:	 
						 
	Befehl		:	 
	--------------------------------------------------------------------------------
	Soap / upnp-command
	--------------------------------------------------------------------------------
	Parameter:		 $NewIndex
					
	Rückgabewert: 	 Array
 
	//////////////////////////////////////////////////////////////////////////////*/
	public function GetInfo_AB($NewIndex){
	    return $this->processSoapCall(
						"/upnp/control/x_tam",

					    "urn:dslforum-org:service:X_AVM-DE_TAM:1",

					    "GetInfo",

					       array(
							new SoapParam($NewIndex   ,"NewIndex" )
							)
		);
	}



//  0 ------------------------------------------------------------------------

	/*//////////////////////////////////////////////////////////////////////////////
	--------------------------------------------------------------------------------
	Funktion 	:	 DeviceInfo()
	Befehl		:	 Fritz Box Informationen und Log's auslesen 
	--------------------------------------------------------------------------------
	Parameter:			none
	Rückgabewert: 	 	Array
		[NewManufacturerName] => AVM
		[NewManufacturerOUI] => 00040E
		[NewModelName] => FRITZ!Box 7490
		[NewDescription] => FRITZ!Box 7490 113.07.12
		[NewProductClass] => FRITZ!Box
		[NewSerialNumber] => 3431C4B672E2
		[NewSoftwareVersion] => 113.07.12
		[NewHardwareVersion] => FRITZ!Box 7490
		[NewSpecVersion] => 1.0
		[NewProvisioningCode] => 002.000.000.000
		[NewUpTime] => 1129627
		[NewDeviceLog] => 25.08.20 05:07:06 IPv6-Präfix wurde erfolgreich aktualisiert. Neues Präfix: 2001:1a81:722d:800::/56
	//////////////////////////////////////////////////////////////////////////////*/
	public function DeviceInfo(){
	    return $this->processSoapCall(
						"/upnp/control/deviceinfo",
					    "urn:dslforum-org:service:DeviceInfo:1",
					    "GetInfo",
					       	array(
							)
		);
	}


//  1 ------------------------------------------------------------------------

	/*//////////////////////////////////////////////////////////////////////////////
	--------------------------------------------------------------------------------
	Funktion 	:	 Reboot()
	Befehl		:	 Fritz Box rebooten
	--------------------------------------------------------------------------------
	Parameter:			none
	Rückgabewert: 	 	none

	//////////////////////////////////////////////////////////////////////////////*/
	public function Reboot(){
	    return $this->processSoapCall(
						"/upnp/control/deviceconfig",
					    "urn:dslforum-org:service:DeviceConfig:1",
					    "Reboot",
					       	array(
							)
		);
	}



//  2 ------------------------------------------------------------------------

	/*//////////////////////////////////////////////////////////////////////////////
	--------------------------------------------------------------------------------
	Funktion 	:	 GetDefaultConnectionService()
	Befehl		:	 
	--------------------------------------------------------------------------------
	Parameter:			none
	Rückgabewert: 	 	1.WANPPPConnection.1
	//////////////////////////////////////////////////////////////////////////////*/
	public function GetDefaultConnectionService(){
	    return $this->processSoapCall(
						"/upnp/control/layer3forwarding",
					    "urn:dslforum-org:service:Layer3Forwarding:1",
					    "GetDefaultConnectionService",
					       	array(
							)
		);
	}

	/*//////////////////////////////////////////////////////////////////////////////
	--------------------------------------------------------------------------------
	Funktion 	:	 
	Befehl		:	 
	--------------------------------------------------------------------------------
	Parameter:		none
	Rückgabewert: 	 0
	//////////////////////////////////////////////////////////////////////////////*/
	public function GetForwardNumberOfEntries(){
	    return $this->processSoapCall(
						"/upnp/control/layer3forwarding",
					    "urn:dslforum-org:service:Layer3Forwarding:1",
					    "GetForwardNumberOfEntries",
					       array(
							)
		);
	}

	/*//////////////////////////////////////////////////////////////////////////////
	--------------------------------------------------------------------------------
	Funktion 	:	 
						 
	Befehl		:	 
	--------------------------------------------------------------------------------
	Soap / upnp-command
	--------------------------------------------------------------------------------
	Parameter:		 $NewIndex
					
	Rückgabewert: 	 Array
 
	//////////////////////////////////////////////////////////////////////////////*/
	public function GetGenericForwardingEntry($NewIndex){
	    return $this->processSoapCall(
						"/upnp/control/layer3forwarding",

					    "urn:dslforum-org:service:Layer3Forwarding:1",

					    "GetGenericForwardingEntry",

					       array(
							new SoapParam($NewIndex   ,"NewForwardingIndex" )
							)
		);
	}


//  4 ------------------------------------------------------------------------

	/*//////////////////////////////////////////////////////////////////////////////
	--------------------------------------------------------------------------------
	Funktion 	:	 
	Befehl		:	 
	--------------------------------------------------------------------------------
	Parameter:		none
	Rückgabewert: 	 Array(
		[NewURL] => https://acs2.online.de
		[NewUsername] => 2006-963
		[NewPeriodicInformEnable] => 1
		[NewPeriodicInformInterval] => 86400
		[NewPeriodicInformTime] => 2018-01-01T15:38:41Z
		[NewParameterKey] => 
		[NewParameterHash] => 
		[NewConnectionRequestURL] => http://[2001:1a81:7002:a21:3631:c4ff:feb6:72e6]:49699/4ca815415439806
		[NewConnectionRequestUsername] => 
		[NewUpgradesManaged] => 0
	//////////////////////////////////////////////////////////////////////////////*/
	public function GetInfoMServer(){
	    return $this->processSoapCall(
						"/upnp/control/mgmsrv",
					    "urn:dslforum-org:service:ManagementServer:1",
					    "GetInfo",
					       array(
							)
		);
	}











//  10 ------------------------------------------------------------------------

	/*//////////////////////////////////////////////////////////////////////////////
	--------------------------------------------------------------------------------
	Funktion 	:	 
						 
	Befehl		:	 
	--------------------------------------------------------------------------------
	Soap / upnp-command
	--------------------------------------------------------------------------------
	Parameter:		  
					
	Rückgabewert: 	 Array
 
	//////////////////////////////////////////////////////////////////////////////*/
	public function GetInfo_ST(){
	    return $this->processSoapCall(
						"/upnp/control/x_speedtest",

					    "urn:dslforum-org:service:X_AVM-DE_Speedtest:1",

					    "GetInfo",

					       array(
							 
							)
		);
	}


//  11 ------------------------------------------------------------------------


	/*//////////////////////////////////////////////////////////////////////////////
	--------------------------------------------------------------------------------
	Funktion 	:	 
	Befehl		:	 DDNS Server
	--------------------------------------------------------------------------------
	Parameter:		none
	Rückgabewert: 	 Array(
		[NewEnabled] => 0
		[NewProviderName] => No-IP.com
		[NewUpdateURL] => /ducupdate.php?update=username=&pass=&h[]=&ip=
		[NewDomain] => dpsghdkirchheim.ddns.net
		[NewStatusIPv4] => undefined
		[NewStatusIPv6] => undefined
		[NewUsername] => Torsten.Beck1@gmx.de
		[NewMode] => ddns_v4
		[NewServerIPv4] => dynupdate.no-ip.com:8245
		[NewServerIPv6] => 
	//////////////////////////////////////////////////////////////////////////////*/
	public function GetDDNSInfo(){
	    return $this->processSoapCall(
						"/upnp/control/x_remote",
					    "urn:dslforum-org:service:X_AVM-DE_RemoteAccess:1",
					    "GetDDNSInfo",
					       array(
							)
		);
	}



//  14 ------------------------------------------------------------------------

/*//////////////////////////////////////////////////////////////////////////////
	--------------------------------------------------------------------------------
	Funktion 	:	 
						 
	Befehl		:	 
	--------------------------------------------------------------------------------
	Soap / upnp-command
	--------------------------------------------------------------------------------
	Parameter:		 none
					
	Rückgabewert: 	 Array 
 
	//////////////////////////////////////////////////////////////////////////////*/
	public function GetInfoContact(){
		return  $this->processSoapCall(
						"/upnp/control/x_contact",

					    "urn:dslforum-org:service:X_AVM-DE_OnTel:1",

					    "GetInfo",

					       array(
							 
							)
				);
 
	}
	
/*//////////////////////////////////////////////////////////////////////////////
	--------------------------------------------------------------------------------
	Funktion 	:	 
						 
	Befehl		:	 
	--------------------------------------------------------------------------------
	Soap / upnp-command
	--------------------------------------------------------------------------------
	Parameter:		 none
					
	Rückgabewert: 	 Array 
            [Id] => 2536
            [Type] => 2
            [Caller] => 017655089540
            [Called] => SIP: 339458
            [CalledNumber] => 339458
            [Name] => Torsten Handy
            [Numbertype] => sip
            [Port] => -1
            [Date] => 08.08.20 16:47
            [Duration] => 0:00
	//////////////////////////////////////////////////////////////////////////////*/
	public function GetCallList($NoOfCalls = 0){
	    $result = $this->processSoapCall(
						"/upnp/control/x_contact",

					    "urn:dslforum-org:service:X_AVM-DE_OnTel:1",

					    "GetCallList",

					       array(
							 
							)
		);
		$xml = @simplexml_load_file($result);

        if ($xml === false)
        {
            IPS_LogMessage(IPS_GetObject($this->InstanceID)['ObjectName'], "Fehler beim laden der callList!");
            return false;
        }
		$xml = new simpleXMLElement($xml->asXML());

		if($NoOfCalls == 0){
			$NoOfCalls = count($xml);
		}
        
		$callList_filtered = array();
		for($i = 0; $i < $NoOfCalls; $i++){

            $callList_filtered[$i] = $xml->Call[$i] ;
        }
        return $callList_filtered;
	}


/*//////////////////////////////////////////////////////////////////////////////
	--------------------------------------------------------------------------------
	Funktion 	:	 
						 
	Befehl		:	 
	--------------------------------------------------------------------------------
	Soap / upnp-command
	--------------------------------------------------------------------------------
	Parameter:		 none
					
	Rückgabewert: 	 Array 
 
	//////////////////////////////////////////////////////////////////////////////*/
	public function GetPhonebookList(){
		return  $this->processSoapCall(
						"/upnp/control/x_contact",

					    "urn:dslforum-org:service:X_AVM-DE_OnTel:1",

					    "GetPhonebookList",

					       array(
							 
							)
				);
 
    }
        
 
	/*//////////////////////////////////////////////////////////////////////////////
	--------------------------------------------------------------------------------
	Funktion 	:	 
						 
	Befehl		:	 
	--------------------------------------------------------------------------------
	Soap / upnp-command
	--------------------------------------------------------------------------------
	Parameter:		 $NewPhonebookID
					
	Rückgabewert: 	 Array
 
	//////////////////////////////////////////////////////////////////////////////*/
	public function GetPhonebook($PhonebookID){
	    return $this->processSoapCall(
						"/upnp/control/layer3forwarding",

					    "urn:dslforum-org:service:Layer3Forwarding:1",

					    "GetPhonebook",

					       array(
							new SoapParam($PhonebookID   ,"NewPhonebookID" )
							)
		);
	}




//  22 ------------------------------------------------------------------------

	/*//////////////////////////////////////////////////////////////////////////////
	--------------------------------------------------------------------------------
	Funktion 	:	 
	Befehl		:	 
	--------------------------------------------------------------------------------
	Parameter:		  
	Rückgabewert: 	 Array
		[NewEnable] => 1
		[NewStatus] => Up
		[NewMaxBitRate] => Auto
		[NewChannel] => 11
		[NewSSID] => FRITZ!Box Fon WLAN 7490
		[NewBeaconType] => 11i
		[NewMACAddressControlEnabled] => 1
		[NewStandard] => n
		[NewBSSID] => 34:31:C4:B6:72:E4
		[NewBasicEncryptionModes] => None
		[NewBasicAuthenticationMode] => None
		[NewMaxCharsSSID] => 32
		[NewMinCharsSSID] => 1
		[NewAllowedCharsSSID] => 0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz !"#$%&'()*+,-./:;?@[\]^_`{|}~
		[NewMinCharsPSK] => 64
		[NewMaxCharsPSK] => 64
		[NewAllowedCharsPSK] => 0123456789ABCDEFabcdef
	//////////////////////////////////////////////////////////////////////////////*/
	public function GetInfoWan(){
	    return $this->processSoapCall(
						"/upnp/control/wlanconfig1",
					    "urn:dslforum-org:service:WLANConfiguration:1",
					    "GetInfo",
					       array(
							)
		);
	}


	/*//////////////////////////////////////////////////////////////////////////////
	--------------------------------------------------------------------------------
	Funktion 	:	 
	Befehl		:	 
	--------------------------------------------------------------------------------
	Parameter:		  
	Rückgabewert: 	 Array
		[NewX_AVM-DE_WPSMode] => stop
		[NewX_AVM-DE_WPSStatus] => off
	//////////////////////////////////////////////////////////////////////////////*/
	public function GetWPSInfo(){
	    return $this->processSoapCall(
						"/upnp/control/wlanconfig1",
					    "urn:dslforum-org:service:WLANConfiguration:1",
					    "X_AVM-DE_GetWPSInfo",
					       array(
							)
		);
	}




//  25 ------------------------------------------------------------------------

	/*//////////////////////////////////////////////////////////////////////////////
	--------------------------------------------------------------------------------
	Funktion 	:	 
						 
	Befehl		:	 
	--------------------------------------------------------------------------------
	Soap / upnp-command
	--------------------------------------------------------------------------------
	Parameter:		  
					
	Rückgabewert: 	 Array
 
	//////////////////////////////////////////////////////////////////////////////*/
	public function GetHostNumberOfEntries(){
	    return $this->processSoapCall(
						"/upnp/control/hosts",

					    "urn:dslforum-org:service:Hosts:1",

					    "GetHostNumberOfEntries",

					       array(
							 
							)
		);
	}

	/*//////////////////////////////////////////////////////////////////////////////
	--------------------------------------------------------------------------------
	Funktion 	:	 
						 
	Befehl		:	 
	--------------------------------------------------------------------------------
	Soap / upnp-command
	--------------------------------------------------------------------------------
	Parameter:	$NewMACAddress = 	"B8:27:EB:9D:78:B5"  
					
	Rückgabewert: 	 Array
						[NewIPAddress] => 192.168.178.28
						[NewAddressSource] => DHCP
						[NewLeaseTimeRemaining] => 0
						[NewInterfaceType] => Ethernet
						[NewActive] => 1
						[NewHostName] => ipspi
	//////////////////////////////////////////////////////////////////////////////*/
	public function GetSpecificHostEntry($NewMACAddress){
	    return $this->processSoapCall(
						"/upnp/control/hosts",

					    "urn:dslforum-org:service:Hosts:1",

					    "GetSpecificHostEntry",

					       array(
							new SoapParam($NewMACAddress   ,"NewMACAddress" )

							)
		);
	}

	/*//////////////////////////////////////////////////////////////////////////////
	--------------------------------------------------------------------------------
	Funktion 	:	 
						 
	Befehl		:	 
	--------------------------------------------------------------------------------
	Soap / upnp-command
	--------------------------------------------------------------------------------
	Parameter:	$NewIndex = 	 
					
	Rückgabewert: 	 Array
						[NewIPAddress] => 192.168.178.27
						[NewAddressSource] => DHCP
						[NewLeaseTimeRemaining] => 0
						[NewMACAddress] => 30:05:5C:4F:F7:0E
						[NewInterfaceType] => Ethernet
						[NewActive] => 1
						[NewHostName] => Brother-Drucker
	//////////////////////////////////////////////////////////////////////////////*/
	public function GetGenericHostEntry($NewIndex){
	    return $this->processSoapCall(
						"/upnp/control/hosts",

					    "urn:dslforum-org:service:Hosts:1",

					    "GetGenericHostEntry",

					       array(
							new SoapParam( (int)$NewIndex, "NewIndex" )

							)
		);
	}


	/*//////////////////////////////////////////////////////////////////////////////
	--------------------------------------------------------------------------------
	Funktion 	:	 
						 
	Befehl		:	 
	--------------------------------------------------------------------------------
	Soap / upnp-command
	--------------------------------------------------------------------------------
	Parameter:	$ip = 	 "192.168.178.28"
					
	Rückgabewert: 	 Array
    [NewMACAddress] => B8:27:EB:9D:78:B5
    [NewActive] => 1
    [NewHostName] => ipspi
    [NewInterfaceType] => Ethernet
    [NewX_AVM-DE_Port] => 1
    [NewX_AVM-DE_Speed] => 1000
    [NewX_AVM-DE_UpdateAvailable] => 0
    [NewX_AVM-DE_UpdateSuccessful] => unknown
    [NewX_AVM-DE_InfoURL] => 
    [NewX_AVM-DE_Model] => dhcpcd-8.1.2:Linux-5.4.51-v7+:armv7l:BCM2835
    [NewX_AVM-DE_URL] => http://192.168.178.28
	//////////////////////////////////////////////////////////////////////////////*/
	public function GetSpecificHostEntryByIP($ip){
	    return $this->processSoapCall(
						"/upnp/control/hosts",

					    "urn:dslforum-org:service:Hosts:1",

					    "X_AVM-DE_GetSpecificHostEntryByIP",

					       array(
							new SoapParam($ip   ,"NewIPAddress" )

							)
		);
	}


	/*//////////////////////////////////////////////////////////////////////////////
	--------------------------------------------------------------------------------
	Funktion 	:	 
						 
	Befehl		:	 
	--------------------------------------------------------------------------------
	Soap / upnp-command
	--------------------------------------------------------------------------------
	Parameter: 
					
	Rückgabewert: 	 Array
 
	//////////////////////////////////////////////////////////////////////////////*/
	public function GetHostListPath( ){
	    return $this->processSoapCall(
						"/upnp/control/hosts",

					    "urn:dslforum-org:service:Hosts:1",

					    "X_AVM-DE_GetHostListPath",

					       array(
							 

							)
		);
	}


	/*//////////////////////////////////////////////////////////////////////////////
	--------------------------------------------------------------------------------
	Funktion 	:	 
						 
	Befehl		:	 
	--------------------------------------------------------------------------------
	Soap / upnp-command
	--------------------------------------------------------------------------------
	Parameter: 
					
	Rückgabewert: 	 Array
 
	//////////////////////////////////////////////////////////////////////////////*/
	public function GetMeshListPath( ){
	    return $this->processSoapCall(
						"/upnp/control/hosts",

					    "urn:dslforum-org:service:Hosts:1",

					    "X_AVM-DE_GetMeshListPath",

					       array(
							 

							)
		);
	}







//  26 ------------------------------------------------------------------------
/*//////////////////////////////////////////////////////////////////////////////
	--------------------------------------------------------------------------------
	Funktion 	:	 
						 
	Befehl		:	 
	--------------------------------------------------------------------------------
	Soap / upnp-command
	--------------------------------------------------------------------------------
	Parameter:		  
					
	Rückgabewert: 	 Array
						[NewEnable] => 1
						[NewStatus] => Up
						[NewMACAddress] => 34:31:c4:b6:72:e2
						[NewMaxBitRate] => Auto
						[NewDuplexMode] => Auto
	//////////////////////////////////////////////////////////////////////////////*/
	public function Net_GetInfo(){
	    return $this->processSoapCall(
						"/upnp/control/lanethernetifcfg",

					    "urn:dslforum-org:service:LANEthernetInterfaceConfig:1",

					    "GetInfo",

					       array(
							 
							)
		);
	}


//  27 ------------------------------------------------------------------------

/*//////////////////////////////////////////////////////////////////////////////
	--------------------------------------------------------------------------------
	Funktion 	:	 
						 
	Befehl		:	 
	--------------------------------------------------------------------------------
	Soap / upnp-command
	--------------------------------------------------------------------------------
	Parameter:		  
					
	Rückgabewert: 	 Array
						[NewDHCPServerConfigurable] => 1
						[NewDHCPRelay] => 0
						[NewMinAddress] => 192.168.178.5
						[NewMaxAddress] => 192.168.178.100
						[NewReservedAddresses] => 
						[NewDHCPServerEnable] => 1
						[NewDNSServers] => 192.168.178.1
						[NewDomainName] => (none)
						[NewIPRouters] => 192.168.178.1
						[NewSubnetMask] => 255.255.255.0
	//////////////////////////////////////////////////////////////////////////////*/
	public function Host_GetInfo(){
	    return $this->processSoapCall(
						"/upnp/control/lanhostconfigmgm",

					    "urn:dslforum-org:service:LANHostConfigManagement:1",

					    "GetInfo",

					       array(
							 
							)
		);
	}


//  28 ------------------------------------------------------------------------

/*//////////////////////////////////////////////////////////////////////////////
	--------------------------------------------------------------------------------
	Funktion 	:	 
						 
	Befehl		:	 
	--------------------------------------------------------------------------------
	Soap / upnp-command
	--------------------------------------------------------------------------------
	Parameter:		  
					
	Rückgabewert: 	 Array
						[NewWANAccessType] => DSL
						[NewLayer1UpstreamMaxBitRate] => 767000
						[NewLayer1DownstreamMaxBitRate] => 12564000
						[NewPhysicalLinkStatus] => Up
	//////////////////////////////////////////////////////////////////////////////*/
	public function GetCommonLinkProperties(){
	    return $this->processSoapCall(
						"/upnp/control/wancommonifconfig1",

					    "urn:dslforum-org:service:WANCommonInterfaceConfig:1",

					    "GetCommonLinkProperties",

					       array(
							 
							)
		);
	}


//  29 ------------------------------------------------------------------------

	/*//////////////////////////////////////////////////////////////////////////////
	--------------------------------------------------------------------------------
	Funktion 	:	 
						 
	Befehl		:	 
	--------------------------------------------------------------------------------
	Soap / upnp-command
	--------------------------------------------------------------------------------
	Parameter:		  

	Rückgabewert: Array	  
					[NewEnable] => 1
					[NewStatus] => Up
					[NewDataPath] => Interleaved
					[NewUpstreamCurrRate] => 763
					[NewDownstreamCurrRate] => 12442
					[NewUpstreamMaxRate] => 763
					[NewDownstreamMaxRate] => 12540
					[NewUpstreamNoiseMargin] => 210
					[NewDownstreamNoiseMargin] => 70
					[NewUpstreamAttenuation] => 120
					[NewDownstreamAttenuation] => 240
					[NewATURVendor] => 41564d00
					[NewATURCountry] => 0400
					[NewUpstreamPower] => 513
					[NewDownstreamPower] => 519
	//////////////////////////////////////////////////////////////////////////////*/
	public function DSL_GetInfo(){
	    return $this->processSoapCall(
						"/upnp/control/wandslifconfig1",

					    "urn:dslforum-org:service:WANDSLInterfaceConfig:1",

					    "GetInfo",

					       array(
							 
							)
		);
	}


	/*//////////////////////////////////////////////////////////////////////////////
	--------------------------------------------------------------------------------
	Funktion 	:	 
	Befehl		:	 
	--------------------------------------------------------------------------------
	Parameter:		  
	Rückgabewert: Array	  
		[NewX_AVM-DE_DSLDiagnoseState] => NONE
		[NewX_AVM-DE_CableNokDistance] => -1
		[NewX_AVM-DE_DSLLastDiagnoseTime] => 0
		[NewX_AVM-DE_DSLSignalLossTime] => 0
		[NewX_AVM-DE_DSLActive] => 1
		[NewX_AVM-DE_DSLSync] => 1
	//////////////////////////////////////////////////////////////////////////////*/
	public function GetDSLDiagnoseInfo(){
	    return $this->processSoapCall(
						"/upnp/control/wandslifconfig1",
					    "urn:dslforum-org:service:WANDSLInterfaceConfig:1",
					    "X_AVM-DE_GetDSLDiagnoseInfo",
					        array(
							)
		);
	}



//  30 ------------------------------------------------------------------------

	/*//////////////////////////////////////////////////////////////////////////////
	--------------------------------------------------------------------------------
	Funktion 	:	 
						 
	Befehl		:	 
	--------------------------------------------------------------------------------
	Soap / upnp-command
	--------------------------------------------------------------------------------
	Parameter:		  

	Rückgabewert: Array	  
					[NewEnable] => 1
					[NewLinkStatus] => Up
					[NewLinkType] => PPPoE
					[NewDestinationAddress] => PVC: 1/32
					[NewATMEncapsulation] => LLC
					[NewAutoConfig] => 0
					[NewATMQoS] => UBR
					[NewATMPeakCellRate] => 0
					[NewATMSustainableCellRate] => 0
	//////////////////////////////////////////////////////////////////////////////*/
	public function Link_GetInfo(){
	    return $this->processSoapCall(
						"/upnp/control/wandsllinkconfig1",

					    "urn:dslforum-org:service:WANDSLLinkConfig:1",

					    "GetInfo",

					       array(
							 
							)
		);
	}

	/*//////////////////////////////////////////////////////////////////////////////
	--------------------------------------------------------------------------------
	Funktion 	:	 
	Befehl		:	 
	--------------------------------------------------------------------------------
	Parameter:		  
	Rückgabewert: Array	  

	//////////////////////////////////////////////////////////////////////////////*/
	public function GetDestinationAddress(){
	    return $this->processSoapCall(
						"/upnp/control/wandsllinkconfig1",
					    "urn:dslforum-org:service:WANDSLLinkConfig:1",
					    "GetDestinationAddress",
					       	array(
							)
		);
	}
	
	

//  31 ------------------------------------------------------------------------

	/*//////////////////////////////////////////////////////////////////////////////
	--------------------------------------------------------------------------------
	Funktion 	:	 
						 
	Befehl		:	 
	--------------------------------------------------------------------------------
	Soap / upnp-command
	--------------------------------------------------------------------------------
	Parameter:		 $NewIndex
					
	Rückgabewert: 	 Unavailable
 
	//////////////////////////////////////////////////////////////////////////////*/
	public function GetEthernetLinkStatus(){
	    return $this->processSoapCall(
						"/upnp/control/wanethlinkconfig1",

					    "urn:dslforum-org:service:WANEthernetLinkConfig:1",

					    "GetEthernetLinkStatus",

					       array(
							 
							)
		);
	}




//  32 ------------------------------------------------------------------------

	/*//////////////////////////////////////////////////////////////////////////////
	--------------------------------------------------------------------------------
	Funktion 	:	Internet neu Verbinden
						 
	Befehl		:	 
	--------------------------------------------------------------------------------
	Soap / upnp-command
	--------------------------------------------------------------------------------
	Parameter:		 none
					
	Rückgabewert: 	  
	    
				 
	//////////////////////////////////////////////////////////////////////////////*/
	public function ForceTermination(){
	    return $this->processSoapCall(
						"/upnp/control/wanpppconn1",

					    "urn:dslforum-org:service:WANPPPConnection:1",

					    "ForceTermination",

					       array(
							 

							)
		);
	}         

	  
	/*//////////////////////////////////////////////////////////////////////////////
	--------------------------------------------------------------------------------
	Funktion 	:	WLAN Verbinden abfragen
						 
	Befehl		:	 geht noch nicht
	--------------------------------------------------------------------------------
	Soap / upnp-command
	--------------------------------------------------------------------------------
	Parameter:		 none
					
	Rückgabewert: 	  
							 
	//////////////////////////////////////////////////////////////////////////////*/
	public function RequestConnection(){
	    return $this->processSoapCall(
						"/upnp/control/wanpppconn1",

					    "urn:dslforum-org:service:WANPPPConnection:1",

					    "RequestConnection",

					       array(
							 

							)
		);
	}


	/*//////////////////////////////////////////////////////////////////////////////
	--------------------------------------------------------------------------------
	Funktion 	:	 
						 
	Befehl		:	 
	--------------------------------------------------------------------------------
	Soap / upnp-command
	--------------------------------------------------------------------------------
	Parameter:		  
					
	Rückgabewert: 	 Array
						[NewEnable] => 1
						[NewConnectionStatus] => Connected
						[NewPossibleConnectionTypes] => IP_Routed, IP_Bridged
						[NewConnectionType] => IP_Routed
						[NewName] => internet
						[NewUptime] => 61534
						[NewUpstreamMaxBitRate] => 691018
						[NewDownstreamMaxBitRate] => 11335245
						[NewLastConnectionError] => ERROR_NONE
						[NewIdleDisconnectTime] => 0
						[NewRSIPAvailable] => 0
						[NewUserName] => 1und1/2006-963@online.de
						[NewNATEnabled] => 1
						[NewExternalIPAddress] => 92.194.29.159
						[NewDNSServers] => 2001:1a80::1, 2001:1a80::2,212.202.215.1,212.202.215.2
						[NewMACAddress] => 34:31:C4:B6:72:E6
						[NewConnectionTrigger] => AlwaysOn
						[NewLastAuthErrorInfo] => 
						[NewMaxCharsUsername] => 128
						[NewMinCharsUsername] => 3
						[NewAllowedCharsUsername] => 0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz-._@()#/%[]{}*+§$&=?!:;,
						[NewMaxCharsPassword] => 64
						[NewMinCharsPassword] => 3
						[NewAllowedCharsPassword] => 0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz-._@()#/%[]{}*+§$&=?!:;,
						[NewTransportType] => PPPoE
						[NewRouteProtocolRx] => Off
						[NewPPPoEServiceName] => 
						[NewRemoteIPAddress] => 
						[NewPPPoEACName] => srbfra31
						[NewDNSEnabled] => 1
						[NewDNSOverrideAllowed] => 1
	//////////////////////////////////////////////////////////////////////////////*/
	public function GetInfo_connection( ){
	    return $this->processSoapCall(
						"/upnp/control/wanpppconn1",

					    "urn:dslforum-org:service:WANPPPConnection:1",

					    "GetInfo",

					       array(
							 
							)
		);
	}


	/*//////////////////////////////////////////////////////////////////////////////
	--------------------------------------------------------------------------------
	Funktion 	:	 
						 
	Befehl		:	 
	--------------------------------------------------------------------------------
	Soap / upnp-command
	--------------------------------------------------------------------------------
	Parameter:		  
					
	Rückgabewert: 	 Array

	//////////////////////////////////////////////////////////////////////////////*/
	public function GetGenericPortMappingEntry( $index ){
	    return $this->processSoapCall(
						"/upnp/control/wanpppconn1",

					    "urn:dslforum-org:service:WANPPPConnection:1",

					    "GetGenericPortMappingEntry",

					       array(
							new SoapParam($index   ,"NewPortMappingIndex" )
							)
		);
	}


	/*//////////////////////////////////////////////////////////////////////////////
	--------------------------------------------------------------------------------
	Funktion 	:	 
						 
	Befehl		:	 
	--------------------------------------------------------------------------------
	Soap / upnp-command
	--------------------------------------------------------------------------------
	Parameter:		  
					
	Rückgabewert: 	 Array

	//////////////////////////////////////////////////////////////////////////////*/
	public function GetSpecificPortMappingEntry( $index ){
	    return $this->processSoapCall(
						"/upnp/control/wanpppconn1",

					    "urn:dslforum-org:service:WANPPPConnection:1",

					    "GetSpecificPortMappingEntry",

					       array(
							new SoapParam($index   ,"NewRemoteHost" )
							)
		);
	}

	
	/*//////////////////////////////////////////////////////////////////////////////
	--------------------------------------------------------------------------------
	Funktion 	:	 
						 
	Befehl		:	 
	--------------------------------------------------------------------------------
	Soap / upnp-command
	--------------------------------------------------------------------------------
	Parameter:		  
					
	Rückgabewert: 	 92.194.17.120

	//////////////////////////////////////////////////////////////////////////////*/
	public function GetExternalIPAddress( ){
	    return $this->processSoapCall(
						"/upnp/control/wanpppconn1",

					    "urn:dslforum-org:service:WANPPPConnection:1",

					    "GetExternalIPAddress",

					       array(
							 
							)
		);
	}


	/*//////////////////////////////////////////////////////////////////////////////
	--------------------------------------------------------------------------------
	Funktion 	:	 
						 
	Befehl		:	 
	--------------------------------------------------------------------------------
	Soap / upnp-command
	--------------------------------------------------------------------------------
	Parameter:		  
					
	Rückgabewert: 	 Array

	//////////////////////////////////////////////////////////////////////////////*/
	public function GetDNSServers( ){
	    return $this->processSoapCall(
						"/upnp/control/wanpppconn1",

					    "urn:dslforum-org:service:WANPPPConnection:1",

					    "X_GetDNSServers",

					       array(
							 
							)
		);
	}


//  33 ------------------------------------------------------------------------

	/*//////////////////////////////////////////////////////////////////////////////
	--------------------------------------------------------------------------------
	Funktion 	:	 
						 
	Befehl		:	 
	--------------------------------------------------------------------------------
	Soap / upnp-command
	--------------------------------------------------------------------------------
	Parameter:		  
					
	Rückgabewert: 	 Array
				[NewEnable] => 1
				[NewConnectionStatus] => Connecting
				[NewPossibleConnectionTypes] => IP_Routed, IP_Bridged
				[NewConnectionType] => IP_Routed
				[NewName] => mstv
				[NewUptime] => 0
				[NewLastConnectionError] => ERROR_NONE
				[NewRSIPAvailable] => 0
				[NewNATEnabled] => 1
				[NewExternalIPAddress] => 0.0.0.0
				[NewDNSServers] => 0.0.0.0, 0.0.0.0
				[NewMACAddress] => 34:31:C4:B6:72:E8
				[NewConnectionTrigger] => AlwaysOn
				[NewRouteProtocolRx] => Off
				[NewDNSEnabled] => 1
				[NewDNSOverrideAllowed] => 0
	//////////////////////////////////////////////////////////////////////////////*/
	public function Con_GetInfo(){
	    return $this->processSoapCall(
						"/upnp/control/wanipconnection1",

					    "urn:dslforum-org:service:WANIPConnection:1",

					    "GetInfo",

					       array(
							 
							)
		);
	} 





	/*//////////////////////////////////////////////////////////////////////////////
	--------------------------------------------------------------------------------
	Funktion 	:	 
						 
	Befehl		:	 
	--------------------------------------------------------------------------------
	Soap / upnp-command
	--------------------------------------------------------------------------------
	Parameter:		  
					
	Rückgabewert: 	 Array
						[NewConnectionStatus] => Connecting
						[NewLastConnectionError] => ERROR_NONE
						[NewUptime] => 0
	//////////////////////////////////////////////////////////////////////////////*/
	public function IP_GetStatusInfo(){
	    return $this->processSoapCall(
						"/upnp/control/wanipconnection1",

					    "urn:dslforum-org:service:WANIPConnection:1",

					    "GetStatusInfo",

					       array(
							 
							)
		);
	}

	/*//////////////////////////////////////////////////////////////////////////////
	--------------------------------------------------------------------------------
	Funktion 	:	 
						 
	Befehl		:	 
	--------------------------------------------------------------------------------
	Soap / upnp-command
	--------------------------------------------------------------------------------
	Parameter:		  
					
	Rückgabewert: 	 Array
						[NewConnectionType] => IP_Routed
						[NewPossibleConnectionTypes] => IP_Routed, IP_Bridged
	//////////////////////////////////////////////////////////////////////////////*/
	public function GetConnectionTypeInfo(){
	    return $this->processSoapCall(
						"/upnp/control/wanipconnection1",

					    "urn:dslforum-org:service:WANIPConnection:1",

					    "GetConnectionTypeInfo",

					       array(
							 
							)
		);
	}

	/*//////////////////////////////////////////////////////////////////////////////
	--------------------------------------------------------------------------------
	Funktion 	:	 
						 
	Befehl		:	 
	--------------------------------------------------------------------------------
	Soap / upnp-command
	--------------------------------------------------------------------------------
	Parameter:		  
					
	Rückgabewert: 	 Array
						[NewEnable] => 1
						[NewConnectionStatus] => Connecting
						[NewPossibleConnectionTypes] => IP_Routed, IP_Bridged
						[NewConnectionType] => IP_Routed
						[NewName] => mstv
						[NewUptime] => 0
						[NewLastConnectionError] => ERROR_NONE
						[NewRSIPAvailable] => 0
						[NewNATEnabled] => 1
						[NewExternalIPAddress] => 0.0.0.0
						[NewDNSServers] => 0.0.0.0, 0.0.0.0
						[NewMACAddress] => 34:31:C4:B6:72:E8
						[NewConnectionTrigger] => AlwaysOn
						[NewRouteProtocolRx] => Off
						[NewDNSEnabled] => 1
						[NewDNSOverrideAllowed] => 0
	//////////////////////////////////////////////////////////////////////////////*/
	public function IP_GetInfo(){
	    return $this->processSoapCall(
						"/upnp/control/wanipconnection1",

					    "urn:dslforum-org:service:WANIPConnection:1",

					    "GetInfo",

					       array(
							 
							)
		);
	}


	/*//////////////////////////////////////////////////////////////////////////////
	--------------------------------------------------------------------------------
	Funktion 	:	 
						 
	Befehl		:	 
	--------------------------------------------------------------------------------
	Soap / upnp-command
	--------------------------------------------------------------------------------
	Parameter:		  
					
	Rückgabewert: 	 Array
		[NewRemoteHost] => 0.0.0.0
		[NewExternalPort] => 500
		[NewProtocol] => UDP
		[NewInternalPort] => 500
		[NewInternalClient] => 192.168.178.22
		[NewEnabled] => 1
		[NewPortMappingDescription] => VPN-IPSEC2
		[NewLeaseDuration] => 0
	//////////////////////////////////////////////////////////////////////////////*/
	public function Con_GetGenericPortMappingEntry( $index ){
	    return $this->processSoapCall(
						"/upnp/control/wanipconnection1",

					    "urn:dslforum-org:service:WANIPConnection:1",

					    "GetGenericPortMappingEntry",

					       array(
							new SoapParam($index   ,"NewPortMappingIndex" )
							)
		);
	}

	/*//////////////////////////////////////////////////////////////////////////////
	--------------------------------------------------------------------------------
	Funktion 	:	 
						 
	Befehl		:	 
	--------------------------------------------------------------------------------
	Soap / upnp-command
	--------------------------------------------------------------------------------
	Parameter:		  
					
	Rückgabewert: 	 15
	//////////////////////////////////////////////////////////////////////////////*/
	public function Con_GetPortMappingNumberOfEntries(  ){
	    return $this->processSoapCall(
						"/upnp/control/wanipconnection1",

					    "urn:dslforum-org:service:WANIPConnection:1",

					    "GetPortMappingNumberOfEntries",

					       array(
							 
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

	$xmlservices = $xml->xpath("//fb:service" );
	//$xmlservice = $xml->xpath("//fb:service[fb:SCPDURL='/".$SCPD."']");
	/*
	$service['uri'] = (string)$xmlservice[0]->serviceType;
	$service['location'] =$fbroot.(string)$xmlservice[0]->controlURL;
	$service['SCPDURL'] =trim((string)$xmlservice[0]->SCPDURL,"/");
	*/
	return $xmlservices;
}
 
public function ReadActionList($ServiceList)
{

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
		$ip = $this->ReadPropertyString('FBX_IP');
		try{
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
			$antwort = $client->__soapCall($action, $parameter);
			$this->SendDebug('Soap:', "Daten abgrufen: ".$action , 0);
	      	return $antwort;
		}
		catch(Exception $e){
	      	$faultstring = $e->faultstring;
	      	$faultcode   = $e->faultcode;
	      	if(isset($e->detail->UPnPError->errorCode)){
				$errorCode   = $e->detail->UPnPError->errorCode;
				//throw new Exception("Error during Soap Call: ".$faultstring." ".$faultcode." ".$errorCode." (".$this->resolveErrorCode($errorCode).")");
				$this->SendDebug('SoapError:', $faultcode, 0);
			}
			else{
				//throw new Exception("Error during Soap Call: ".$faultstring." ".$faultcode);
				$this->SendDebug('SoapError:', $faultcode , 0);
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
	  private function resolveErrorCode(string $errorCode)

	  {

	    $errorList = array(

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

		);

	    if (isset($errorList[$errorCode])){

	      return $errorList[$errorCode] ;

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
