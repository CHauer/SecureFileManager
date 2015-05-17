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
}

?>
