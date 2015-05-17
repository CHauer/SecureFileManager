<?php
/**
 * Created by PhpStorm.
 * User: Christoph
 * Date: 14.05.2015
 * Time: 04:02
 */

class FileRepository{

    /**
     * @param UserFile $file
     * @return bool
     */
    public function InsertFile(UserFile $file){
        global $db;
       /* $stmt = $db->prepare('INSERT INTO [dbo].[User]
           ([Username]
           ,[Password]
           ,[Birthdate]
           ,[EMail]
           ,[Description]
           ,[PictureLink]
           ,[LockoutEnabled]
           ,[LockoutEndDate]
           ,[AccessFailedCount]
           ,[RoleId]
           ,[AuthTokenId]
           ,[Firstname]
           ,[Lastname])
     VALUES
           (:Username,
           :Password,
           :Birthdate,
           :EMail,
           :Description,
           :PictureLink,
           :LockoutEnabled,
           :LockoutEndDate,
           :AccessFailedCount,
           :RoleId,
           :AuthTokenId,
           :Firstname,
           :Lastname)');
        $stmt->bindParam(":Username", $user->Username);
        $stmt->bindParam(":Password", $user->Password);
        $stmt->bindParam(":Birthdate", $user->Birthdate);
        $stmt->bindParam(":EMail", $user->EMail);
        $stmt->bindParam(":Description", $user->Description);
        $stmt->bindParam(":PictureLink", $user->PictureLink);
        $stmt->bindParam(":LockoutEnabled", $user->LockoutEnabled);
        $stmt->bindParam(":LockoutEndDate", $user->LockoutEndDate);
        $stmt->bindParam(":AccessFailedCount", $user->AccessFailedCount);
        $stmt->bindParam(":RoleId", $user->RoleId);
        $stmt->bindParam(":AuthTokenId", $user->AuthTokenId);
        $stmt->bindParam(":Firstname", $user->Firstname);
        $stmt->bindParam(":Lastname", $user->Lastname);

        $stmt->execute();

        return $stmt->columnCount() == 1;*/

        return true;
    }

    public function GetPublicAllFiles()
    {
        global $db;
        $isprivate = 0;

        $stmt = $db->prepare('Select * from [UserFile] where IsPrivate = :ispriv');
        $stmt->bindParam(':ispriv', $isprivate);

        $result = $stmt->execute();

        if ($stmt->columnCount() >= 1) {
            return $result;
        }
    }

}
