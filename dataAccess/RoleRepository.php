<?php
/**
 * Created by PhpStorm.
 * User: Christoph
 * Date: 15.05.2015
 * Time: 04:50
 */

class RoleRepository {

    /**
     * @param $name
     */
    public function GetRoleId($name)
    {
        global $db;

        $stmt = $db->prepare('Select RoleId from [Role] where Name = :name');
        $stmt->bindParam(':name', $name);

        $stmt->execute();

        $results = $stmt->fetchAll();

        if ($stmt->rowCount() == 1) {
            return $results[0]['RoleId'];
        }
    }

    /**
     * @param $roleid
     * @return null|Role
     */
    public function GetRole($roleid)
    {
        global $db;

        $stmt = $db->prepare('SELECT [RoleId]
                              ,[Name]
                              ,[FileDownload]
                              ,[ReadForum]
                              ,[ReadComments]
                              ,[FileUpload]
                              ,[WriteForum]
                              ,[WriteComments]
                              FROM [dbo].[Role]
                              WHERE [RoleId] = :roleid');
        $stmt->bindParam(':roleid', $roleid);

        $stmt->execute();

        $result = $stmt->fetch();

        if ($result == false)
        {
            return NULL;
        }

        $role = new Role();

        $role->RoleId = $result["RoleId"];
        $role->FileDownload = $result["FileDownload"];
        $role->FileUpload = $result["FileUpload"];
        $role->Name = $result["Name"];
        $role->ReadComments= $result["ReadComments"];
        $role->ReadForum = $result["ReadForum"];
        $role->WriteComments = $result["WriteComments"];
        $role->WriteForum= $result["WriteForum"];

        return $role;
    }
}