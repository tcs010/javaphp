<?php
if (!defined("TCS_APP")){
    exit( "Access Denied" );
}
//require_once("./FileException.php");
//require_once("./SQLException.php"); 
require_once("./SecurityException.php"); 
  
try {
    //$o = new FileException(FileException::THROW_NO_FILE);
    throw new SecurityException(SecurityException::THROW_NO_PERMISSION);
} catch (Exception $e) {
    showError($e); 
}


?>
