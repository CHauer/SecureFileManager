<?php
/* 
 * Project: SecureFileManager
 * File: /classes/viewmodel.php
 * Purpose: class for the optional data object returned by model methods which the controller sends to the view.
 * Author: Christoph Hauer
 */

class ViewModel {

    private $validationErrors;

    public function __construct()
    {
        $this->validationErrors = array();
    }

    /**
     * @param string $fieldName
     * @param string $errorMessage
     */
    public function setFieldError($fieldName, $errorMessage)
    {
        $this->validationErrors[$fieldName] = $errorMessage;
    }

    /**
     * @param $fieldName
     * @return null
     */
    public function getFieldError($fieldName)
    {
        if(array_key_exists($fieldName, $this->validationErrors))
        {
            return $this->validationErrors[$fieldName];
        }
        return NULL;
    }

    /**
     * @param $fieldName
     * @return bool
     */
    public function isFieldError($fieldName)
    {
        return array_key_exists($fieldName, $this->validationErrors);
    }

    /**
     * @return bool
     */
    public function isErrorSet()
    {
        return ($this->exists("error") || count($this->validationErrors) > 0);
    }

    //dynamically adds a property or method to the ViewModel instance
    public function set($name,$val) {
        $this->$name = $val;
    }

    //returns the requested property value
    public function get($name) {
        if (isset($this->{$name}))
        {
            return $this->{$name};
        }
        else
        {
            return null;
        }
    }

    public function exists($name){
        return isset($this->{$name});
    }

}

?>