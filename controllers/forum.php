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

        $this->view->output($this->model->index());
    }

    protected function newThread()
    {
        $viewModel = $this->model->newThread();

        ConfirmUserIsLoggedOn();

        $userrepo = new UserRepository();
        $rolerepo = new RoleRepository();

        $user = $userrepo->GetUser(intval($_SESSION['userid']));
        $user->Role = $rolerepo->GetRole($user->RoleId);

        if(!$user->Role->WriteForum)
        {
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
            $thread->Title = $_POST["Title"];
            $thread->Description = $_POST["Description"];
            $thread->IsDeleted = 0;
            $thread->UserId = $_SESSION['userid'];
            #endregion

            #region # Insert File
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
                // TODO: redirect to new created forum thread -> create action for existing threads
                RedirectAction("forum", "index");
                return;
            }
        }

        $this->view->output($viewModel);
    }

    private function validateThreadData(ViewModel &$viewModel)
    {
        $ok = true;

        if(!isset($_POST["Title"]) || $_POST["Title"] == ''){
            $viewModel->setFieldError("Title", "Title has to be entered!");
            $ok = false;
        }

        if(!isset($_POST["Description"]) || $_POST["Description"] == ''){
            $viewModel->setFieldError("Description", "Description has to be entered!");
            $ok = false;
        }

        return $ok;
    }
}

?>
