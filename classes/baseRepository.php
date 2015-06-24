<?php
/* 
 * Project: SecureFileManager
 * File: /classes/basecontroller.php
 * Purpose: abstract class from which repositories extend
 * Author: Christoph Hauer
 */

abstract class BaseRepository
{
    /**
     * @var PDO
     */
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

}

?>
