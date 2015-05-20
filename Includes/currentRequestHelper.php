<?php
/**
 * Created by PhpStorm.
 * User: Christoph
 * Date: 20.05.2015
 * Time: 04:13
 */

function CurrentActive ($controller, $action)
{
    global $loader;
    $currentAction = $loader->getCurrentAction();
    $currentController = $loader->getCurrentController();

    if($currentController == $controller && $currentAction == $action)
    {
        echo ' active ';
    }
}