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
        echo " has-errors ";
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

function ParseDate(string $date)
{
    return DateTime::createFromFormat('j.m.Y', $date);
}

function VerifyDate(string $date)
{
    return (DateTime::createFromFormat('j.m.Y', $date) !== false);
}