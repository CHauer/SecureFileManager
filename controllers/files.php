<?php
/* 
 * Project: Nathan MVC
 * File: /controllers/home.php
 * Purpose: controller for the home of the app.
 * Author: Nathan Davison
 */

class FilesController extends BaseController
{
    //add to the parent constructor
    public function __construct($action, $urlValues) {
        parent::__construct($action, $urlValues);
        
        //create the model object
        require("models/files.php");
        $this->model = new FilesModel();
    }

    //default method
    protected function index()
    {
        ConfirmUserIsLoggedOn();

       /* $fileRepo = new FileRepository();
        $files = $fileRepo->GetPublicAllFiles();*/

        $this->view->output($this->model->index());
    }

    protected function upload()
    {
        $viewModel = $this->model->upload();

        if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST')
        {

            if(!$this->validateRegisterData($viewModel)) {
                $this->view->output($viewModel);
                return;
            }

            #region # Create File
            $file = new UserFile();
            $viewModel->set("model", $file);
            $file->Name = $_POST["Name"];
            $file->Description = $_POST["Description"];
            if (isset($_POST["IsPrivate"]))
            {
                $file->IsPrivate = '1';
            } else {
                $file->IsPrivate = '0';
            }
            $file->UserId  = $_SESSION["userid"];
            #endregion

            #region # Insert File
            try
            {
                $filelink = $this->HandleUpload("FileLink", "/upload/files", $file->Name);

                if (is_null($filelink) || $filelink == '')
                {
                    throw new Exception("Something went wrong during handle the link - please try again!");
                }
                $file->FileLink = $filelink;

                $filesrepo = new FileRepository();
                $fileid = $filesrepo->InsertFile($file);

                if($fileid == false)
                {
                    $viewModel->set("error", "Something went wrong during upload a file - please try again!");
                }
            }
            catch(Exception $e)
            {
                $viewModel->set("error", $e->getMessage());
            }
            #endregion

            //no error
            if(!$viewModel->exists("error"))
            {
                RedirectAction("files", "index");
                return;
            }
        }

        $this->view->output($viewModel);
    }

    private function HandleUpload($postFileName, $directory, $filename){

        if(isset($_FILES[$postFileName]))
        {
            if ($_FILES[$postFileName]["size"] > 0)
            {
                $filename = $filename . '.' . time();
                $filepath = $directory . '/' . $_SESSION["userid"] . '/' . $filename;

                copy($_FILES[$postFileName]["tmp_name"], $filepath);

                return $filepath;
            }
        }

        return NULL;
    }

    private function validateRegisterData(ViewModel &$viewModel)
    {
        $ok = true;

        if(!isset($_POST["Name"]) || $_POST["Name"] == ''){
            $viewModel->setFieldError("Name", "Name has to be entered!");
            $ok = false;
        }

        if(!isset($_FILES["FileLink"]) || $_FILES["FileLink"] == '') {
            $viewModel->setFieldError("FileLink", "FileLink has to be entered!");
            $ok = false;
        }

        return $ok;
    }
}

?>
