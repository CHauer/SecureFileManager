<?php
/* 
 * Project: SecureFileManager
 * File: /classes/viewmodel.php
 * Purpose: class for the optional data object returned by model methods which the controller sends to the view.
 * Author: Christoph Hauer
 */

class ViewModel {    
    
    //dynamically adds a property or method to the ViewModel instance
    public function set($name,$val) {
        $this->$name = $val;
    }
    
    //returns the requested property value
    public function get($name) {
        if (isset($this->{$name})) {
            return $this->{$name};
        } else {
            return null;
        }
    }

    public function exists($name){
        return isset($this->{$name});
    }

}

?>