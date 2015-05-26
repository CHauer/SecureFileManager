<?php
/* 
 * Project: Nathan MVC
 * File: /models/error.php
 * Purpose: model for the error controller.
 * Author: Nathan Davison
 */

class AdminModel extends BaseModel
{    
    //data passed to the bad URL error view
    public function users()
    {
        //TODO load all users
        $this->viewModel->set("pageTitle","Userlist");
        return $this->viewModel;
    }

    public function log()
    {
        //todo load all log entries
        $this->viewModel->set("pageTitle","Log");
        return $this->viewModel;
    }
}

?>
