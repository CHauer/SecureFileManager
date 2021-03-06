<?php
/**
 * Created by PhpStorm.
 * User: Christoph
 * Date: 14.05.2015
 * Time: 04:02
 */

class FileRepository extends  BaseRepository
{
    public function __construct($db)
    {
        parent::__construct($db);
    }

    /**
     * @param UserFile $file
     * @return bool
     */
    public function InsertFile(UserFile $file)
    {

        $stmt = $this->db->prepare("INSERT INTO [dbo].[UserFile]
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
        $stmt->bindParam(":Description", PrepareHtml($file->Description));
        $stmt->bindParam(":IsPrivate", $file->IsPrivate);
        $stmt->bindParam(":UserId", $file->UserId);

        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function GetFile($fileid)
    {


        $stmt = $this->db->prepare('select top 1
            [Description]
            ,[UserFileId]
           ,[Name]
           ,[UserId]
           ,[IsPrivate]
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
        $file->UserId = $result["UserId"];
        $file->IsPrivate = $result["IsPrivate"];

        return $file;
    }

    private function GetFileLink($fileid) {



        $stmt = $this->db->prepare('Select FileLink from [UserFile] where UserFileId = :fileid');
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


        $stmt = $this->db->prepare('delete from [Comment] where UserFile_UserFileId = :fileid');
        $stmt->bindParam(':fileid', $fileid);
        $stmt->execute();

        $path = $this->GetFileLink($fileid);

        if (file_exists($path))
        {
            unlink($path);
        }

        $stmt = $this->db->prepare('Delete From [UserFile]
                              where UserFileId = :fileid');
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

        $isprivate = 0;

        if ($order == 'Uploaded')
        {
            $order = 'Uploaded DESC';
        }

        $stmt = $this->db->prepare('Select [UserFile].*, Username, PictureLink, (Select count(Commentid) From Comment where UserFile_UserFileId = [Userfile].UserFileId) as CommentCount
                              from [UserFile] left join [User] on [UserFile].UserId = [User].UserId
                              where (IsPrivate = :ispriv or [UserFile].UserId = :id) and [User].Username LIKE :user
                              and Name LIKE :file order by ' . $order . ', [UserFile].Name');

        $user = '%' . $user . '%';
        $file = '%' . $file . '%';

        $stmt->bindParam(':ispriv', $isprivate);
        $stmt->bindParam(':id', $_SESSION["userid"]);
        $stmt->bindParam(':user', $user, PDO::PARAM_STR);
        $stmt->bindParam(':file', $file, PDO::PARAM_STR);

        $stmt->execute();

        $results = $stmt->fetchAll();

        if ($stmt->columnCount() >= 1) {
            return $results;
        }

        return null;
    }

    public function GetMyFiles($file, $order = 'Uploaded')
    {


        if ($order == 'Uploaded')
        {
            $order = 'Uploaded DESC';
        }

        $stmt = $this->db->prepare('Select [UserFile].*, Username, PictureLink,
                              (Select count(Commentid) From Comment where UserFile_UserFileId = [Userfile].UserFileId) as CommentCount
                              from [UserFile] left join [User] on [UserFile].UserId = [User].UserId
                              where Name LIKE :file and [UserFile].UserId = :id
                              order by ' . $order . ', [UserFile].Name');

        $file = '%' . $file . '%';

        $stmt->bindParam(':file', $file, PDO::PARAM_STR);
        $stmt->bindParam(':id', $_SESSION["userid"]);

        $stmt->execute();
        $results = $stmt->fetchAll();

        if ($stmt->columnCount() >= 1) {
            return $results;
        }

        return null;
    }

    public function DownloadFile($fileid)
    {
        $path = $this->GetFileLink($fileid);

        if (file_exists($path))
        {
            header('Content-Description: File Transfer');
            //header('Content-Type: application/octet-stream');
            header('Content-Type: application/x-unknown');
            header('Content-Disposition: attachment; filename='.basename($path));
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($path));
            ob_clean();
            flush();
            readfile($path);
        }
        else
        {
            return false;
        }
        return true;
    }
}