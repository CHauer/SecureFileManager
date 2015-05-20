<?php
/**
 * Created by PhpStorm.
 * User: Christoph
 * Date: 14.05.2015
 * Time: 13:56
 */

class AccountController extends BaseController
{
    //add to the parent constructor
    public function __construct($action, $urlValues) {
        parent::__construct($action, $urlValues);

        //create the model object
        require("models/account.php");
        $this->model = new AccountModel();
    }

    protected function register()
    {
        $viewModel = $this->model->register();

        if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST')
        {
            //redirects to error/unexpectedError if wrong
            CheckAntiCSRFToken();

            if(!$this->validateRegisterData($viewModel)) {
                $this->view->output($viewModel);
                return;
            }

            #region # Create User obj

            $user = new User();
            $user->Username = $_POST["Username"];
            $user->BirthDate = ParseDate($_POST["Birthdate"]);

            $user->Description = $_POST["Description"];
            $user->EMail = $_POST["EMail"];

            $user->Firstname = $_POST["Firstname"];
            $user->Lastname = $_POST["Lastname"];

            $user->Description = ($_POST["Description"]);
            $user->Password = md5($_POST["Password"]);

            #endregion

            try
            {
                $filelink = HandleFileUpload("Picture", "/upload/UserPictures");

                $user->PictureLink = $filelink;

                $roleRepo = new RoleRepository();
                $roleId = $roleRepo->GetRoleId($_POST["Role"]);

                $user->RoleId = $roleId;

                $userrepo = new UserRepository();
                $userid = $userrepo->InsertUser($user);

                if($userid == false)
                {
                    $viewModel->set("error", "Something went wrong during your registration - please try again!");
                }
            }
            catch(Exception $e)
            {
                $viewModel->set("error", $e->getMessage());
            }

            //no error
            if(!$viewModel->exists("error"))
            {
                //contains inserted userid - user id logged in
                $_SESSION["userid"] = $userid;

                RedirectAction("home", "index");
                return;
            }
        }

        $this->view->output($viewModel);

    }

    private function validateRegisterData(ViewModel &$viewModel)
    {
        $ok = true;

        if(!$_POST["Password"] == $_POST["PasswordConfirm"]) {
            $viewModel->set("error", "Password and Password Confirm are not equal!");
            $ok = false;
        }

        if(!isset($_POST["CheckTerms"]) || $_POST["CheckTerms"] == false) {
            $viewModel->setFieldError("CheckTerms", "Please accept the terms!");
            $ok = false;
        }

        if(!($_POST["Role"] == "Standard" || $_POST["Role"] == "Premium")){
            $viewModel->set("error", "This role is not valid!");
            $ok = false;
        }

        if(!isset($_POST["Username"]) || $_POST["Username"] == ''){
            $viewModel->setFieldError("Username", "Username has to be entered!");
            $ok = false;
        }

        if(!isset($_POST["EMail"]) || $_POST["EMail"] == ''){
            $viewModel->setFieldError("EMail", "EMail has to be entered!");
            $ok = false;
        }

        if (! filter_var($_POST["EMail"], FILTER_VALIDATE_EMAIL) ) {
            $viewModel->setFieldError("EMail", "EMail is invalid!");
            $ok = false;
        }

        if (! VerifyDate($_POST["Birthdate"])) {
            $viewModel->setFieldError("Birthdate", "Birthdate has the wrong format!");
            $ok = false;
        }

        return $ok;
    }

    protected function login()
    {
        $viewModel = $this->model->login();

        if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST')
        {
            //redirects to error/unexpectedError if wrong
            CheckAntiCSRFToken();

            $username = $_POST['username'];
            $password = $_POST['password'];

            $userrepo = new UserRepository();
            $result = $userrepo->CheckUserCredentials($username, $password);

            if($userrepo->CheckUserLocked($username))
            {
                $viewModel->set("error", "The Account '" . $username. "' is locked - please try again later!");
                $this->view->output($viewModel);
                return;
            }

            if($result == NULL)
            {
                $viewModel->set("error", "Login was not successfully! Username/password combination is incorrect.");
                if($userrepo->UpdateAccessFailedCounter($username))
                {
                    $viewModel->set("error", "The Account '" . $username. "' is locked for 10 minutes - please try again later!");
                }
            }

            //$result contains userid
            $_SESSION["userid"] = $result;

            Redirect("home/index");
        }

        $this->view->output($viewModel);
    }

    protected function logoff()
    {
        $this->view->output(NULL);
    }

}

?>