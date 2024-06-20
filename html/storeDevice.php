<?php

function initiateMySQL(){
    //new PDO(description,user,password);
    $dbdescr="mysql:host=localhost; dbname=sysMonitorDB; port=3306";
    $cnx= new PDO($dbdescr, "testbedControl" , "raspberrypi" );

    return $cnx;
}

function getID($deviceMac){
    // We make the connection to the database
    $db=initiateMySQL();

    $device = $db -> prepare("SELECT * FROM devices WHERE deviceMac=:deviceMac");
    unset($content);
    $content['deviceMac'] = $deviceMac;

    $p1 = $device -> execute($content);
    $p2 = $device -> fetch();

    if ($p2!=null){
        return $p2['id'];
    }else{
        // The device has not been introduced to the database
        print("The device has not been introduced in the database so we cannot identify it.\n");
    	return 0;
	}

}

$db = initiateMySQL(); 
$odroidMac=$data[count($data)-1];
array_pop($data);


$deviceID = $db -> prepare ("SELECT id FROM devices WHERE deviceMac =:deviceMac");
unset($content);
$content['deviceMac'] = $odroidMac;

$p1 = $deviceID -> execute($content);
$p2 = $deviceID -> fetch();
if ($p2==null){
    $deviceID = $db -> prepare ("INSERT INTO devices (deviceMac, deviceType) values (:deviceMac, :deviceType)");
    unset($content);
    $content['deviceMac'] = $odroidMac;
    $content['deviceType'] = "Odroid M1S";

    $p1 = $deviceID -> execute($content);
    $p2 = $deviceID -> fetch();

    if ($p2 != null){
        // There has been some problem while introducing the device in the database
        print("The Odroid M1S cannot be correctly registered. Call a supervisor.\n");
    }else{
        print("The Odroid M1S has been correctly registered.\n");
        foreach ($data as $device){
            $odroid = $db -> prepare ("SELECT id FROM devices WHERE deviceMac =:deviceMac AND deviceType = :deviceType AND controlDevice=:controlDevice");
            unset($content);
            $content['deviceMac'] = $device;
            $content['deviceType'] = "nRF Dongle";
            $content['controlDevice'] = getID($odroidMac);
            $p1 = $odroid -> execute($content);
            $p2 = $odroid -> fetch();
            if ($p2==null){
                $deviceID = $db -> prepare ("INSERT INTO devices (deviceMac, deviceType, controlDevice) values (:deviceMac, :deviceType, :controlDevice)");
                unset($content);
                $content['deviceMac'] = $device;
                $content['deviceType'] = "nRF Dongle";
                $content['controlDevice'] = getID($odroidMac);
            
                $p1 = $deviceID -> execute($content);
                $p2 = $deviceID -> fetch();
            
                if ($p2 != null){
                    print("The nRF Dongle whose MAC address: ".$device." cannot be correctly registered. Call a supervisor.\n");
                }else{
                    print("The nRF Dongle whose MAC address is : ".$device." has been correctly registered.\n");
                }
            }else{
                print("The nRF Dongle whose MAC address is : ".$device." was already registered with control Device the Odroid : ".$odroidMac.".\n");
            }
        }
    }
}else{
    print("The Odroid M1S was already registered in the database.\n");
    foreach ($data as $device){
        $odroid = $db -> prepare ("SELECT id FROM devices WHERE deviceMac =:deviceMac AND deviceType = :deviceType AND controlDevice=:controlDevice");
        unset($content);
        $content['deviceMac'] = $device;
        $content['deviceType'] = "nRF Dongle";
        $content['controlDevice'] = getID($odroidMac);
        $p1 = $odroid -> execute($content);
        $p2 = $odroid -> fetch();
        if ($p2==null){
            $deviceID = $db -> prepare ("INSERT INTO devices (deviceMac, deviceType, controlDevice) values (:deviceMac, :deviceType, :controlDevice)");
            unset($content);
            $content['deviceMac'] = $device;
            $content['deviceType'] = "nRF Dongle";
            $content['controlDevice'] = getID($odroidMac);
        
            $p1 = $deviceID -> execute($content);
            $p2 = $deviceID -> fetch();
        
            if ($p2 != null){
                print("The nRF Dongle whose MAC address: ".$device." cannot be correctly registered. Call a supervisor.\n");
            }else{
                print("The nRF Dongle whose MAC address is : ".$device." has been correctly registered.\n");
            }
        }else{
            print("The nRF Dongle whose MAC address is : ".$device." was already registered with control Device the Odroid : ".$odroidMac.".\n");
        }
    }
}

?>
