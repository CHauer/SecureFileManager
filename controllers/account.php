<?php
/**
 * Created by PhpStorm.
 * User: Christoph
 * Date: 14.05.2015
 * Time: 13:56
 */

class AccountController extends BaseController
{
    //add to the parent constructor
    public function __construct($action, $urlValues) {
        parent::__construct($action, $urlValues);

        //create the model object
        require("models/account.php");
        $this->model = new AccountModel();
    }

    //default method
    protected function register()
    {
        if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST')
        {
            Redirect("home/index");
        }
        else
        {
            $this->view->output($this->model->register());
        }
    }

    protected function login()
    {
        if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST')
        {
            Redirect("home/index");
        }
        else
        {
            $this->view->output($this->model->login());
        }
    }
}

?>