<?php
// move password_protect.php to a location outside your web directory,
//  and set the path here : 
include("../../password_protect.php"); 
// remember to set passwords in the password_protect.php file. (ctrl+F "EDIT PASSWORDS HERE")

function get_default_language(){
    return "en"; // can be en, ar, fr, es, ..
}

function get_db_config(){
    
    $host = "yourhostname"; // often localhost
    $user = "yourusername";
    $pass = "yourrootpassword";
    $db = "yourdatabasename";

    return array("host"=>$host, "user"=>$user, "pass"=> $pass, "db"=>$db);
    
}

?>
