<?php
/**
 * Created by PhpStorm.
 * User: Christoph
 * Date: 19.05.2015
 * Time: 20:53
 */

function ValidationErrorClass ($field, ViewModel $viewModel)
{
    if($viewModel->isFieldError($field)) {
        echo " has-error ";
    }
}

function ValidationErrorMessage($field, ViewModel $viewModel)
{
    if($viewModel->IsErrorSet() || $viewModel->isFieldError($field)) {
        $msg = $viewModel->getFieldError($field);
        if($msg != NULL) {
            echo '<span class="color-red">'. $msg . '</span>';
        }
    }
}

function ParseDate($date)
{
    $value = NULL;
    try {
        $value = DateTime::createFromFormat('j.m.Y', $date);
    }catch(Exception $ex){
        $value = NULL;
    }

    try {
        $value = DateTime::createFromFormat('d.m.Y', $date);
    }catch(Exception $ex){
        $value = NULL;
    }

    if($value == NULL) {
        throw new Exception('The given Date has a invalid format! Please try 01.01.1900!');
    }

    return $value;

}

function VerifyDate($date)
{
    return ((DateTime::createFromFormat('j.m.Y', $date) !== false) ||
        (DateTime::createFromFormat('d.m.Y', $date) !== false));
}