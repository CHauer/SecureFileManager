<?php
/**
 * Created by PhpStorm.
 * User: Christoph
 * Date: 15.05.2015
 * Time: 03:08
 */

function HandleFileUpload($postFileName, $directory)
{
    if (isset($_FILES[$postFileName]))
    {
        $dname = explode(".", $_FILES[$postFileName]["name"]);
        $ext = strtolower($dname[count($dname) - 1]);

        switch ($_FILES[$postFileName]['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new RuntimeException('No file sent.');
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new RuntimeException('Exceeded filesize limit.');
            default:
                throw new RuntimeException('Unknown errors.');
        }

        $uniqid = uniqid();
        $filename = $uniqid . '.' . $ext;
        $path = $directory;

        try {
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
        } catch (Exception $ex) {
        }

        //max 100mb
        if ($_FILES[$postFileName]["size"] > 0 && $_FILES[$postFileName]["size"] < 1024 * 1024 * 100) {
            $filepath = $path . '/' . $filename;

            if (!copy($_FILES[$postFileName]["tmp_name"], $filepath)) {
                throw new RuntimeException('Failed to move uploaded file.');
            }

            return $filepath;
        }
    }

    return NULL;
}

function HandlePictureUpload($postFileName, $directory)
{

    if (isset($_FILES[$postFileName])) {
        $dname = explode(".", $_FILES[$postFileName]["name"]);
        $ext = strtolower($dname[count($dname) - 1]);

        switch ($_FILES[$postFileName]['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new RuntimeException('No file sent.');
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new RuntimeException('Exceeded filesize limit.');
            default:
                throw new RuntimeException('Unknown errors.');
        }

        $uniqid = uniqid();
        $filename = $uniqid . '.' . $ext;
        $path = $directory;

        try {
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
        } catch (Exception $ex) {
            return NULL;
        }

        //max 30mb
        if ($_FILES[$postFileName]["size"] > 0 && $_FILES[$postFileName]["size"] < 1024 * 1024 * 30 &&
            ($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg' || $ext == 'gif' || $ext == 'bmp')
        ) {
            $filepath = $path . '/' . $filename;

            if (!copy($_FILES[$postFileName]["tmp_name"], $filepath)) {
                throw new RuntimeException('Failed to move uploaded file.');
            }

            return $filepath;
        }
    }

    return NULL;
}
