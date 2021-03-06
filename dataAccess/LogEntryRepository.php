<?php
/**
 * Created by PhpStorm.
 * User: Christoph
 * Date: 14.05.2015
 * Time: 04:02
 */

class LogEntryRepository extends  BaseRepository
{
    public function __construct($db)
    {
        parent::__construct($db);
    }

    /**
     * @param LogEntry $logEntry
     * @return bool
     */
    public function InsertLogEntry(LogEntry $logEntry){


        $stmt = $this->db->prepare("INSERT INTO [dbo].[LogEntry] ([Message],[Typ]) VALUES (:message, :typ)");
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


        $stmt = $this->db->prepare("SELECT TOP " . $count ." [LogEntryId],
                                convert(nvarchar, [Created], 104) + ' ' + convert(nvarchar, [Created], 114)  as [CreatedFormat],
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
            $tempEntry->Created = $resultRow["CreatedFormat"];
            $tempEntry->Message = $resultRow['Message'];
            $tempEntry->Typ = $resultRow['Typ'];

            $entries[$insertIndex++] =$tempEntry;
        }

        return $entries;
    }

}
