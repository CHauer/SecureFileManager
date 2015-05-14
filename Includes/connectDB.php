<?php
    require_once ("/Includes/config.php");

    /* Connect*/
    try
    {
        $conn = new PDO( "sqlsrv:server=" . DB_HOST . ";Database=" . DB_NAME,  DB_USER, DB_PASSWORD);
        $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    }
    catch(Exception $e)
    {
        die( print_r( $e->getMessage() ) );
    }

    // Create database if needed
    setupDatabase();
?>