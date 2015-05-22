<?php
/**
 * Created by PhpStorm.
 * User: Christoph
 * Date: 14.05.2015
 * Time: 04:02
 */

class FileRepository{

    /**
     * @param UserFile $file
     * @return bool
     */
    public function InsertFile(UserFile $file){
        global $db;
        $stmt = $db->prepare("INSERT INTO [dbo].[UserFile]
          ([Name],
          [FileLink],
          [Description],
          [IsPrivate],
          [UserId])
     VALUES
           (:Name,
            :FileLink,
            :Description,
            :IsPrivate,
            :UserId");
        $stmt->bindParam(":Name", $file->Name);
        $stmt->bindParam(":FileLink", $file->FileLink);
        $stmt->bindParam(":Description", $file->Description);
        $stmt->bindParam(":IsPrivate", $file->IsPrivate);
        $stmt->bindParam(":UserId", $file->UserId);

        $stmt->execute();

        if ($stmt->rowCount() == 1)
        {
            return $db->lastInsertId();
        }

        return false;
    }

    public function GetPublicFiles()
    {
        global $db;
        $isprivate = 0;

        //TODO: Eigene Files alle anzeigen + USERNAME

        $stmt = $db->prepare('Select * from [UserFile] where IsPrivate = :ispriv');
        $stmt->bindParam(':ispriv', $isprivate);

        $result = $stmt->execute();

        if ($stmt->columnCount() >= 1) {
            return $result;
        }
    }

}
