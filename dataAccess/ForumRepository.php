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
     * @return bool
     */
    public function InsertThread(ForumThread $thread){
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
            :UserId)');
        $stmt->bindParam(":Title", $thread->Title);
        $stmt->bindParam(":Description", $thread->Description);
        $stmt->bindParam(":IsDeleted", $thread->IsDeleted);
        $stmt->bindParam(":UserId", $thread->UserId);

        $stmt->execute();

        if ($stmt->rowCount() == 1)
        {
            return $db->lastInsertId();
        }

        return false;
    }

    /**
     * @param int $threadId
     * @return ForumThread
     */
    public function GetForumThreadById($threadId)
    {
        global $db;

        $stmt = $db->prepare('select
            [Title],
            [Description]
           from [dbo].[ForumThread]
           where [ForumThreadId]=:threadid');

        $stmt->bindParam(":threadid", $threadId);
        $stmt->execute();

        $result = $stmt->fetch();

        if ($result == false)
        {
            throw new InvalidArgumentException("The given thread ID does not exist!");
        }

        $thread = new ForumThread();

        $thread->ForumThreadId = $threadId;
        $thread->Title = $result["Title"];
        $thread->Description = $result["Description"];

        return $thread;
    }

    public function GetNotDeletedThreads()
    {
        global $db;

        $stmt = $db->prepare('SELECT [ForumThreadId], [Title], [ForumThread].[Description], [Created], [User].[UserId], [User].[Username], (SELECT count(EntryId) FROM [Entry] WHERE [ForumThreadId] = [ForumThread].[ForumThreadId]) as EntryCount
                                FROM [ForumThread] JOIN [User] ON [ForumThread].[UserId] = [User].[UserId]
                                WHERE [IsDeleted] = 0
                                ORDER BY [Created] DESC');

        $stmt->execute();

        $results = $stmt->fetchAll();

        for ($i = 0; $i < count($results); ++$i) {
            $results[$i]['Created'] = date("d.m.Y H:i", strtotime($results[$i]['Created']));
        }

        if ($stmt->columnCount() >= 1) {
            return $results;
        }
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