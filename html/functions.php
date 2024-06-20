<?php

function initiateMySQL(){
    //new PDO(description,user,password);
    $dbdescr="mysql:host=localhost; dbname=sysMonitorDB; port=3306";
    $cnx= new PDO($dbdescr, "testbedControl" , "raspberrypi" );

    return $cnx;
}

function getID($deviceMac){
    // WE make the connection to the database
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
        print("The device has not been introduced in the database so we cannot identify it.");
	return 0;
    }

}

function floatToDatetime($floatValue) {
    
        $dateTime=date('Y-m-d H:i:s', $floatValue);
	return $dateTime;
}
