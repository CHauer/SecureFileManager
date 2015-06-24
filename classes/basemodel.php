<?php
/* 
 * Project: SecureFileManager
 * File: /classes/basemodel.php
 * Purpose: abstract class from which models extend.
 * Author: Christoph Hauer
 */

class BaseModel {
    
    protected $viewModel;
    /**
     * @var PDO
     */
    protected $db;

    //create the base and utility objects available to all models on model creation
    public function __construct($db)
    {
        $this->db = $db;
        $this->viewModel = new ViewModel();
	    $this->commonViewData();
    }

    //establish viewModel data that is required for all views in this method (i.e. the main template)
    protected function commonViewData()
    {
        if(IsUserLoggedOn())
        {
            $repo = new UserRepository($this->db);
            $currentuser = $repo->GetUser(intval($_SESSION["userid"]));

            $this->viewModel->set("userid", $_SESSION["userid"]);
            $this->viewModel->set("username", $currentuser->Username);

            $fullname = $currentuser->Firstname . ' ' . $currentuser->Lastname;

            if($fullname != NULL && $fullname != ' ')
            {
                $this->viewModel->set("fullname", $fullname );
            }
            else
            {
                $this->viewModel->set("fullname", $currentuser->Username );
            }

            if($currentuser->PictureLink == NULL || $currentuser->PictureLink == '')
            {
                $this->viewModel->set("userimage", '/assets/img/user.jpg');
            }
            else
            {
                $this->viewModel->set("userimage", $currentuser->PictureLink);
            }

            $this->viewModel->set("email", $currentuser->EMail);
        }

        //e.g. $this->viewModel->set("mainMenu",array("Home" => "/home", "Help" => "/help"));
    }
}

?>
