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
    public function index()
    {
        $this->viewModel->set("pageTitle","Upload List");
        return $this->viewModel;
    }

    //data passed to the home index view
    public function upload()
    {
        $this->viewModel->set("pageTitle","Upload");
        return $this->viewModel;
    }

    public function delete($fileid)
    {
        $this->viewModel->set("pageTitle","Delete File");

        //$fileRepo = new FileRepository();

//        $file = $fileRepo->GetFile($fileid);

  //      $this->viewModel->set("model", $file);

        return $this->viewModel;
    }
}

?>
