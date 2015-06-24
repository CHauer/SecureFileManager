<?php
/* 
 * Project: Nathan MVC
 * File: /models/home.php
 * Purpose: model for the home controller.
 * Author: Nathan Davison
 */

class FilesModel extends BaseModel
{
    public function __construct($db)
    {
        parent::__construct($db);
    }

    //data passed to the home index view
    public function index()
    {
        $this->viewModel->set("pageTitle","All Files");

        try {
            $fileRepo = new FileRepository($this->db);
            $files = $fileRepo->GetPublicAndOwnFiles('', '');

            $this->viewModel->set("model", $files);

        } catch (Exception $e) {
            $this->viewModel->set("error", $e->getMessage());
        }

        return $this->viewModel;
    }

    public function myfiles()
    {
        $this->viewModel->set("pageTitle","My Files");
        try
        {
            $fileRepo = new FileRepository($this->db);
            $files = $fileRepo->GetMyFiles('');

            $this->viewModel->set("model", $files);
        }
        catch (Exception $e)
        {
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
        $fileRepo = new FileRepository($this->db);

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
            $fileRepo = new FileRepository($this->db);
            $file = $fileRepo->GetFile($fileid);
            $this->viewModel->set("model", $file);

            $commRepo = new CommentRepository($this->db);
            $comments = $commRepo->GetComments($fileid);
            $this->viewModel->set("comments", $comments);

        } catch (Exception $e) {
            $this->viewModel->set("error", $e->getMessage());
        }

        return $this->viewModel;
    }
}

?>
