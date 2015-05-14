<?php
    require_once ("/Includes/config.php");

    /* Connect*/
    try
    {
        $db = new PDO( "sqlsrv:server=" . DB_HOST . ";Database=" . DB_NAME,  DB_USER, DB_PASSWORD);
        $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    }
    catch(Exception $e)
    {
        die( print_r( $e->getMessage() ) );
    }
?>