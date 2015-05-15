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

        $result = $stmt->execute();

        if ($stmt->columnCount() == 1) {
            return $result[0]['RoleId'];
        }
        return NULL;

    }

}