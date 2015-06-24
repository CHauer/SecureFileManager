<?php
/* 
 * Project: SecureFileManager
 * File: /classes/basecontroller.php
 * Purpose: abstract class from which controllers extend
 * Author: Christoph Hauer
 */

abstract class BaseController {
    
    protected $urlValues;
    protected $action;
    /**
     * @var PDO
     */
    protected $db;
    /**
     * @var Logger
     */
    protected $log;
    protected $model;
    protected $view;
    
    public function __construct($action, $urlValues, $db) {
        $this->action = $action;
        $this->urlValues = $urlValues;
        $this->db=$db;
        $this->log=new Logger($db);

        //establish the view object
        $this->view = new View(get_class($this), $action);
    }
        
    //executes the requested method
    public function executeAction() {
        return $this->{$this->action}();
    }
}

?>
