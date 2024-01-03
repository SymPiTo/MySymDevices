<?php

 #******************************************************************************#
 # Title : MyUPNP Discovery                                                     #
 #                                                                              #
 # Author: PiTo                                                                 #
 #                                                                              #
 # GITHUB: <https://github.com/SymPiTo/MySymDevices/tree/master/MyUPNPDiscovery>#
 #                                                                              #
 # Version: 1.0.2  20240101                                                     #
 #******************************************************************************#
 # _____________________________________________________________________________#
 #    Section: Beschreibung                                                     #
 #    Das Modul dient zum Auffinden von UPNP Devices                            #
 #    Server        			                                                #
 #    Clients                                                                   #
 # _____________________________________________________________________________#
 
 require_once(__DIR__ . "/../libs/MyHelper.php");

 class MyUPNPDiscovery extends IPSModule{
	#Traits aufrufen
	use ProfileHelper;
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

	#--------------------------------------------------------------------------------#
	#       Function: GetConfigurationForm()                                         #
	#       Einträge von Form.json werden ignoriert                                  #
	#       Einträge der Form können überschrieben werden.                           #
	#       Form wird hierdurch dynamisiert.                                         #
	#--------------------------------------------------------------------------------#
	
    public function GetConfigurationForm()
    {
        //SSDP Suchlauf ausführen - Netz nach devices suchen
        $Devices = $this->DiscoverDevices();
        //Daten aus der form.json holen und in array variable speichern
        $Form = json_decode(file_get_contents(__DIR__ . '/form.json'), true);

        //$IPSDevices = $this->GetIPSInstances();

        $Values = [];

        //suche alle UPNP fähigen Geräte im lokalen Netz
        $discoveredDevices = $this->DiscoverDevices();

        foreach ($Devices as $IPAddress => $Device) {
            $AddValue = [
                'IPAddress'  => $IPAddress,
                'type'       => $Device[0],
                'name'       => 'Onkyo/Pioneer AVR Splitter (' . $Device[0] . ')',
                'instanceID' => 0,
            ];
            $InstanceID = array_search($IPAddress, $IPSDevices);
            if ($InstanceID === false) {
                $InstanceID = array_search(strtolower($Device[4]), $IPSDevices);
                if ($InstanceID !== false) {
                    $AddValue['IPAddress'] = $Device[4];
                }
            }
            if ($InstanceID !== false) {
                unset($IPSDevices[$InstanceID]);
                $AddValue['name'] = IPS_GetName($InstanceID);
                $AddValue['instanceID'] = $InstanceID;
            }
            $AddValue['create'] = [
                [
                    'moduleID'      => '{251DAC2C-5B1F-4B1F-B843-B22D518F553E}',
                    'configuration' => new stdClass(),
                ],
                [
                    'moduleID'      => '{EB1697D1-2A88-4A1A-89D9-807D73EEA7C9}',
                    'configuration' => new stdClass(),
                ],
                [
                    'moduleID'      => '{3CFF0FD9-E306-41DB-9B5A-9D06D38576C3}',
                    'configuration' => [
                        'Host' => $AddValue['IPAddress'],
                        'Port' => (int) $Device[1],
                        'Open' => true,
                    ],
                ],
            ];
            $Values[] = $AddValue;
        }

        foreach ($IPSDevices as $InstanceID => $IPAddress) {
            $Values[] = [
                'IPAddress'  => $IPAddress,
                'type'       => '',
                'name'       => IPS_GetName($InstanceID),
                'instanceID' => $InstanceID,
            ];
        }
        $Form['actions'][0]['values'] = $Values;

        $this->SendDebug('FORM', json_encode($Form), 0);
        $this->SendDebug('FORM', json_last_error_msg(), 0);

        return json_encode($Form);
    }

    private function GetIPSInstances(): array
    {
        $InstanceIDList = IPS_GetInstanceListByModuleID('{251DAC2C-5B1F-4B1F-B843-B22D518F553E}');
        $Devices = [];
        foreach ($InstanceIDList as $InstanceID) {
            $Splitter = IPS_GetInstance($InstanceID)['ConnectionID'];
            if ($Splitter > 0) {
                $IO = IPS_GetInstance($Splitter)['ConnectionID'];
                if ($IO > 0) {
                    $parentGUID = IPS_GetInstance($IO)['ModuleInfo']['ModuleID'];
                    if ($parentGUID == '{3CFF0FD9-E306-41DB-9B5A-9D06D38576C3}') {
                        $Devices[$InstanceID] = strtolower(IPS_GetProperty($IO, 'Host'));
                    }
                }
            }
        }

        return $Devices;
    }

    private function DiscoverDevices(): array
    {
        /*
        $this->LogMessage($this->Translate('Background discovery of Onkyo/Pioneer AV-Receiver'), KL_NOTIFY);
        $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        if (!$socket) {
            return [];
        }
        socket_set_option($socket, SOL_SOCKET, SO_BROADCAST, 1);
        socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);
        socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, ['sec' => 1, 'usec' => 100000]);
        socket_bind($socket, '0.0.0.0', 0);
        $message = hex2bin('49534350000000100000000b01000000217845434e5153544e0d0a');
        if (@socket_sendto($socket, $message, strlen($message), 0, '255.255.255.255', 60128) === false) {
            return [];
        }
        $this->SendDebug('Search', $message, 0);
        usleep(100000);
        $i = 50;
        $buf = '';
        $IPAddress = '';
        $Port = 0;
        $DeviceData = [];
        while ($i) {
            $ret = @socket_recvfrom($socket, $buf, 2048, 0, $IPAddress, $Port);
            if ($ret === false) {
                break;
            }
            if ($ret === 0) {
                $i--;
                continue;
            }
            $start = strpos($buf, '!1ECN');
            if ($start === false) {
                continue;
            }
            $this->SendDebug('Receive', $buf, 0);
            $end = strpos($buf, "\x19", $start);
            $DeviceData[$IPAddress] = explode('/', substr($buf, $start + 5, $end - $start - 5));
            $DeviceData[$IPAddress][] = gethostbyaddr($IPAddress);
        }
        socket_close($socket);
        $this->SendDebug('Discover', $DeviceData, 0);
        */
        //ID der SDDP Kern-Instanz
        //Suche nach allen UPNP fägigen Geräte
        $result = YC_SearchDevices(59291, "ssdp:all");
        //wichtige Daten aussortieren und doppelte entfernen
        
        
        return $DeviceData;
    }

    #-----------------------------------------------------------------------------
	# Function: removeDuplicatesByIP                                                      
	#...............................................................................
	# Beschreibung :  Removes duplicate items from an array based on the 'IP' key.                          
	#...............................................................................
	# Parameters:                                                                   
    # @param array $array An array of items, where each item is an associative array with an 'IP' key.
    # @return array An array containing unique items from the input array based on the 'IP' key.                                                                                                                                 
	#...............................................................................
	# Returns : array                                                                  
	#------------------------------------------------------------------------------  
	private Function removeDuplicatesByIP($array):array {
        $uniqueIPs = array();
        $result = array();
    
        foreach ($array as $item) {
            $ip = $item['IP'];
    
            if (!in_array($ip, $uniqueIPs)) {
                // If the IP is not already in the uniqueIPs array, add it and add the item to the result
                $uniqueIPs[] = $ip;
                $result[] = $item;
            }
            // If the IP is already in the uniqueIPs array, skip adding the item to the result (remove duplicate)
        }
        return $result;
	}

    #-----------------------------------------------------------------------------
	# Function: getDescription                                                      
	#...............................................................................
	# Beschreibung :  * Retrieves information parsed from an XML file retrieved from a given URL.                
	#...............................................................................
	# Parameters:                                                                   
    # @param string $xmlUrl The URL of the XML file to fetch and parse.
    # @return array An array containing the parsed information from the XML file, 
    #   including URLBase, friendlyName, deviceType, manufacturer, modelName, modelNumber, icon, 
    #   and an array of service information.                                                                                                                              
	#...............................................................................
	# Returns : array                                                                  
	#------------------------------------------------------------------------------  
	private Function GetDescription($xmlUrl) : array {
        // Initialize cURL session
        $ch = curl_init($xmlUrl);

        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        // Execute cURL session and get the XML content
        $xmlContent = curl_exec($ch);
    
        // Check for cURL errors
        if (curl_errno($ch)) {
            die('Error fetching XML content: ' . curl_error($ch));
        }

        // Close cURL session
        curl_close($ch);

        // Check if the XML content is not empty
        if (empty($xmlContent)) {
            die('Error fetching XML content');
        }

        // Parse the XML content using SimpleXML
        $xmlObject = simplexml_load_string($xmlContent, 'SimpleXMLElement', LIBXML_NOWARNING ); 
        $device = []; 

        if ($xmlObject) {
            // Access other elements in the default namespace
            $device['URLBase'] = (string)$xmlObject->URLBase;
            $device['friendlyName'] = (string)$xmlObject->device->friendlyName;
            $str = (string)$xmlObject->device->deviceType;
            preg_match('/device:([A-Za-z0-9_-]+):1/',$str, $type);
            $device['deviceType'] = $type[1];
            $device['manufacturer'] = (string)$xmlObject->device->manufacturer;
            $device['modelName'] = (string)$xmlObject->device->modelName;
            $device['modelNumber'] = (string)$xmlObject->device->modelNumber;
            $device['icon'] = (string)$xmlObject->device->iconList->icon->url;

            if (isset($xmlObject->device->serviceList->service)) {
                // Überprüfe, ob der Schlüssel [service] vorhanden ist
                foreach ($xmlObject->device->serviceList->service as $service) {
                    // Iteriere durch alle [service]-Einträge
                    $serviceData = [
                        'Service Type' => (string) $service->serviceType,
                        'Service ID' => (string) $service->serviceId,
                        'SCPDURL' => (string) $service->SCPDURL,
                        'Control URL' => (string) $service->controlURL,
                        'Event Sub URL' => (string) $service->eventSubURL,
                    ];

                    $device['service'][] = $serviceData;
	            }
            }
        }
        return $device;
    }
 }
