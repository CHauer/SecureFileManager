<?php
/**
 * Created by PhpStorm.
 * User: Christoph
 * Date: 15.05.2015
 * Time: 02:02
 */

function IsUserLoggedOn()
{
    return isset($_SESSION['userid']);
}

function ConfirmUserIsLoggedOn()
{
    if (!IsUserLoggedOn())
    {
        header ("Location: /account/login");
    }
}

function ConfirmUserIsAdmin() {
    if (!IsUserLoggedOn())
    {
        header ("Location: /account/login");
    }

    if (!IsUserAdministrator())
    {
        header ("Location: /home/index");
    }
}

function IsUserAdministrator()
{
    $userRepo = new UserRepository(CreateDatabaseAccess());
    return $userRepo->IsUserInRole("Administrator", intval($_SESSION['userid']));
}

function IsStandardUser()
{
    $userRepo = new UserRepository(CreateDatabaseAccess());
    return $userRepo->IsUserInRole("Standard", intval($_SESSION['userid']));
}

function IsPremiumUser()
{
    $userRepo = new UserRepository(CreateDatabaseAccess());
    return $userRepo->IsUserInRole("Premium", intval($_SESSION['userid'])) || $userRepo->IsUserInRole("Administrator", intval($_SESSION['userid']));
}

function isFileOwner($userid, $fileid)
{
    $filerepo = new FileRepository(CreateDatabaseAccess());

    $thread = $filerepo->GetFile($fileid);

    if($thread->UserId == $userid)
    {
        return true;
    }

    return false;
}

function IsThreadOwner($forumThreadId, $userId)
{
    $forumrepo = new ForumRepository(CreateDatabaseAccess());

    $thread = $forumrepo->GetForumThreadById($forumThreadId);

    if($thread->UserId == $userId) {
        return true;
    }

    return false;
}

function IsEntryOwner($entryId, $userId)
{
    $forumrepo = new ForumRepository(CreateDatabaseAccess());

    $entry = $forumrepo->GetEntryById($entryId);

    if($entry->UserId == $userId) {
        return true;
    }

    return false;
}
?>