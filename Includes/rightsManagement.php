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
    return $userRepo->IsUserInRole("Administrator");
}

function IsStandardUser()
{
    $userRepo = new UserRepository();
    return $userRepo->IsUserInRole("Standard");
}

function IsPremiumUser()
{
    $userRepo = new UserRepository();
    return $userRepo->IsUserInRole("Premium");
}

?>