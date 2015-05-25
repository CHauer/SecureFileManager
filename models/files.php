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

        try {
            $fileRepo = new FileRepository();
            $files = $fileRepo->GetPublicAndOwnFiles('', '');

            $this->viewModel->set("model", $files);

        } catch (Exception $e) {
            $this->viewModel->set("error", $e->getMessage());
        }

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

        try {
        $fileRepo = new FileRepository();

        $file = $fileRepo->GetFile($fileid);

        $this->viewModel->set("model", $file);

        } catch (Exception $e) {
            $this->viewModel->set("error", $e->getMessage());
        }

        return $this->viewModel;
    }

    public function details($fileid)
    {
        $this->viewModel->set("pageTitle","File Details");

        try
        {
            $fileRepo = new FileRepository();
            $file = $fileRepo->GetFile($fileid);
            $this->viewModel->set("model", $file);

            $commRepo = new CommentRepository();

           /* $commRepo = new CommentRepository();
            $comments = $commRepo->GetComments($fileid);
            $this->viewModel->set("comments", $comments);*/

        } catch (Exception $e) {
            $this->viewModel->set("error", $e->getMessage());
        }

        return $this->viewModel;
    }
}

?>
