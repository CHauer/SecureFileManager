<?php
/**
 * Created by PhpStorm.
 * User: Christoph
 * Date: 19.05.2015
 * Time: 17:10
 */

function GenerateAntiCSRFToken()
{
    if (!isset($_SESSION["CSRFToken"])) {
        $_SESSION["CSRFToken"] = md5(uniqid(mt_rand(), true));
    }
}

function CreateHiddenAntiCSRFTokenField()
{
    echo '<input type="hidden" name="CSRFToken" value="' . $_SESSION["CSRFToken"] . '">';
}

function CheckAntiCSRFToken()
{
    if (isset($_POST["CSRFToken"]) && $_POST["CSRFToken"] == $_SESSION["CSRFToken"])
    {
        return true;
    }

    global $log;
    global $loader;

    $con = $loader->getCurrentController();
    $act = $loader->getCurrentAction();

    $log->LogMessage("CRSF Error on call to " . $con . " - " . $act. ".", LOGGER_WARNING);

    RedirectAction("error", "unexpectedError");
}