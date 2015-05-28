<?php
/**
 * Created by PhpStorm.
 * User: Christoph
 * Date: 14.05.2015
 * Time: 04:02
 */

class LogEntryRepository{

    /**
     * @param LogEntry $logEntry
     * @return bool
     */
    public function InsertLogEntry(LogEntry $logEntry){
        global $db;

        $stmt = $db->prepare("INSERT INTO [dbo].[LogEntry] ([Message],[Typ]) VALUES (:message, :typ)");
        $stmt->bindParam(":message", $logEntry->Message);
        $stmt->bindParam(":typ", $logEntry->Typ);

        $stmt->execute();

        if ($stmt->rowCount() == 1)
        {
            return true;
        }

        return false;
    }

    /**
     * @param $count
     * @return array
     */
    public function GetLogEntries($count = 1000){
        $entries = Array();
        global $db;

        $stmt = $db->prepare("SELECT TOP " . $count ." [LogEntryId],
                                convert(varchar, [Created], 104) as [Created],
                                [Message],
                                [Typ]
                              FROM [dbo].[LogEntry]
                              ORDER BY [Created] DESC");

        $stmt->execute();
        $results = $stmt->fetchAll();

        $insertIndex = 0;

        foreach($results as $resultRow)
        {
            $tempEntry = new LogEntry();
            $tempEntry->LogEntryId = $resultRow['LogEntryId'];
            $tempEntry->Created = ParseDate($resultRow["Created"]);
            $tempEntry->Message = $resultRow['Message'];
            $tempEntry->Typ = $resultRow['Typ'];

            $entries[$insertIndex++] =$tempEntry;
        }

        return $entries;
    }

}
