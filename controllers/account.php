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

            #region # Create User obj
            try
            {
                $user = new User();
                $viewModel->set("model", $user);

                $user->Username = $_POST["Username"];

                $user->Description = $_POST["Description"];
                $user->EMail = $_POST["EMail"];

                $user->Firstname = $_POST["Firstname"];
                $user->Lastname = $_POST["Lastname"];

                $user->Description = ($_POST["Description"]);
                $user->Password = $_POST["Password"];

                try
                {
                    $user->BirthDate = ParseDate($_POST["BirthDate"]);
                }
                catch (Exception $iex)
                {
                    $viewModel->setFieldError("BirthDate", $iex->getMessage());
                }

            }
            catch (Exception $ex){;}

            #endregion

            if(!$this->validateRegisterData($viewModel)) {
                $this->view->output($viewModel);
                return;
            }

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

    protected function login()
    {
        global $log;

        $viewModel = $this->model->login();

        if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST')
        {
            //redirects to error/unexpectedError if wrong
            CheckAntiCSRFToken();

            $username = NULL;
            $password = NULL;

            if(!isset($_POST['Username']) || $_POST['Username'] == '')
            {
                $viewModel->set("error", "Please provide your credentials.");
                $this->view->output($viewModel);
                return;
            }

            if(isset($_POST['Username']))
            {
                $username = $_POST['Username'];
            }

            if(isset($_POST['Password']))
            {
                $password = $_POST['Password'];
            }

            $viewModel->set("model", $username);

            $userrepo = new UserRepository();

            try {
                $result = $userrepo->CheckUserCredentials($username, $password);

                if ($userrepo->CheckUserLocked($username)) {
                    $errorMessage = "The Account '" . $username . "' is locked - please try again later!";

                    $viewModel->set("error", $errorMessage);

                    $log->LogMessage($errorMessage, LOGGER_INFO);

                    $this->view->output($viewModel);
                    return;
                }

                if ($result == NULL)
                {
                    $viewModel->set("error", "Login was not successfully! Username/password combination is incorrect.");

                    if ($userrepo->UpdateAccessFailedCounter($username)) {
                        $viewModel->set("error", "The Account '" . $username . "' is locked for 10 minutes - please try again later!");
                    }

                    $this->view->output($viewModel);
                    return;
                }

                //$result contains userid
                $_SESSION["userid"] = $result;

                if($_POST['RememberMe'])
                {
                    $authRepo = new AuthTokenRepository();
                    $authToken = $authRepo->CreateAuthToken(intval($_SESSION["userid"]));

                    $month = time() + 3600 * 24 * 31; // a month
                    setcookie('SecureRememberMe', $authToken->Selector . ':' . $authToken->Token , $month);
                }
                else
                {
                    if(isset($_COOKIE['SecureRememberMe']))
                    {
                        setcookie('SecureRememberMe', 'gone', time()-100);
                    }
                }

            }
            catch (Exception $ex)
            {
                $viewModel->set("error",$ex->getMessage());
                $this->view->output($viewModel);
                return;
            }

            RedirectAction("home", "index");
        }

        $this->view->output($viewModel);
    }

    protected function logoff()
    {
        $this->view->output(NULL, '');
    }

    protected function editprofile()
    {
        $viewModel = $this->model->editprofile();

        if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST')
        {
            //redirects to error/unexpectedError if wrong
            CheckAntiCSRFToken();

            #region # Create User obj
            try
            {
                $user = new User();
                $viewModel->set("model", $user);

                $user->Username = $_POST["Username"];

                $user->Description = $_POST["Description"];
                $user->EMail = $_POST["EMail"];

                $user->Firstname = $_POST["Firstname"];
                $user->Lastname = $_POST["Lastname"];

                $user->Description = ($_POST["Description"]);
                $user->Password = $_POST["Password"];

                try
                {
                    $user->BirthDate = ParseDate($_POST["BirthDate"]);
                }
                catch (Exception $iex)
                {
                    $viewModel->setFieldError("BirthDate", $iex->getMessage());
                }

            }
            catch (Exception $ex){;}

            #endregion

            if(!$this->validateRegisterData($viewModel, false)) {
                $this->view->output($viewModel);
                return;
            }

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

    private function validateRegisterData(ViewModel &$viewModel, $checkTerms = true)
    {
        $ok = true;

        if (!isset($_POST["Password"])) {
            $viewModel->setFieldError("Password", "Password has to be entered!");
            $ok = false;
        }

        if (strlen($_POST["Password"]) < 5 || !preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z.!@#$%\/]{8,12}$/', $_POST["Password"])) {
            $viewModel->setFieldError("Password", "Password has to consist of at least 5 letters and has to contain uppercase letter, special char and digits.");
            $ok = false;
        }

        if (!($_POST["Password"] == $_POST["PasswordConfirm"])) {
            $viewModel->setFieldError("Password", "Password and Password Confirm are not equal!");
            $ok = false;
        }

        if ($checkTerms)
        {
            if (!isset($_POST["CheckTerms"]) || $_POST["CheckTerms"] == false) {
                $viewModel->setFieldError("CheckTerms", "Please accept the terms!");
                $ok = false;
            }
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

        if (! VerifyDate($_POST["BirthDate"])) {
            $viewModel->setFieldError("BirthDate", "BirthDate has the wrong format!");
            $ok = false;
        }

        $userrepo = new UserRepository();

        if($userrepo->IsUsernameUsed($_POST["Username"]))
        {
            $viewModel->setFieldError("Username", "Username is already used!");
            $ok = false;
        }

        return $ok;
    }

}

?>