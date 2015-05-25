<?php
/**
 * Created by PhpStorm.
 * User: Marlies
 * Date: 25.05.2015
 * Time: 23:56
 */

class CommentRepository {

    public function InsertComment(Comment $comment)
    {
        global $db;
        $stmt = $db->prepare("INSERT INTO [dbo].[Comment]
          ([Message],
          [UserId],
          [UserFile_UserFileId]
     VALUES
           (:Message,
            :UserId,
            :UserFileId)");

        $stmt->bindParam(":Message", PrepareHtml($comment->Message));
        $stmt->bindParam(":UserId", $comment->UserId);
        $stmt->bindParam(":UserFileId", $comment->UserFile_UserFileId);

        $stmt->execute();

        if ($stmt->rowCount() == 1)
        {
            return $db->lastInsertId();
        }

        return false;
    }

    public function GetComments($fileid)
    {
        global $db;
        $isprivate = 0;

        $stmt = $db->prepare('Select [Comment].Message, [Comment].Created, Username, PictureLink
                              From [Comment] left join [User] on [Comment].UserId = [User].UserId
                              where [Comment].UserFile_UserFileId = :fileid
                              order by Created DESC');

        $stmt->bindParam(':fileid', $fileid);

        $stmt->execute();

        $results = $stmt->fetchAll();

        if ($stmt->columnCount() >= 1) {
            return $results;
        }
        return null;
    }

}