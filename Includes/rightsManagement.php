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

    if (!IsUserAdmin())
    {
        header ("Location: /home/index");
    }
}

function IsUserAdministrator()
{
    $userRepo = new UserRepository();
    return $userRepo->IsUserInRole("Administrator", intval($_SESSION['userid']));
}

function IsStandardUser()
{
    $userRepo = new UserRepository();
    return $userRepo->IsUserInRole("Standard", intval($_SESSION['userid']));
}

function IsPremiumUser()
{
    $userRepo = new UserRepository();
    return $userRepo->IsUserInRole("Premium", intval($_SESSION['userid']));
}

function isFileOwner($userid)
{
    if ($_SESSION['userid'] == $userid)
    {
        return true;
    }
    return false;
}

function IsThreadOwner($forumThreadId, $userId)
{
    $forumrepo = new ForumRepository();

    $thread = $forumrepo->GetForumThreadById($forumThreadId);

    if($thread->UserId === $userId) {
        return true;
    }

    return false;
}
?>