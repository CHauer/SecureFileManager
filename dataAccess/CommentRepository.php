<?php
/**
 * Created by PhpStorm.
 * User: Marlies
 * Date: 25.05.2015
 * Time: 23:56
 */

class CommentRepository extends BaseRepository {

    public function __construct($db)
    {
        parent::__construct($db);
    }

    public function InsertComment(Comment $comment)
    {
        $stmt = $this->db->prepare("INSERT INTO [dbo].[Comment]
          ([Message],
          [UserId],
          [UserFile_UserFileId])
     VALUES
           (:Message,
            :UserId,
            :UserFileId)");

        $stmt->bindParam(":Message", htmlspecialchars($comment->Message));
        $stmt->bindParam(":UserId", $comment->UserId);
        $stmt->bindParam(":UserFileId", $comment->UserFile_UserFileId);

        $stmt->execute();

        if ($stmt->rowCount() == 1)
        {
            return $this->db->lastInsertId();
        }

        return false;
    }

    public function GetComments($fileid)
    {
        $stmt = $this->db->prepare('Select [Comment].*, Username, PictureLink
                              From [Comment] left join [User] on [Comment].UserId = [User].UserId
                              where [Comment].UserFile_UserFileId = :fileid
                              order by Created DESC');

        $stmt->bindParam(':fileid', $fileid);

        $stmt->execute();

        $results = $stmt->fetchAll();

        /*$test[] = array();

        for ($i = 0; $i < count($results); ++$i)
        {
            $user = new User();
            $user->Username = $results[$i]['Username'];
            $user->PictureLink = $results[$i]['PictureLink'];

            $comment = new Comment();
            $comment->CommentId = $results[$i]['CommentId'];
            $comment->Message = $results[$i]['Message'];
            $comment->Created= ModelDateTimeValue($results[$i]['Created']);
            $comment->UserId= $results[$i]['UserId'];
            $comment->UserFile_UserFileId = $results[$i]['UserFile_UserFileId'];
            $comment->User = $user;

            $test[] = $comment;
        }*/

        if ($stmt->columnCount() >= 1)
        {
            return $results;
        }
        return null;
    }

}