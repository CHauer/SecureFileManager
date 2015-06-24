<?php
/**
 * Created by PhpStorm.
 * User: Christoph
 * Date: 20.05.2015
 * Time: 22:27
 */

/* LogEntry Typs */
define('LOGGER_DEBUG', 0);
define('LOGGER_INFO', 1);
define('LOGGER_WARNING', 2);
define('LOGGER_ERROR', 3);

/*
 * Logging Events
 * ---------------
 * Kontoerstellung [x]
 * Kontoänderungen [x]
 * Konto-Aktivierung [x]
 * Konto-Deaktivierung [x]
 * Login [x]
 * Logout [x]
 * Forum
 * Themenerstellung
 * Löschen eines Themas
 * Verfassen eines Beitrags
 * Löschen eines Beitrags
 * Dateiverwaltung
 * Download
 * Upload
 * Verfassen eines Kommentar
 * Löschen eines Kommentars
*/

class Logger
{
    private $logRepository;
    private $db;

    public function __construct($db)
    {
        $this->logRepository = new LogEntryRepository($db);
        $this->db = $db;
    }

    public function LogDebugMessage($message)
    {
        $tempEntry = new LogEntry();
        $tempEntry->Message = $message;
        $tempEntry->Typ = LOGGER_DEBUG;

        return $this->logRepository->InsertLogEntry($tempEntry);
    }

    public function LogMessage($message, $typ)
    {
        $tempEntry = new LogEntry();
        $tempEntry->Message = $message;
        $tempEntry->Typ = $typ;

        return $this->logRepository->InsertLogEntry($tempEntry);
    }

    public function LogMessageEntry (LogEntry $entry)
    {
        return $this->logRepository->InsertLogEntry($entry);
    }

    /* Note: Paging should be implemented in real world scenarios! */
    public function GetAllLogMessages($count)
    {
        if(!isset($count) || !is_int($count))
        {
            $count = 1000;
        }

        return $this->logRepository->GetLogEntries($count);
    }
}