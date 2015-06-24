<?php
/* 
 * Project: Nathan MVC
 * File: /controllers/error.php
 * Purpose: controller for the URL access errors of the app.
 * Author: Nathan Davison
 */

class AdminController extends BaseController
{    
    //add to the parent constructor
    public function __construct($action, $urlValues, $db) {
        parent::__construct($action, $urlValues, $db);
        
        //create the model object
        require("models/admin.php");
        $this->model = new AdminModel($db);
    }
    
    //bad URL request error
    protected function users()
    {
        $this->view->output($this->model->users());
    }

    protected function log()
    {
        $this->view->output($this->model->log());
    }
}

?>
