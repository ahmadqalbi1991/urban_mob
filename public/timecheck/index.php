<?php
 
 function getDubaiTime() {
    $dubaiTimeZone = new DateTimeZone('Asia/Dubai');
    $currentTime = new DateTime('now', $dubaiTimeZone);
    
    return $currentTime->format('Y-m-d H:i:s');
}

echo getDubaiTime();

?>
