<?php
/* 
 * Project: Nathan MVC
 * File: /controllers/home.php
 * Purpose: controller for the home of the app.
 * Author: Nathan Davison
 */

class FilesController extends BaseController
{
    //add to the parent constructor
    public function __construct($action, $urlValues) {
        parent::__construct($action, $urlValues);
        
        //create the model object
        require("models/files.php");
        $this->model = new FilesModel();
    }
    
    //default method
    protected function upload()
    {
        $this->view->output($this->model->index());
    }
}

?>
