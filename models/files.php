<?php
/* 
 * Project: Nathan MVC
 * File: /models/home.php
 * Purpose: model for the home controller.
 * Author: Nathan Davison
 */

class FilesModel extends BaseModel
{
    //data passed to the home index view
    public function upload()
    {
        $this->viewModel->set("pageTitle","Upload");
        return $this->viewModel;
    }
}

?>
