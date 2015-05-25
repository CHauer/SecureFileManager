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
            [Description],
            [UserId],
            [Created],
            [IsDeleted]
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
        $thread->UserId = $result["UserId"];
        $thread->Created = $result["Created"];
        $thread->IsDeleted = $result["IsDeleted"];

        return $thread;
    }

    public function GetEntryById($entryId)
    {
        global $db;

        $stmt = $db->prepare('select
            [Message],
            [UserId],
            [Created],
            [IsDeleted],
            [ForumThreadId]
           from [dbo].[Entry]
           where [EntryId]=:entryid');

        $stmt->bindParam(":entryid", $entryId);
        $stmt->execute();

        $result = $stmt->fetch();

        if ($result == false)
        {
            throw new InvalidArgumentException("The given entry ID does not exist!");
        }

        $entry = new Entry();

        $entry->EntryId = $entryId;
        $entry->Message = $result["Message"];
        $entry->UserId = $result["UserId"];
        $entry->Created = $result["Created"];
        $entry->IsDeleted = $result["IsDeleted"];
        $entry->ForumThreadId = $result["ForumThreadId"];

        return $entry;
    }

    public function GetEntriesForThread($threadId)
    {
        global $db;

        $stmt = $db->prepare('SELECT [EntryId], [Message], [Created], [Entry].[UserId], [Username]
                                FROM [Entry] JOIN [User] ON [Entry].[UserId] = [User].[UserId]
                                WHERE [IsDeleted] = 0
                                ORDER BY [Created] DESC');

        $stmt->execute();

        $results = $stmt->fetchAll();

        for ($i = 0; $i < count($results); ++$i) {
            $results[$i]['Created'] = date("d.m.Y H:i", strtotime($results[$i]['Created']));
        }

        if ($stmt->columnCount() >= 1) {
            return $results;
        } else {
            return null;
        }
    }

    public function GetNotDeletedThreads()
    {
        global $db;

        $stmt = $db->prepare('SELECT [ForumThreadId], [Title], [ForumThread].[Description], [Created], [User].[UserId], [User].[Username], (SELECT count(EntryId) FROM [Entry] WHERE [ForumThreadId] = [ForumThread].[ForumThreadId] AND [IsDeleted] = 0) as EntryCount
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

    public function PostEntryToThread($entry)
    {
        global $db;

        $stmt = $db->prepare("INSERT INTO [dbo].[Entry]
           ([Message]
           ,[ForumThreadId]
           ,[UserId])
     VALUES
           (:Message,
           :ForumThreadId,
           :UserId)");
        $stmt->bindParam(":Message", $entry->Message);
        $stmt->bindParam(":ForumThreadId", $entry->ForumThreadId);
        $stmt->bindParam(":UserId", $entry->UserId);

        $stmt->execute();

        if ($stmt->rowCount() == 1)
        {
            return $db->lastInsertId();
        }

        return false;
    }

    /**
     * @param int $forumThreadId
     * @return bool
     */
    public function DeleteById($forumThreadId)
    {
        global $db;

        $stmt = $db->prepare('UPDATE [ForumThread] SET [IsDeleted] = 1 where [ForumThreadid] = :threadid');
        $stmt->bindParam(':threadid', $forumThreadId);
        $stmt->execute();

        if ($stmt->rowCount() == 1)
        {
            return true;
        }
        return false;
    }

    /**
     * @param int $entryId
     * @return bool
     */
    public function DeleteEntryById($entryId)
    {
        // TODO: return error if entry doesn't exist?
        global $db;

        $stmt = $db->prepare('UPDATE [Entry] SET [IsDeleted] = 1 where [EntryId] = :entryid');
        $stmt->bindParam(':entryid', $entryId);
        $stmt->execute();

        if ($stmt->rowCount() == 1)
        {
            return true;
        }
        return false;
    }
}