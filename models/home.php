<?php
/* 
 * Project: Nathan MVC
 * File: /models/home.php
 * Purpose: model for the home controller.
 * Author: Nathan Davison
 */

class HomeModel  extends BaseModel
{
    public function __construct($db)
    {
        parent::__construct($db);
    }

    //data passed to the home index view
    public function index()
    {   
        $this->viewModel->set("pageTitle","Home");
        return $this->viewModel;
    }

    public function faq(){
        $this->viewModel->set("pageTitle","Get HELP!?!");
        return $this->viewModel;
    }
}

?>
