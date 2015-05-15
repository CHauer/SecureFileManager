<?php
/**
 * Created by PhpStorm.
 * User: Christoph
 * Date: 14.05.2015
 * Time: 14:16
 */

function RedirectAction($controller, $action, $id = NULL)
{
    if (headers_sent() === false)
    {
        header('Location: ' . $controller. '/' . $action . '/'. $id);
    }

    exit();
}


function RedirectUrl($url, $permanent = false)
{
    if (headers_sent() === false)
    {
        header('Location: ' . $url, true, ($permanent === true) ? 301 : 302);
    }

    exit();
}