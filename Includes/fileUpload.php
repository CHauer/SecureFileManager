<?php
/**
 * Created by PhpStorm.
 * User: Christoph
 * Date: 15.05.2015
 * Time: 03:08
 */

function HandleFileUpload($postFileName, $directory){

    if(isset($_FILES[$postFileName]))
    {
        $dname = explode(".", $_FILES[$postFileName]["name"]);
        $ext = $dname[count($dname) - 1];

        if ($_FILES[$postFileName]["size"] > 0)
        {
            $uniqid = uniqid();
            $filename = $uniqid . '.' . $ext;
            $filepath = $directory . '/' . $filename;

            copy($_FILES[$postFileName]["tmp_name"], $filepath);

            return $filepath;
        }
    }

    return NULL;
}

function HandlePictureUpload($postFileName, $directory){

    if(isset($_FILES[$postFileName]))
    {
        $dname = explode(".", $_FILES[$postFileName]["name"]);
        $ext = $dname[count($dname) - 1];

        if ($_FILES[$postFileName]["size"] > 0 && ($ext == "jpg" ||$ext == "png" || $ext == "gif" ))
        {
            $uniqid = uniqid();
            $filename = $uniqid . '.' . $ext;
            $filepath = $directory . '/' . $filename;

            copy($_FILES[$postFileName]["tmp_name"], $filepath);

            return $filepath;
        }
    }

    return NULL;
}