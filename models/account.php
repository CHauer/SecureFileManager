<?php
/* 
 * Project: Nathan MVC
 * File: /models/home.php
 * Purpose: model for the home controller.
 * Author: Nathan Davison
 */

class AccountModel extends BaseModel
{
    //data passed to the home index view
    public function register()
    {   
        $this->viewModel->set("pageTitle","Registration");
        return $this->viewModel;
    }

    public function login()
    {
        $this->viewModel->set("pageTitle","Login");
        return $this->viewModel;
    }

    public function showprofile($userid)
    {
        $userrepo = new UserRepository();

        $user = $userrepo->GetUser(intval($userid));
        $this->viewModel->set("model", $user);
        $this->viewModel->set("pageTitle",'Profile ' . $user->Username);

        return $this->viewModel;
    }

    public function editprofile()
    {
        $this->viewModel->set("pageTitle","Edit Profile");

        $userrepo = new UserRepository();
        $rolerepo = new RoleRepository();

        $user = $userrepo->GetUser(intval($_SESSION['userid']));
        $user->Role = $rolerepo->GetRole($user->RoleId);

        $this->viewModel->set("model", $user);
        return $this->viewModel;
    }

    public function manage()
    {
        $this->viewModel->set("pageTitle","Account Management");
        return $this->viewModel;
    }

    public function changeuserpicture()
    {
        $this->viewModel->set("pageTitle","Change Profile Picture");
        return $this->viewModel;
    }

    public function changepassword()
    {
        $this->viewModel->set("pageTitle","Change Password");
        return $this->viewModel;
    }

    public function resetpassword()
    {
        $this->viewModel->set("pageTitle","Reset your password");
        return $this->viewModel;
    }

    public function confirmresetpassword()
    {
        $this->viewModel->set("pageTitle","Confirm");
        return $this->viewModel;
    }
}

?>
