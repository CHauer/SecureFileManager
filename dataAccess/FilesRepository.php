<?php
/**
 * Created by PhpStorm.
 * User: Christoph
 * Date: 14.05.2015
 * Time: 04:02
 */

class FileRepository
{

    /**
     * @param UserFile $file
     * @return bool
     */
    public function InsertFile(UserFile $file)
    {
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
            :UserId)");
        $stmt->bindParam(":Name", $file->Name);
        $stmt->bindParam(":FileLink", $file->FileLink);
        $stmt->bindParam(":Description", $file->Description);
        $stmt->bindParam(":IsPrivate", $file->IsPrivate);
        $stmt->bindParam(":UserId", $file->UserId);

        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            return $db->lastInsertId();
        }

        return false;
    }

    public function GetFile($fileid)
    {
        global $db;

        $stmt = $db->prepare('select top 1
            [Description]
           ,[Name]
           from [dbo].[UserFile]
           where UserFileId=:fileid');

        $stmt->bindParam(":fileid", $fileid);
        $stmt->execute();

        $result = $stmt->fetch();

        if ($result == false)
        {
            throw new InvalidArgumentException("The given fileid does not exist!");
        }

        $file = new UserFile();

        $file->UserFileId = $fileid;
        $file->Description = $result["Description"];
        $file->Name = $result["Name"];

        return $file;
    }

    public function DeleteFile($fileid)
    {
        global $db;

        $stmt = $db->prepare('delete from [Comment] where UserFile_UserFileId = :fileid');
        $stmt->bindParam(':fileid', $fileid);
        $stmt->execute();

        $stmt = $db->prepare('Delete From [UserFile] where UserFileId = :fileid');
        $stmt->bindParam(':fileid', $fileid);
        $stmt->execute();
        if ($stmt->rowCount() == 1) {
            return true;
        }
        return false;
    }

    public function GetPublicAndOwnFiles($user, $file, $order = 'Uploaded')
    {
        global $db;
        $isprivate = 0;

        $stmt = $db->prepare('Select [UserFile].*, Username, PictureLink, (Select count(Commentid) From Comment where UserFile_UserFileId = [Userfile].UserFileId) as CommentCount
                              from [UserFile] left join [User] on [UserFile].UserId = [User].UserId
                              where (IsPrivate = :ispriv or [UserFile].UserId = :id) and [User].Username LIKE :user
                              and Name LIKE :file order by ' . $order . ' DESC, Name');

        $user = '%' . $user . '%';
        $file = '%' . $file . '%';

        $stmt->bindParam(':ispriv', $isprivate);
        $stmt->bindParam(':id', $_SESSION["userid"]);
        $stmt->bindParam(':user', $user, PDO::PARAM_STR);
        $stmt->bindParam(':file', $file, PDO::PARAM_STR);

        $stmt->execute();

        $results = $stmt->fetchAll();

        for ($i = 0; $i < count($results); ++$i) {
            $results[$i]['Uploaded'] = date("d.m.Y H:i", strtotime($results[$i]['Uploaded']));
        }

        if ($stmt->columnCount() >= 1) {
            return $results;
        }
    }
}