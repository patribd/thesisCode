<?php

include("functions.php");
$db = initiateMySQL();

// Determine which devices should be shown
$offset = 0;
if (isset($_GET['offset'])){
    $offset = max(intval($_GET['offset']),0);
}

if ($offset == 0){
    header("Refresh: 3");
}

$header = "";
$i = 0;
$printCode = file("skin/currents.html");

while (!stristr($printCode[$i], '##init_row##')){
    $header = $header.$printCode[$i];
    $i = $i +1;
}
while (!stristr($printCode[$i], '##end_row##')){
    $i = $i + 1;
}

$code = file_get_contents("skin/currents.html");
$init_row = strpos($code, "<!-- ##init_row## -->") + strlen("<!-- ##init_row## -->");
$end_row = strpos($code, "<!-- ##end_row## -->") + strlen("<!-- ##end_row## -->");
$content = substr($code, $init_row, $end_row - $init_row);

$devices = $db -> prepare ("SELECT * FROM currents ORDER BY floatTimestamp DESC LIMIT 10 OFFSET " . $offset);
$devicesInformation = $devices -> execute();

$content_devices[] = "";

while ($row = $devices -> fetch()){
    $content_device = $content;

    $content_device = str_replace("##deviceSourceID##", $row['deviceSourceID'], $content_device);
    $resitor=0.5;
    $amplificationValue=99.04;
    $content_device = str_replace("##value##", $row['value']/($amplificationValue*$resitor), $content_device);
    $content_device = str_replace("##measurement##", $row['measurement'], $content_device);
    $content_device = str_replace("##floatTimestamp##", $row['floatTimestamp'], $content_device);
    $content_device = str_replace("##dateTimestamp##", $row['dateTimestamp'], $content_device);

    $content_devices[] = $content_device;
}

$content_general = implode('', $content_devices);

echo $header;
echo $content_general;

// Code to check what arrows we have to print
$i = 0;
$final = "";

$total=$db -> prepare ("SELECT COUNT(*) FROM currents");
$p1=$total->execute();
$totalElements = $total -> fetch();

if ($offset==0){
    
    if ($totalElements["COUNT(*)"] > $offset +10){
        while (!stristr($printCode[$i], '##end_row##')){
            $i = $i + 1;
        }
        $i = $i + 1;
        while (!stristr($printCode[$i], '##init_arrow_left##')){
            $final=$final.$printCode[$i];
            $i = $i + 1;
        }
        $i = $i + 1;
        while (!stristr($printCode[$i], '##init_arrow_right##')){
            $i = $i + 1;
        }
        $i = $i + 1;
        while (isset($printCode[$i])){
            $final=$final.$printCode[$i];
            $i=$i+1;
        }
        
        $final = str_replace('##offsetRight##', $offset + 10, $final);
        
        echo $final;
    }else{
        while (!stristr($printCode[$i], '##end_arrow_right##')){
            $i = $i + 1;
        }
        $i = $i + 1;
        while (isset($printCode[$i])){
            $final=$final.$printCode[$i];
            $i=$i+1;
        }
        
        echo $final;

    }
}else{

    if ($totalElements["COUNT(*)"] > $offset+10){
        while (!stristr($printCode[$i], '##end_row##')){
            $i = $i + 1;
        }
        $i = $i + 1;
        while (isset($printCode[$i])){
            $final=$final.$printCode[$i];
            $i=$i+1;
        }
        
        $final = str_replace('##offsetRight##', $offset + 10, $final);
        $final = str_replace('##offsetLeft##', max($offset - 10,0), $final);
        
        echo $final;
    }else{
        while (!stristr($printCode[$i], '##end_row##')){
            $i = $i + 1;
        }
        $i = $i + 1;
        while (!stristr($printCode[$i], '##end_arrow_left##')){
            $final=$final.$printCode[$i];
            $i=$i+1;
        }
        $i = $i + 1;
        
        $final = str_replace('##offsetLeft##', max($offset - 10,0), $final);
        
        echo $final;
    }

}

?>
