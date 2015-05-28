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
        global $log;

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

                $user->Description = PrepareHtml($_POST["Description"]);
                $user->EMail = $_POST["EMail"];

                $user->Firstname = $_POST["Firstname"];
                $user->Lastname = $_POST["Lastname"];

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
                $filelink = NULL;

                try
                {
                    $filelink = HandlePictureUpload("Picture", "upload/UserPictures");
                }
                catch (Exception $ex)
                {
                    $filelink = NULL;
                }

                if($filelink != NULL)
                {
                    $user->PictureLink = '/' . $filelink;
                }
                else
                {
                    $user->PictureLink = '/assets/img/standard_user.jpg';
                }

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
                // log Kontoerstellung
                $log->LogMessage('User ' . $user->Username . ' created a new account.', LOGGER_INFO);

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

                if ($userrepo->CheckUserLocked($username))
                {
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

                //Reset Lockout (if user was locked out)
                $userrepo->ResetUserLockout($result);

                //Reset Lockout (if user was locked out)
                if($userrepo->ResetUserDeactivated($result))
                {
                    //log Konto-Aktivierung
                    $log->LogMessage('User ' . $username . ' has re-activated his profile.', LOGGER_INFO);
                }

                //$result contains userid
                $_SESSION["userid"] = $result;

                //reset access failed counter
                $userrepo->ResetAccessFailedCounter($result);

                //log Login
                $log->LogMessage('User ' . $username . ' has logged in successfully.', LOGGER_INFO);

                if($_POST['RememberMe'])
                {
                    $authRepo = new AuthTokenRepository();
                    $authToken = $authRepo->CreateAuthToken(intval($_SESSION["userid"]));

                    $month = time() + 3600 * 24 * 31; // a month
                    setcookie('SecureRememberMe', $authToken->Selector . ':' . $authToken->Token , $month, '/');
                }
                else
                {
                    if(isset($_COOKIE['SecureRememberMe']))
                    {
                        setcookie('SecureRememberMe', 'gone', time()-100, '/');
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
        global $log;

        $viewModel = $this->model->manage();

        //log logout
        $log->LogMessage('User ' . $viewModel->get('username') . ' has logged out.', LOGGER_INFO);

        $this->view->output(NULL, '');
    }

    protected function showprofile()
    {
        ConfirmUserIsLoggedOn();
        $userid = null;

        if(isset($this->urlValues['id']))
        {
            $userid = intval($this->urlValues['id']);
        }

        if ($userid == null || $userid == false)
        {
            $userid = intval($_SESSION['userid']);
        }

        $viewModel = $this->model->showprofile($userid);

        $this->view->output($viewModel);

    }

    protected function editprofile()
    {
        global $log;

        ConfirmUserIsLoggedOn();

        $viewModel = $this->model->editprofile();

        if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST')
        {
            $currentUsername = $viewModel->get("username");

            //redirects to error/unexpectedError if wrong
            CheckAntiCSRFToken();

            #region # Create User obj
            try
            {
                $user = new User();
                $viewModel->set("model", $user);

                $user->Username = $_POST["Username"];

                $user->Description = PrepareHtml($_POST["Description"]);
                $user->EMail = $_POST["EMail"];

                $user->Firstname = $_POST["Firstname"];
                $user->Lastname = $_POST["Lastname"];

                $user->Description = PrepareHtml($_POST["Description"]);

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

            if(!$this->validateEditData($viewModel, $currentUsername)) {
                $this->view->output($viewModel);
                return;
            }

            try
            {
                $roleRepo = new RoleRepository();
                $roleId = $roleRepo->GetRoleId($_POST["Role"]);

                $user->RoleId = $roleId;

                $userrepo = new UserRepository();
                $result = $userrepo->UpdateUser($user);

                if($result == false)
                {
                    $viewModel->set("error", "Something went wrong during your changes - please try again!");
                }else
                {

                    //log Kontoänderungen
                    $log->LogMessage('User ' . $viewModel->get('username') . ' has changed his profile.', LOGGER_INFO);

                    RedirectAction("account", "manage");
                }
            }
            catch(Exception $e)
            {
                $viewModel->set("error", $e->getMessage());
            }
        }

        $this->view->output($viewModel);

    }

    protected function manage()
    {
        ConfirmUserIsLoggedOn();

        $this->view->output($this->model->manage());
    }

    protected function deactivate()
    {
        global $log;
        ConfirmUserIsLoggedOn();

        $userrepo = new UserRepository();

        $userrepo->SetUserDeactivated(intval($_SESSION['userid']));
        $user = $userrepo->GetUser(intval($_SESSION['userid']));

        //log Konto-Deaktivierung
        $log->LogMessage('User ' . $user->Username . ' has deactivated his profile.', LOGGER_INFO);

        RedirectAction('account', 'logoff');
    }

    protected function resetpassword()
    {

        if(IsUserLoggedOn())
        {
            RedirectAction("home", "index");
        }

        $viewModel = $this->model->resetpassword();

        if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST')
        {
            if(!isset($_POST["EMail"]) || $_POST["EMail"] == ''){
                $viewModel->setFieldError("EMail", "EMail has to be entered!");
                $this->view->output($viewModel);
                return;
            }

            //send mail to user with resetLink
            $userrepo = new UserRepository();

            $resetLink = $userrepo->CheckEMailExists($_POST['EMail']);

            if($resetLink != NULL)
            {
                $link = $_SERVER[HTTP_HOST] . "/account/confirmresetpassword?reset=" . $resetLink;

                $sendgrid = new SendGrid('SecureFH', 'qwerASDF12');
                $email = new SendGrid\Email();

                $email->addTo($_POST['EMail']);
                $email->setFrom("reset-noreply@secure.net");
                $email->setSubject("Secure - Password Reset");
                $email->setHtml("
                    Hi<br/>
                    Here is your password reset Link: <a href='" . $link . "'> click here</a>.<br/>
                    Best regards");
                $sendgrid->send($email);

                RedirectAction("home", "index");
            }else{
                $viewModel->set("error", "Something went wrong - please try again!");
            }

        }

        $this->view->output($viewModel);
    }

    protected function confirmresetpassword()
    {
        if(IsUserLoggedOn())
        {
            RedirectAction("home", "index");
        }

        global $log;

        $viewModel = $this->model->confirmresetpassword();

        if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST')
        {
            $viewModel->set("reset", $_POST["reset"]);

            if(!$this->validatePassword($viewModel)) {
                $this->view->output($viewModel);
                return;
            }

            try
            {
                //change user password
                $reset = $_POST["reset"];
                $username = $_POST['Username'];
                $email = $_POST['EMail'];
                $newPassword = $_POST['NewPassword'];

                $userrepo = new UserRepository();

                if ($userrepo->ResetPassword($reset, $newPassword,  $email, $username) !== false) {

                    //log Kontoänderungen
                    $log->LogMessage('User ' . $username . ' has reseted his password.', LOGGER_INFO);

                    RedirectAction("home", "index");
                }
                else
                {
                    $viewModel->set('error', 'Your password reset was not successfully!');
                }
            }
            catch(Exception $ex)
            {
                $viewModel->set('error', 'Your password reset was not successfully!');
            }
        }
        else
        {
            if(isset($_GET["reset"]))
            {
                $viewModel->set("reset", $_GET["reset"]);
            }
            else
            {
                RedirectAction("home", "index");
            }
        }
        $this->view->output($viewModel);
    }

    protected function changepassword()
    {
        global $log;
        ConfirmUserIsLoggedOn();

        $viewModel = $this->model->changepassword();

        if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST')
        {
            if(!$this->validatePassword($viewModel)) {
                $this->view->output($viewModel);
                return;
            }

            //change user password
            $username = $viewModel->get('username');

            $userrepo = new UserRepository();

            if($userrepo->CheckUserCredentials($username, $_POST['CurrentPassword']) !== false)
            {
                $userRepo = new UserRepository();
                $result = $userRepo->UpdateUserPassword($viewModel->get('userid'), $_POST['CurrentPassword'],  $_POST['NewPassword']);

                if($result == false )
                {
                    $viewModel->set('error', 'Something went wrong during your password change - please try again!');
                }
                else
                {
                    //log Kontoänderungen
                    $log->LogMessage('User ' . $viewModel->get('username') . ' has changed his password.', LOGGER_INFO);

                    RedirectAction("account", "manage");
                }
            }
            else
            {
                $viewModel->setFieldError('CurrentPassword', 'Your current entered password is not correct!');
            }

        }
        $this->view->output($viewModel);
    }

    protected function changeuserpicture()
    {
        global $log;
        ConfirmUserIsLoggedOn();

        $viewModel = $this->model->changeuserpicture();

        if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST')
        {
            //update user picture
            if(isset($_FILES['Picture']))
            {
                try
                {
                    $filelink = HandlePictureUpload("Picture", "upload/UserPictures");

                    if($filelink != NULL && $filelink != false)
                    {
                        $userRepo = new UserRepository();
                        $result = $userRepo->UpdateUserPicture($viewModel->get('userid'), '/'.  $filelink);

                        if ($result == false)
                        {
                            $viewModel->set('error', 'Something went wrong during your file upload - please try again!');
                        }
                        else
                        {
                            //log Kontoänderungen
                            $log->LogMessage('User ' . $viewModel->get('username') . ' has changed his profile picture.', LOGGER_INFO);

                            RedirectAction("account", "manage");
                        }
                    }
                }
                catch(Exception $ex)
                {
                    $viewModel->setFieldError("Picture", $ex->getMessage());
                }
            }
        }

        $this->view->output($viewModel);
    }

    private function validatePassword(ViewModel &$viewModel)
    {
        $ok = true;

        if (!isset($_POST["NewPassword"])) {
            $viewModel->setFieldError("NewPassword", "Password has to be entered!");
            $ok = false;
        }

        if (strlen($_POST["NewPassword"]) < 5 || !preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z.!@#$%\/]{8,12}$/', $_POST["Password"])) {
            $viewModel->setFieldError("NewPassword", "Password has to consist of at least 5 letters and has to contain uppercase letter, special char and digits.");
            $ok = false;
        }

        if (!($_POST["NewPassword"] == $_POST["PasswordConfirm"])) {
            $viewModel->setFieldError("NewPassword", "Password and Password Confirm are not equal!");
            $ok = false;
        }

        return $ok;
    }

    private function validateRegisterData(ViewModel &$viewModel)
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

        if (!isset($_POST["CheckTerms"]) || $_POST["CheckTerms"] == false) {
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

        if (! VerifyDate($_POST["BirthDate"])) {
            $viewModel->setFieldError("BirthDate", "BirthDate has the wrong format!");
            $ok = false;
        }

        $userrepo = new UserRepository();

        if ($userrepo->IsUsernameUsed($_POST["Username"])) {
            $viewModel->setFieldError("Username", "Username is already used!");
            $ok = false;
        }

        return $ok;
    }

    private function validateEditData(ViewModel &$viewModel, $currentUsername)
    {
        $ok = true;

        if(!IsUserAdministrator())
        {
            if (!($_POST["Role"] == "Standard" || $_POST["Role"] == "Premium")) {
                $viewModel->set("error", "This role is not valid!");
                $ok = false;
            }
        }
        else
        {
            if (!($_POST["Role"] == "Standard" || $_POST["Role"] == "Premium" ||  $_POST["Role"] == "Administrator" ))
            {
                $viewModel->set("error", "This role is not valid!");
                $ok = false;
            }
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

        if ($currentUsername !== $_POST["Username"])
        {
            if ($userrepo->IsUsernameUsed($_POST["Username"])) {
                $viewModel->setFieldError("Username", "Username is already used!");
                $ok = false;
            }
        }

        return $ok;
    }

}

?>