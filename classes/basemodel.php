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
    protected function commonViewData() {
	
    //e.g. $this->viewModel->set("mainMenu",array("Home" => "/home", "Help" => "/help"));
    }
}

?>
