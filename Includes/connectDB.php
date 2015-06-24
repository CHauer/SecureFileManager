<?php

function CreateDatabaseAccess()
{
     $DB_NAME = 'SecureFileManagerDB';
     $DB_USER = 'FHProjectsAdmin@vlpd8topfp';
     $DB_PASSWORD = 'qwerASDF12';
     $DB_HOST = 'tcp:vlpd8topfp.database.windows.net,1433';

    /* Connect*/
    try
    {
        $db = new PDO("sqlsrv:server=" . $DB_HOST . ";Database=" . $DB_NAME, $DB_USER, $DB_PASSWORD);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch (Exception $e)
    {
        die(print_r($e->getMessage()));
    }

    return $db;
}

?>