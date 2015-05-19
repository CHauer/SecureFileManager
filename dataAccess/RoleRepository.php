<?php
/**
 * Created by PhpStorm.
 * User: Christoph
 * Date: 15.05.2015
 * Time: 04:50
 */

class RoleRepository {

    /**
     * @param string $name
     */
    public function GetRoleId(string $name)
    {
        global $db;

        $stmt = $db->prepare('Select RoleId from [Role] where Name = :name');
        $stmt->bindParam(':name', $name);

        $stmt->execute();

        $results = $stmt->fetchAll();

        if ($stmt->columnCount() == 1) {
            return $results[0]['RoleId'];
        }
    }

}