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
    public function __construct($action, $urlValues, $db)
    {
        parent::__construct($action, $urlValues, $db);

        //create the model object
        require("models/files.php");
        $this->model = new FilesModel($db);
    }

    //default method
    protected function index()
    {
        $_SESSION["fileview"] = "index";

        ConfirmUserIsLoggedOn();

        $viewModel = $this->model->index();

        if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST')
        {
            try {
                $fileRepo = new FileRepository($this->db);
                $files = $fileRepo->GetPublicAndOwnFiles($_POST["User"], $_POST["Name"]);

                $viewModel->set("model", $files);

            } catch (Exception $e)
            {
                // log Fehler Read files
                $this->log->LogMessage('Error during loading files by UserID ' . $_SESSION["userid"] . '.', LOGGER_ERROR);
                $viewModel->set("error", $e->getMessage());
            }

        }

        $this->view->output($viewModel);
    }

    //default method
    protected function myfiles()
    {
        $_SESSION["fileview"] = "myfiles";

        ConfirmUserIsLoggedOn();

        $viewModel = $this->model->myfiles();

        if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST')
        {
            try
            {
                $fileRepo = new FileRepository($this->db);
                $files = $fileRepo->GetMyFiles($_POST["Name"]);

                $viewModel->set("model", $files);

            } catch (Exception $e)
            {
                // log Fehler Read files
                $this->log->LogMessage('Error during loading files by UserID ' . $_SESSION["userid"] . '.', LOGGER_ERROR);
                $viewModel->set("error", $e->getMessage());
            }

        }

        $this->view->output($viewModel);
    }

    protected function upload()
    {
        ConfirmUserIsLoggedOn();

        if (!IsPremiumUser())
        {
            RedirectAction("files", $_SESSION["fileview"]);
            return;
        }

        $viewModel = $this->model->upload();

        if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST')
        {
            CheckAntiCSRFToken();

            try {
                #region # Create File
                $file = new UserFile();
                $viewModel->set("model", $file);

                $file->Name = $_POST["Name"];
                $file->Description = PrepareHtml($_POST["Description"]);
                if (isset($_POST["IsPrivate"])) {
                    $file->IsPrivate = '1';
                } else {
                    $file->IsPrivate = '0';
                }
                $file->UserId = $_SESSION["userid"];
                #endregion
            } catch (Exception $ex) {
                ;

                // log Fehler File Upload
                $this->log->LogMessage('Error during file upload by UserID ' . $file->UserId . '. Create UserFile Object.', LOGGER_ERROR);
            }

            if (!$this->validateFileData($viewModel)) {
                $this->view->output($viewModel);

                // log Fehler File Upload
                $this->log->LogMessage('Error during file upload by UserID ' . $file->UserId . '. Validate File Data', LOGGER_ERROR);

                return;
            }

            #region # Insert File
            try {
                $filelink = $this->HandleUpload("FileLink");

                if (is_null($filelink) || $filelink == '')
                {
                    throw new Exception("Something went wrong during handle the link - please try again!");

                }
                $file->FileLink = $filelink;

                $filesrepo = new FileRepository($this->db);
                $fileid = $filesrepo->InsertFile($file);
                if ($fileid == false)
                {
                    throw new Exception("Something went wrong during upload a file - please try again!");

                }
            } catch (Exception $e) {
                $viewModel->set("error", $e->getMessage());
                // log Fehler File Upload
                $this->log->LogMessage('Error during file upload by UserID ' . $file->UserId . '.', LOGGER_ERROR);
            }
            #endregion

            //no error
            if (!$viewModel->exists("error"))
            {
                // log File Upload
                $this->log->LogMessage('Uploaded the file ' . $file->Name . ' (ID: ' . $file->UserFileId . ') by UserID ' . $file->UserId, LOGGER_INFO);

                RedirectAction("files", $_SESSION["fileview"]);
                return;
            }
        }

        $this->view->output($viewModel);
    }

    private function HandleUpload($postFileName)
    {
        if (isset($_FILES[$postFileName]))
        {
            $dname = basename($_FILES[$postFileName]["name"]);

            switch ($_FILES[$postFileName]['error'])
            {
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

            $path = 'upload/files/' . $_SESSION["userid"];

            if (!file_exists($path))
            {
                mkdir($path, 0777, true);
            }

            if ($_FILES[$postFileName]["size"] > 0)
            {
                $filename = uniqid() . '_' . $dname;
                $filepath = $path . '/' . $filename;

                if (!move_uploaded_file($_FILES[$postFileName]["tmp_name"],
                        $filepath))
                {
                    throw new RuntimeException('Failed to move uploaded file.');
                }

                return $filepath;
            }
        }

        return NULL;
    }

    private function validateFileData(ViewModel &$viewModel)
    {
        $ok = true;

        if (!isset($_POST["Name"]) || $_POST["Name"] == '')
        {
            $viewModel->setFieldError("Name", "Name has to be entered!");
            $ok = false;
        }

        if (empty($_FILES['FileLink']['name']))
        {
            $viewModel->setFieldError("FileLink", "File Link has to be entered!");
            $ok = false;
        }

        $dname = explode(".", $_FILES["FileLink"]["name"]);
        $ext = strtolower($dname[count($dname) - 1]);

        //max 100Mb
        if (!($_FILES["FileLink"]["size"] > 0 && $_FILES["FileLink"]["size"] < 1024 * 1024 * 100))
        {
            $viewModel->setFieldError("FileLink", "File is to big (max. 100MB)!");
            $ok = false;
        }

        $ext=strtolower($ext);

        if($ext == 'php' || $ext == 'js' || $ext == 'htm' || $ext == 'html')
        {
            $viewModel->setFieldError("FileLink", "File has invalid type!");
            $ok = false;
        }

        return $ok;
    }

    protected function delete()
    {
        ConfirmUserIsLoggedOn();

        $id = $this->urlValues['id'];

        if (!isset($id) || empty($id))
        {
            $_SESSION['error'] = "Something went wrong - please try again!";

            // log Fehler Delete File
            $this->log->LogMessage('Error during delete a file by UserID ' . $_SESSION["userid"] . '. No FileID.', LOGGER_ERROR);

            RedirectAction("files", $_SESSION["fileview"]);
            return;
        }

        if (!isFileOwner($_SESSION["userid"], $id))
        {
            RedirectAction("files", $_SESSION["fileview"]);
            return;
        }

        $viewModel = $this->model->delete($id);

        if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST')
        {
            try {
                $fileRepo = new FileRepository($this->db);

                if (!$fileRepo->DeleteFile($id))
                {
                    // log Fehler delete file
                    $this->log->LogMessage('Error during delete a file (ID: ' . $id . ' by UserID ' . $_SESSION["userid"] . '. Delete DB Record.', LOGGER_ERROR);

                    $viewModel->set("error", "Something went wrong - please try again!");
                }

            } catch (Exception $e)
            {
                $viewModel->set("error", $e->getMessage());
                // log Fehler delete file
                $this->log->LogMessage('Error during delete a file (ID: ' . $id . ' by UserID ' . $_SESSION["userid"] . '.', LOGGER_ERROR);

            }

            //no error
            if (!$viewModel->exists("error"))
            {
                // log delete file
                $this->log->LogMessage('File (ID: ' . $id . ') successfully deleted by UserID ' . $_SESSION["userid"] , LOGGER_INFO);

                $_SESSION["deleteFile"] = "File successfully deleted!";
                RedirectAction("files", $_SESSION["fileview"]);
                return;
            }
        }

        $this->view->output($viewModel);

    }

    protected function details()
    {
        ConfirmUserIsLoggedOn();

        $id = $this->urlValues['id'];

        if (!isset($id) || empty($id))
        {
            $_SESSION['error'] = "Something went wrong - please try again!";

            // log Fehler Datail File
            $this->log->LogMessage('Error during show file details by UserID ' . $_SESSION["userid"] . '. No FileID.', LOGGER_ERROR);

            RedirectAction("files", $_SESSION["fileview"]);
            return;
        }

        $viewModel = $this->model->details($id);

        if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST')
        {
            CheckAntiCSRFToken();

            #region # Create Comment
            try {

                $comment = new Comment();

                $viewModel->set("comment", $comment);

                $comment->UserFile_UserFileId = $id;
                $comment->Message = PrepareHtml($_POST["Message"]);
                $comment->UserId = $_SESSION["userid"];

            } catch (Exception $ex)
            {
                // log Fehler Datail File
                $this->log->LogMessage('Error during insert a comment (FileID: ' . $comment->UserFile_UserFileId . ') by UserID ' . $_SESSION["userid"] . '. Create Comment Object.', LOGGER_ERROR);
            }
            #endregion

            if (!$this->validateCommentData($viewModel)) {
                $this->view->output($viewModel);

                // log Fehler Datail File
                $this->log->LogMessage('Error during insert a comment (FileID: ' . $comment->UserFile_UserFileId . ') by UserID ' . $_SESSION["userid"] . '. Validate Comment Data.', LOGGER_ERROR);

                return;
            }

            #region # Insert Comment
            try {

                $commrepo = new CommentRepository($this->db);
                $commid = $commrepo->InsertComment($comment);
                if ($commid == false)
                {
                    throw new Exception("Something went wrong during comment a file - please try again!");
                }
            } catch (Exception $e) {
                $viewModel->set("error", $e->getMessage());

                // log Fehler Datail File
                $this->log->LogMessage('Error during insert a comment (FileID: ' . $comment->UserFile_UserFileId . ') by UserID ' . $_SESSION["userid"] . '.', LOGGER_ERROR);

            }
            #endregion

            //no error
            if (!$viewModel->exists("error")) {

                $this->log->LogMessage('File (ID: ' . $comment->UserFile_UserFileId . ') has been commented by UserID ' . $_SESSION["userid"] , LOGGER_INFO);

                RedirectAction("files", "details", $id);
                return;
            }
        }

        $this->view->output($viewModel);

    }

    protected function download()
    {

        ConfirmUserIsLoggedOn();

        $id = $this->urlValues['id'];

        if (!isset($id) || empty($id)) {
            $_SESSION['error'] = "Something went wrong - please try again!";

            // log Fehler Download File
            $this->log->LogMessage('Error during download a file by UserID ' . $_SESSION["userid"] . '. No FileID.', LOGGER_ERROR);

            RedirectAction("files", $_SESSION["fileview"]);
            return;
        }

        try
        {
            $fileRepo = new FileRepository($this->db);

            $file = $fileRepo->GetFile($id);

            //file is private and not my file
            if($file->IsPrivate && $file->UserId !== $_SESSION['userid'])
            {
                $_SESSION['error'] = "File couldn't be downloaded!";
                $this->log->LogMessage('UserID ' . $_SESSION["userid"] . ' tried to download private File  (ID: ' . $id . ') - action stopped.', LOGGER_WARNING);
                RedirectAction("files", $_SESSION["fileview"]);
            }

            if (!$fileRepo->DownloadFile($id))
            {
                $_SESSION['error'] = "File couldn't be downloaded - please try again!";
            } else
            {
                $this->log->LogMessage('File (ID: ' . $id . ') has been downloaded by UserID ' . $_SESSION["userid"] , LOGGER_INFO);
            }
            return;

        } catch (Exception $e)
        {
            $_SESSION['error'] = $e->getMessage();
            // log Fehler Download File
            $this->log->LogMessage('Error during download a file by UserID ' . $_SESSION["userid"] . '.', LOGGER_ERROR);

        }

        RedirectAction("files", $_SESSION["fileview"]);
    }

    private function validateCommentData(ViewModel &$viewModel)
    {
        $ok = true;

        if (!isset($_POST["Message"]) || $_POST["Message"] == '')
        {
            $viewModel->setFieldError("Message", "Message has to be entered!");
            $ok = false;
        }

        return $ok;
    }

}

?>
