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
    
        $entera=floor($floatValue);
        $decimal=$floatValue - $entera;

        $timestamp=$entera;
        $date_hour=date('Y/m/d H:i:s',$timestamp);
        $seconds_with_decimal=intval($decimal * 60);

        $date_hour_with_decimal=$date_hour . '.' . str_pad($seconds_with_decimal,2,'0', STR_PAD_LEFT);
          
        return $date_hour_with_decimal;
} 

?>
