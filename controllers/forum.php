<?php
/* 
 * Project: Nathan MVC
 * File: /controllers/home.php
 * Purpose: controller for the home of the app.
 * Author: Nathan Davison
 */

class ForumController extends BaseController
{
    //add to the parent constructor
    public function __construct($action, $urlValues) {
        parent::__construct($action, $urlValues);

        //create the model object
        require("models/forum.php");
        $this->model = new ForumModel();
    }

    //default method
    protected function index()
    {
        ConfirmUserIsLoggedOn();
        $viewModel = $this->model->index();

        $forumrepo = new ForumRepository();
        $threads = $forumrepo->GetNotDeletedThreads();

        $viewModel->set("threads", $threads);

        $this->view->output($viewModel);
    }

    protected function thread()
    {
        ConfirmUserIsLoggedOn();
        $forumrepo = new ForumRepository();

        $id = $this->urlValues['id'];

        if (!isset($id) || empty($id)) {
            $_SESSION["redirectError"] = "No Thread ID specified";
            RedirectAction("forum", "index");
            return;
        }

        try
        {
            $viewModel = $this->model->thread($id);
        }
        catch(Exception $e) {
            $_SESSION["redirectError"] = "The requested thread doesn't exist.";
            RedirectAction("forum", "index");
            return;
        }

        if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST')
        {
            // check for permissions to write in forum
            $userrepo = new UserRepository();
            $rolerepo = new RoleRepository();

            $user = $userrepo->GetUser(intval($_SESSION['userid']));
            $user->Role = $rolerepo->GetRole($user->RoleId);

            if (!$user->Role->WriteForum) {
                $_SESSION['redirectError'] = "You don't have permissions to write a comment.";
                RedirectAction("forum", "thread", $id);
                return;
            }

            // Post entry
            if(!$this->validateEntryData($viewModel))
            {
                $this->view->output($viewModel);
                return;
            }

            $entry = new Entry();

            $entry->Message = PrepareHtml($_POST["Message"]);
            $entry->ForumThreadId = $id;
            $entry->UserId = $_SESSION["userid"];

            $entryId = $forumrepo->PostEntryToThread($entry);

            if($entryId == false)
            {
                $viewModel->set("error", "Something went wrong - please try again!");
            }
            else
            {
                // reload viewmodel since data has changed (new entry)
                $viewModel = $this->model->thread($id);

                global $log;

                // log creation of entry
                $log->LogMessage("Entry " . $entry . " created by user with ID " . $_SESSION["userid"], LOGGER_INFO);

                $_SESSION["redirectSuccess"] = "Answer successfully created.";
            }
        }

        $this->view->output($viewModel);
    }

    protected function deleteEntry()
    {
        ConfirmUserIsLoggedOn();

        $id = $this->urlValues['id'];

        if (!isset($id) || empty($id))
        {
            $_SESSION["redirectError"] = "No answer specified!";
            RedirectAction("forum", "index");
            return;
        }
        else
        {
            $forumrepo = new ForumRepository();

            try
            {
                $entry = $forumrepo->GetEntryById($id);
            }
            catch(InvalidArgumentException $e)
            {
                $_SESSION["redirectError"] = $e->getMessage();
                RedirectAction("forum", "index");
                return;
            }

            if(IsEntryOwner($id, $_SESSION["userid"]))
            {
                try
                {
                    $forumrepo->DeleteEntryById($id);

                    global $log;

                    // log deletion of thread
                    $log->LogMessage("Entry " . $id . " deleted by user with ID " . $_SESSION["userid"], LOGGER_INFO);

                    $_SESSION["redirectSuccess"] = "Answer successfully deleted.";
                }
                catch (Exception $e)
                {
                    $_SESSION["redirectError"] = "Something went wrong. Please try again.";
                }
            }
            else
            {
                $_SESSION["redirectError"] = "You are not allowed to delete this answer.";
            }
            RedirectAction("forum", "thread", $entry->ForumThreadId);
            return;
        }
    }

    protected function newThread()
    {
        $viewModel = $this->model->newThread();

        ConfirmUserIsLoggedOn();

        $userrepo = new UserRepository();
        $rolerepo = new RoleRepository();

        $user = $userrepo->GetUser(intval($_SESSION['userid']));
        $user->Role = $rolerepo->GetRole($user->RoleId);


        if (!$user->Role->WriteForum) {
            $_SESSION['redirectError'] = "You don't have permissions to start a new thread.";
            RedirectAction("forum", "index");
            return;
        }

        if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST')
        {
            if(!$this->validateThreadData($viewModel)) {
                $this->view->output($viewModel);
                return;
            }

            #region # Create Thread
            $thread = new ForumThread();
            $thread->Title = htmlentities($_POST["Title"]);
            $thread->Description = htmlentities($_POST["Description"]);
            $thread->IsDeleted = 0;
            $thread->UserId = $_SESSION['userid'];
            #endregion

            #region # Insert Thread
            try
            {
                $forumRepository = new ForumRepository();
                $threadId = $forumRepository->InsertThread($thread);

                if($threadId == false)
                {
                    $viewModel->set("error", "Something went wrong - please try again!");
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
                global $log;

                // log creation of thread
                $log->LogMessage("Thread " . $threadId . " created by user with ID " . $_SESSION["userid"], LOGGER_INFO);

                $_SESSION["redirectSuccess"] = "Thread successfully created!";
                RedirectAction("forum", "thread", $threadId);
                return;
            }
        }

        $this->view->output($viewModel);
    }

    protected function delete() {
        ConfirmUserIsLoggedOn();

        $id = $this->urlValues['id'];

        if (!isset($id) || empty($id))
        {
            $_SESSION["redirectError"] = "No thread id specified";
            RedirectAction("forum", "index");
            return;
        }

        try
        {
            $viewModel = $this->model->thread($id);
        }
        catch(Exception $e)
        {
            $_SESSION["redirectError"] = "The requested thread doesn't exist.";
            RedirectAction("forum", "index");
            return;
        }

        $forumrepo = new ForumRepository();
        try {
            if (IsThreadOwner($id, $_SESSION["userid"])) {
                $success = $forumrepo->DeleteById($id);

                if ($success) {
                    global $log;

                    // log deletion of thread
                    $log->LogMessage("Thread " . $id . " deleted by user with ID " . $_SESSION["userid"], LOGGER_INFO);

                    $_SESSION["redirectSuccess"] = "Thread successfully deleted.";
                } else {
                    $_SESSION["redirectError"] = "Thread couldn't be deleted. Please try again.";
                }
            } else {
                $_SESSION["redirectError"] = "You are not allowed to delete this thread.";
            }
        } catch(InvalidArgumentException $e) {
            $_SESSION["redirectError"] = $e->getMessage();
        }
        RedirectAction("forum", "index");
    }

    private function validateThreadData(ViewModel &$viewModel)
    {
        $ok = true;

        if (!isset($_POST["Title"]) || $_POST["Title"] == '') {
            $viewModel->setFieldError("Title", "Title has to be entered!");
            $ok = false;
        }

        if (!isset($_POST["Description"]) || $_POST["Description"] == '') {
            $viewModel->setFieldError("Description", "Description has to be entered!");
            $ok = false;
        }

        return $ok;
    }

    private function validateEntryData(ViewModel &$viewModel)
    {
        $ok = true;

        if (!isset($_POST["Message"]) || $_POST["Message"] == '') {
            $viewModel->setFieldError("Message", "Message has to be entered!");
            $ok = false;
        }

        return $ok;
    }
}

?>
