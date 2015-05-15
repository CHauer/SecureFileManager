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

        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            if(!$_POST["Password"] == $_POST["PasswordConfirm"]) {
                $viewModel->set("error", "Password and Password Confirm are not equal!");
                $this->view->output($viewModel);
                return;
            }
            if(!$_POST["CheckTerms"] == true) {
                $viewModel->set("error", "Please accept the terms!");
                $this->view->output($viewModel);
                return;
            }

            if(!$_POST["CheckTerms"] == true) {
                $viewModel->set("error", "Pleae accept the terms!");
                $this->view->output($viewModel);
                return;
            }

            if(!($_POST["Role"] == "Standard" || $_POST["Role"] == "Premium")){
                $viewModel->set("error", "This role is not valid!");
                $this->view->output($viewModel);
                return;
            }

            $filelink = HandleFileUpload("Picture", "/upload/UserPictures");

            $user = new User();
            $user->Username = $_POST["Username"];
            $user->BirthDate = $_POST["Birthdate"];

            $user->Description = $_POST["Description"];
            $user->EMail = $_POST["EMail"];

            $user->Firstname = $_POST["Firstname"];
            $user->Lastname = $_POST["Lastname"];

            $user->Password = md5($_POST["Password"]);
            $user->PictureLink = $filelink;

            try {
                $roleRepo = new RoleRepository();
                $roleId = $roleRepo->GetRoleId($_POST["Role"]);

                $user->RoleId = $roleId;

                $userrepo = new UserRepository();
                $userrepo->InsertUser($user);
            }catch(Exception $e){
                $viewModel->set("error", $e->getMessage());
            }

            if(!$viewModel->exists(("error"))) {
                RedirectAction("home", "index");
                return;
            }
        }

        $this->view->output($viewModel);

    }

    protected function login()
    {
        if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST')
        {
            Redirect("home/index");
        }
        else
        {
            $this->view->output($this->model->login());
        }
    }

    protected function logoff()
    {
        $this->view->output(NULL);
    }

}

?>