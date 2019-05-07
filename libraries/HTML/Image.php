<?php

//Receives a string with a html class, a row, and an alt as parameters. Creates an <img>
function showImage($class, $image, $alt){
    
    echo ' <img class =" ' . $class .'" src="data:image/jpeg;base64,'.base64_encode( $image ).'" alt = "'. $alt .'" /> ';
    
}



?>