<?php
/* 
 * Project: SecureFileManager - FH WN
 * File: index.php
 * Purpose: landing page which handles all requests
 * Author: Haller, Hauer, Leeb
 */

//load the required classes
require("classes/basecontroller.php");  
require("classes/basemodel.php");
require("classes/view.php");
require("classes/viewmodel.php");
require("classes/loader.php");
require("dataAccess/initDataAccess.php");

$loader = new Loader(); //create the loader object
$controller = $loader->createController(); //creates the requested controller object based on the 'controller' URL value
$controller->executeAction(); //execute the requested controller's requested method based on the 'action' URL value. Controller methods output a View.

?>