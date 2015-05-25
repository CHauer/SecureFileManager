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
        $stmt->bindParam(":Name", htmlspecialchars($file->Name));
        $stmt->bindParam(":FileLink", $file->FileLink);
        $stmt->bindParam(":Description", htmlspecialchars($file->Description));
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

    private function GetFileLink($fileid) {

        global $db;

        $stmt = $db->prepare('Select FileLink from [UserFile] where UserFileId = :fileid');
        $stmt->bindParam(':fileid', $fileid);
        $stmt->execute();

        $result = $stmt->fetch();

        if ($result == false)
        {
            throw new InvalidArgumentException("The given fileid does not exist!");
        }

        return $result["FileLink"];
    }

    public function DeleteFile($fileid)
    {
        global $db;

        $stmt = $db->prepare('delete from [Comment] where UserFile_UserFileId = :fileid');
        $stmt->bindParam(':fileid', $fileid);
        $stmt->execute();

        $path = $this->GetFileLink($fileid);

        if (file_exists($path))
        {
            unlink($path);
        }

        $stmt = $db->prepare('Delete From [UserFile] where UserFileId = :fileid');
        $stmt->bindParam(':fileid', $fileid);
        $stmt->execute();

        if ($stmt->rowCount() == 1)
        {
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

    public function DownloadFile($fileid)
    {
        $path = $this->GetFileLink($fileid);

        if (file_exists($path))
        {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='.basename($path));
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($path));
            ob_clean();
            flush();
            readfile($path);
        }
    }
}