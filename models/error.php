<?php
/* 
 * Project: Nathan MVC
 * File: /models/error.php
 * Purpose: model for the error controller.
 * Author: Nathan Davison
 */

class ErrorModel extends BaseModel
{
    public function __construct($db)
    {
        parent::__construct($db);
    }

    //data passed to the bad URL error view
    public function badURL()
    {
        $this->viewModel->set("pageTitle","Error - Bad URL");
        return $this->viewModel;
    }

    public function unexpectedError()
    {
        $this->viewModel->set("pageTitle","Unexpected Error");
        return $this->viewModel;
    }
}

?>
