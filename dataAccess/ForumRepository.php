<?php
/**
 * Created by PhpStorm.
 * User: Christoph
 * Date: 15.05.2015
 * Time: 04:50
 */

class ForumRepository {

    /**
     * @param ForumThread $thread
     * @param string $statement by reference
     * @return bool
     */
    public function InsertThread(ForumThread $thread, string &$statement){
        global $db;
        $stmt = $db->prepare('INSERT INTO [dbo].[ForumThread]
          ([Title],
          [Description],
          [IsDeleted],
          [UserId])
     VALUES
           (:Title,
            :Description,
            :IsDeleted,
            :UserId');
        $stmt->bindParam(":Title", $thread->Title);
        $stmt->bindParam(":Description", $thread->Description);
        $stmt->bindParam(":IsDeleted", $thread->IsDeleted);
        $stmt->bindParam(":UserId", $thread->UserId);

        $statement = $stmt->queryString;

        $stmt->execute();

        if ($stmt->rowCount() == 1)
        {
            return $db->lastInsertId();
        }

        return false;
    }

    /**
     * @param int $userId
     */
    public function GetForumThreadForUser(int $userId)
    {
        global $db;

        /*$stmt = $db->prepare('Select RoleId from [Role] where Name = :name');
        $stmt->bindParam(':name', $name);

        $stmt->execute();

        $results = $stmt->fetchAll();

        if ($stmt->columnCount() == 1) {
            return $results[0]['RoleId'];
        }*/
    }

}