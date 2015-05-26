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

                //Reset Lockout (if user was locked out)
                $userrepo->ResetUserLockout($result);

                //Reset Lockout (if user was locked out)
                $userrepo->ResetUserDeactivated($result);

                //$result contains userid
                $_SESSION["userid"] = $result;

                //reset access failed counter
                $userrepo->ResetAccessFailedCounter($result);


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
        else
        {
            $userid = intval($_SESSION['userid']);
        }

        $viewModel = $this->model->showprofile($userid);

        $this->view->output($viewModel);

    }

    protected function editprofile()
    {
        ConfirmUserIsLoggedOn();

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

                $user->Description = PrepareHtml($_POST["Description"]);
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
                /*if(isset($_POST['Picture']))
                {
                    $filelink = HandleFileUpload("Picture", "/upload/UserPictures");
                    $user->PictureLink = $filelink;
                }*/

                $roleRepo = new RoleRepository();
                $roleId = $roleRepo->GetRoleId($_POST["Role"]);

                $user->RoleId = $roleId;

                $userrepo = new UserRepository();
                $result = $userrepo->UpdateUser($user);

                if($result == false)
                {
                    $viewModel->set("error", "Something went wrong during your changes - please try again!");
                }

                RedirectAction("account", "manage");
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
        ConfirmUserIsLoggedOn();

        $userrepo = new UserRepository();

        $userrepo->SetUserDeactivated(intval($_SESSION['userid']));

        RedirectAction('account', 'logoff');
    }

    protected function resetpassword(){

        //TODO send mail to user with confirmlink - md5({userid}_{currentpasswordhash})
        /* //Create a new PHPMailer instance
        $mail = new PHPMailer;

        $mail->isSMTP();  // telling the class to use SMTP
        $mail->SMTPAuth   = true;                // enable SMTP authentication
        $mail->Port       = 26;                  // set the SMTP port
        $mail->Host       = "mail.yourhost.com"; // SMTP server
        $mail->Username   = "name@yourhost.com"; // SMTP account username
        $mail->Password   = "your password";     // SMTP account password

        //Set who the message is to be sent from
        $mail->setFrom('secure@securefile.azurewebsites.net', 'Secure Team');

        //Set who the message is to be sent to
        $mail->addAddress(, 'John Doe');

        //Set the subject line
        $mail->Subject = 'PHPMailer mail() test';
        //Read an HTML message body from an external file, convert referenced images to embedded,
        //convert HTML into a basic plain-text alternative body
        $mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
        //Replace the plain text body with one created manually
        $mail->AltBody = 'This is a plain-text message body';
        //Attach an image file
        $mail->addAttachment('images/phpmailer_mini.png');

        //send the message, check for errors

        if (!$mail->send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
            echo "Message sent!";
        }*/

        $this->view->output($this->model->resetpassword());
    }

    protected function confirmresetpassword()
    {
        //TODO update user password
        $this->view->output($this->model->confirmresetpassword());
    }

    protected function changepassword()
    {
        ConfirmUserIsLoggedOn();

        $viewModel = $this->model->changepassword();

        if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST')
        {
            //TODO change user password
        }

        $this->view->output($viewModel);
    }

    protected function changeuserpicture()
    {
        ConfirmUserIsLoggedOn();

        $viewModel = $this->model->changeuserpicture();

        if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST')
        {
            //TODO update user picture
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