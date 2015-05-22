<?php
/* 
 * Project: SecureFileManager
 * File: /classes/basemodel.php
 * Purpose: abstract class from which models extend.
 * Author: Christoph Hauer
 */

class BaseModel {
    
    protected $viewModel;

    //create the base and utility objects available to all models on model creation
    public function __construct()
    {
        $this->viewModel = new ViewModel();
	    $this->commonViewData();
    }

    //establish viewModel data that is required for all views in this method (i.e. the main template)
    protected function commonViewData()
    {
        if(IsUserLoggedOn() && isset($_SESSION["userid"]))
        {
            $repo = new UserRepository();
            $currentuser = $repo->GetUser(intval($_SESSION["userid"]));

            $this->viewModel->set("userid", $_SESSION["userid"]);
            $this->viewModel->set("username", $currentuser->Username);
            if($currentuser->PictureLink == NULL || $currentuser->PictureLink == ''){
                $this->viewModel->set("userimage", '/assets/img/user.jpg');
            }else {
                $this->viewModel->set("userimage", $currentuser->PictureLink);
            }
            $this->viewModel->set("email", $currentuser->EMail);
        }

        //e.g. $this->viewModel->set("mainMenu",array("Home" => "/home", "Help" => "/help"));
    }
}

?>
