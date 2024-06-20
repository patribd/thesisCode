<?php

include('functions.php');
$db = initiateMySQL();


$table1 = "devices";
$table2 = "txPackages";
$table3 = "rxPackages";
$table4 = "currents";
$table5 = "routings";

$sql1 = $db -> prepare ("SHOW TABLES LIKE '$table1'");
$p1 = $sql1 -> execute();

$sql2 = $db -> prepare ("SHOW TABLES LIKE '$table2'");
$p2 = $sql2 -> execute();

$sql3 = $db -> prepare ("SHOW TABLES LIKE '$table3'");
$p3 = $sql3 -> execute();

$sql4 = $db -> prepare ("SHOW TABLES LIKE '$table4'");
$p4 = $sql4 -> execute();

$sql5 = $db -> prepare ("SHOW TABLES LIKE '$table5'");
$p5 = $sql5 -> execute();

if ($sql1->rowCount() == 0){
    $insert = $db -> prepare("CREATE TABLE devices ( id INT AUTO_INCREMENT PRIMARY KEY, deviceMac VARCHAR(255), deviceType VARCHAR(50), controlDevice VARCHAR(50) DEFAULT NULL)");
    if (!$insert -> execute()){
        print_r("The table called 'devices' cannot be created. Please call a supervisor.\n");
    }
}

if ($sql2->rowCount() == 0){
    $insert = $db -> prepare("CREATE TABLE txPackages (deviceSourceID INT, deviceDestinationID INT, packageID INT, floatTimestamp DECIMAL(19,9), dateTimestamp DATETIME)");
    if (!$insert -> execute()){
        print_r("The table called 'txPackages' cannot be created. Please call a supervisor.\n");
    }
}

if ($sql3->rowCount() == 0){
    $insert = $db -> prepare("CREATE TABLE rxPackages (deviceSourceID INT, deviceDestinationID INT, packageID INT, floatTimestamp DECIMAL(19,9), dateTimestamp DATETIME)");
    if (!$insert -> execute()){
        print_r("The table called 'rxPackages' cannot be created. Please call a supervisor.\n");
    }
}

if ($sql4->rowCount() == 0){
    $insert = $db -> prepare ("CREATE TABLE currents (deviceSourceID INT, value DECIMAL(23,18), floatTimestamp DECIMAL(19,9), dateTimestamp DATETIME, measurement INT)");
    if (!$insert -> execute()){
        print_r("The table called 'currents' cannot be created. Please call a supervisor.\n");
    }
}

if ($sql5->rowCount() == 0){
    $insert = $db -> prepare("CREATE TABLE routings (deviceID INT, parentID INT, floatTimestamp DECIMAL(19,9), dateTimestamp DATETIME)");
    if (!$insert -> execute()){
        print_r("The table called 'routings' cannot be created. Please call a supervisor.\n");
    }
}


?>
