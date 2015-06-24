<?php

function CreateDatabaseAccess()
{
     $dbName = 'SecureFileManagerDB';
     $dbUser = 'FHProjectsAdmin@vlpd8topfp';
     $dbPassword = 'qwerASDF12';
     $dbHost = 'tcp:vlpd8topfp.database.windows.net,1433';

    /* Connect*/
    try
    {
        $db = new PDO("sqlsrv:server=" . $dbHost . ";Database=" . $dbName, $dbUser, $dbPassword);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch (Exception $e)
    {
        die(print_r($e->getMessage()));
    }

    return $db;
}

?>