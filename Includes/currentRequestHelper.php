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


function ModelValue($model, $field)
{
    if($model != NULL && isset($model))
    {
        echo ' value="' . $model->{$field} . '" ';
    }
}

function ModelDateValue($model, $field)
{
    if($model != NULL && isset($model))
    {
        echo ' value="' . date_format($model->{$field}, 'd.m.Y') . '" ';
    }
}

function EchoModelValue($model, $field)
{
    if($model != NULL && isset($model))
    {
        echo $model->{$field};
    }
}


function EchoModelDate($model, $field)
{
    if($model != NULL && isset($model) && isset($model->{$field}))
    {
        echo  date_format($model->{$field}, 'd.m.Y');
    }
}


function ModelDateTimeValue($field)
{
    return date( 'l, d.m.Y H:i', strtotime($field));
}

function GetLogTypeName($typ){
    switch($typ){
        case LOGGER_ERROR:
            return "Error";
        case LOGGER_INFO:
            return "Info";
        case LOGGER_WARNING:
            return "Warning";
        case LOGGER_DEBUG:
            return "Debug";
    }

    return "Undefined";
}
