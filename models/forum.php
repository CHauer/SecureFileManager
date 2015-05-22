<?php
/* 
 * Project: Nathan MVC
 * File: /models/home.php
 * Purpose: model for the home controller.
 * Author: Nathan Davison
 */

class ForumModel extends BaseModel
{
    //data passed to the home index view
    public function index()
    {
        $this->viewModel->set("pageTitle","Forum");
        return $this->viewModel;
    }

    public function newThread()
    {
        $this->viewModel->set("pageTitle", "Start a new thread");
        return $this->viewModel;
    }
}

?>
