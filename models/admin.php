<?php
/* 
 * Project: Nathan MVC
 * File: /models/error.php
 * Purpose: model for the error controller.
 * Author: Nathan Davison
 */

class AdminModel extends BaseModel
{
    public function __construct($db)
    {
        parent::__construct($db);
    }

    //data passed to the bad URL error view
    public function users()
    {
        $userRepo = new UserRepository($this->db);
        $users = $userRepo->GetAllUsers();

        $this->viewModel->set("pageTitle","Userlist");
        $this->viewModel->set("model",$users);
        return $this->viewModel;
    }

    public function log()
    {
        $log = new Logger($this->db);
        $logs = $log->GetAllLogMessages(1000);
        $this->viewModel->set("pageTitle","Log");
        $this->viewModel->set("model",$logs);
        return $this->viewModel;
    }
}

?>
