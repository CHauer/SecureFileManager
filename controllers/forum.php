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
        //ConfirmUserIsLoggedOn();

        $this->view->output($this->model->index());
    }

    protected function newThread()
    {
        ConfirmUserIsLoggedOn();

        $this->view->output($this->model->newThread());
    }
}

?>
