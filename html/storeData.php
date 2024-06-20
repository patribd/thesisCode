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
        $deviceN=$p2['id'];
    }else{
        print("The device has not been introduced in the database so we cannot identify it.\n");
	$deviceN=0;
    }

	return $deviceN;
}

function floatToDatetime($floatValue) {
    
        $dateTime=date('Y-m-d H:i:s', $floatValue);
        return $dateTime;
}
 

$db=initiateMySQL();

$data[1]=str_replace(" ",'',$data[1]);
$data[2]=str_replace(" ",'',$data[2]);
$data[3]=str_replace(" ",'',$data[3]);
$data[4]=str_replace(" ",'',$data[4]);

switch ($data[0]){

    case 'tx':
        $package = $db -> prepare("INSERT INTO txPackages (deviceSourceID, deviceDestinationID, packageID, floatTimestamp,dateTimestamp) VALUES (:deviceSourceID, :deviceDestinationID, :packageID, :floatTimestamp, :dateTimestamp)");
        unset($content);
        $content['deviceSourceID'] = getID($data[1]);
        $content['deviceDestinationID'] = getID($data[2]);
        $content['packageID'] = $data[3];
        $content['floatTimestamp'] = $data[4]+7200;
	$content['dateTimestamp']=floatToDatetime($data[4]+7200);
	        

        $p1 = $package -> execute($content);
        $p2 = $package -> fetch();

        if ($p2 != null){
            print("There has been an error sending the data to the 'txPackages' table.\n");
        }else{
	   print("The package data has been stored in the system's database.\n");
	}
        
        break;

    case 'rx':
        $package = $db -> prepare("INSERT INTO rxPackages (deviceSourceID, deviceDestinationID, packageID, floatTimestamp, dateTimestamp) VALUES (:deviceSourceID, :deviceDestinationID, :packageID, :floatTimestamp, :dateTimestamp)");
        unset($content);
        $content['deviceSourceID'] = getID($data[1]);
        $content['deviceDestinationID'] = getID($data[2]);
        $content['packageID'] = $data[3];
        $content['floatTimestamp'] = $data[4]+7200;
        $content['dateTimestamp']=floatToDatetime($data[4]+7200);

        $p1 = $package -> execute($content);
        $p2 = $package -> fetch();

        if ($p2 != null){
            print("There has been an error sending the data to the 'rxPackages' table.\n");
        }else{ 
           print("The package data has been stored in the system's database.\n");
        }
        
        break;

    case 'R':
        $package = $db -> prepare ("INSERT INTO routings (deviceID, parentID,floatTimestamp, dateTimestamp) VALUES (:deviceID, :parentID,:floatTimestamp, :dateTimestamp)");
        unset($content);
        $content['deviceID'] = getID($data[1]);
        $content['parentID'] = getID($data[2]);
	$content['floatTimestamp'] = $data[3]+7200;
        $content['dateTimestamp']=floatToDatetime($data[3]+7200);

        $p1 = $package -> execute($content);
        $p2 = $package -> fetch();

        if ($p2 != null){
            print("There has been an error sending the data to the 'routings' table.\n");
        }else{ 
           print("The package data has been stored in the system's database.\n");
        }

        break;
    
    case 'I':
        $package = $db -> prepare ("INSERT INTO currents (deviceSourceID, value, floatTimestamp, dateTimestamp, measurement) VALUES (:deviceSourceID, :value, :floatTimestamp, :dateTimestamp, :measurement)");
        unset($content);
        $content['deviceSourceID'] = getID($data[1]);
        $content['value'] = $data[2];
        $content['floatTimestamp'] = $data[3]+7200;
        $content['dateTimestamp']=floatToDatetime($data[3]+7200);
        $content['measurement'] = $data[4];

        $p1 = $package -> execute($content);
        $p2 = $package -> fetch();

        if ($p2 != null){
            print("There has been an error sending the data to the 'currents' table.\n");
        }else{ 
           print("The package data has been stored in the system's database.\n");
        }
	
        break;
}

?>
