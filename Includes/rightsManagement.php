<?php
/**
 * Created by PhpStorm.
 * User: Christoph
 * Date: 15.05.2015
 * Time: 02:02
 */

function logged_on()
{
    return isset($_SESSION['userid']);
}

function confirm_is_admin() {
    if (!logged_on())
    {
        header ("Location: /account/login");
    }

    if (!is_admin())
    {
        header ("Location: /home/index");
    }
}

function is_admin()
{
    global $db;

    $query = "SELECT Top(1) UserId FROM [User] U INNER JOIN [Role] R on U.RoleId = R.RoleId WHERE R.Name = 'Administrator' AND U.UserId=:id";
    $statement = $db->prepare($query);
    $statement->bindValue(':id', $_SESSION['userid']);
    $statement->execute();
    return $statement->num_rows == 1;
}
?>