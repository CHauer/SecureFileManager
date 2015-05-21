<?php
/**
 * Created by PhpStorm.
 * User: Christoph
 * Date: 20.05.2015
 * Time: 22:27
 */

/* LogEntry Typs */
define('LOGGER_DEBUG', 1);
define('LOGGER_INFO', 1);
define('LOGGER_WARNING', 1);
define('LOGGER_ERROR', 1);

/*
 * Logging Events
 * ---------------
 * Kontoerstellung
 * Kontoänderungen
 * Konto-Aktivierung
 * Konto-Deaktivierung
 * Login
 * Logout
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

    public function __construct()
    {
        $this->logRepository = new LogEntryRepository();
    }

    public function LogDebugMessage($message)
    {
        $tempEntry = new LogEntry();
        $tempEntry->Message = $message;
        $typ = LOGGER_DEBUG;

        return $this->logRepository->InsertLogEntry($tempEntry);
    }

    public function LogMessage($message, $typ)
    {
        $tempEntry = new LogEntry();
        $tempEntry->Message = $message;
        $typ = $typ;

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