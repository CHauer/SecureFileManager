<?php
/**
 * Created by PhpStorm.
 * User: Christoph
 * Date: 14.05.2015
 * Time: 04:02
 */

class UserRepository{

    /**
     * @param User $user
     * @return bool
     */
    public function InsertUser(User $user){
        global $db;

        $stmt = $db->prepare('INSERT INTO [dbo].[User]
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

        if ($stmt->columnCount() == 1)
        {
            return $db->lastInsertId();
        }

        return false;
    }

    /**
     * @param int $userid
     * @return User
     */
    public function GetUser(int $userid){
        global $db;

        $stmt = $db->prepare('select top 1
            [Username]
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
           ,[Lastname]
           from [dbo].[User] U
           where U.UserId==:userid');

        $stmt->bindParam(":userid", $userid);
        $stmt->execute();

        if($stmt->columnCount() < 1)
        {
            throw new InvalidArgumentException("The given userid does not exist!");
        }

        $result = $stmt->fetchAll()[0];

        $user = new User();

        $user->UserId = $userid;
        $user->Description = $result["Description"];
        $user->Firstname = $result["Description"];
        $user->Lastname = $result["Description"];
        $user->BirthDate = $result["Description"];
        $user->AccessFailedCount = $result["Description"];
        $user->AuthTokenId = $result["Description"];
        $user->EMail = $result["Description"];
        $user->Username = $result["Description"];
        $user->PictureLink = $result["Description"];
        $user->LockoutEnabled = $result["Description"];
        $user->LockoutEndDate= $result["Description"];
        $user->RoleId = $result["Description"];

        return $user;
    }

    /**
     * @param string $roleName
     * @return bool
     */
    public function IsUserInRole(string $roleName){
        $roleRepo = new RoleRepository();
        $roleId = $roleRepo->GetRoleId($roleName);

        global $db;

        $query = "SELECT Top(1) UserId FROM [User] U
                  WHERE U.RoleId=:roleId AND U.UserId=:id";
        $statement = $db->prepare($query);
        $statement->bindValue(':id', $_SESSION['userid']);
        $statement->bindValue(':roleId', $roleId);
        $statement->execute();
        return $statement->num_rows == 1;
    }

}
