<?php
include("../../password_protect.php"); 

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function get_default_language(){
    return "en";
}

function get_db_config(){
    /*$host = "localhost";
    $user = "bitnami";
    $pass = "eae8924aed";
    $db = "bitnami_drupal6";
    */
    
    /*$db_host = "localhost";
    $db_user = "CEdrupal_6_fl";
    $db_pass = "3xpl0r3!";
    $db_db_name = "CEdrupal_6_flatbook";
    */
    
    $host = "localhost";
    $user = "root";
    $pass = "jnvpmfcmmdp7485";
    $db = "CEdrupal_6_flatbook";

    return array("host"=>$host, "user"=>$user, "pass"=> $pass, "db"=>$db);
    
}

?>
