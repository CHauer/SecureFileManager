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

        $stmt = $db->prepare("INSERT INTO [dbo].[User]
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
           CONVERT(nvarchar,HASHBYTES('SHA2_256', :Password),2) ,
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
           :Lastname)");
        $stmt->bindParam(":Username", $user->Username);
        $stmt->bindParam(":Password", $user->Password);
        $stmt->bindParam(":Birthdate", date_format($user->BirthDate, 'm.d.Y'));
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

        if ($stmt->rowCount() == 1)
        {
            return $db->lastInsertId();
        }

        return false;
    }

    /**
     * @param $userid
     * @return User
     */
    public function GetUser($userid){
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
           where U.UserId=:userid');

        $stmt->bindParam(":userid", $userid);
        $stmt->execute();

        $result = $stmt->fetch();

        if($result == false)
        {
            throw new InvalidArgumentException("The given userid does not exist!");
        }

        $user = new User();

        $user->UserId = $userid;
        $user->Description = $result["Description"];
        $user->Firstname = $result["Firstname"];
        $user->Lastname = $result["Lastname"];
        $user->BirthDate = date_format($result["BirthDate"], 'd.m.Y');
        $user->AccessFailedCount = $result["AccessFailedCount"];
        $user->AuthTokenId = $result["AuthTokenId"];
        $user->EMail = $result["EMail"];
        $user->Username = $result["Username"];
        $user->PictureLink = $result["PictureLink"];
        $user->LockoutEnabled = $result["LockoutEnabled"];
        $user->LockoutEndDate= $result["LockoutEndDate"];
        $user->RoleId = $result["RoleId"];

        return $user;
    }

    /**
     * @param string $roleName
     * @return bool
     */
    public function IsUserInRole($roleName, $userid){
        $roleRepo = new RoleRepository();
        $roleId = $roleRepo->GetRoleId($roleName);

        global $db;

        $query = "SELECT Top(1) UserId FROM [User] U
                  WHERE U.RoleId=:roleId AND U.UserId=:id";
        $statement = $db->prepare($query);
        $statement->bindValue(':id', $userid);
        $statement->bindValue(':roleId', $roleId);
        $statement->execute();

        return ($statement->fetch() !== false);
    }

    public function IsUsernameUsed($username){
        global $db;

        $query = "SELECT Top(1) [UserId] FROM [User]
                  WHERE [Username]=:username";
        $statement = $db->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->execute();
        return ($statement->fetch() !== false);
    }


    /**
     * @param $username
     * @param $password
     * @return null
     */
    public function CheckUserCredentials($username,  $password)
    {
        global $db;

        try
        {
            $statement = $db->prepare("SELECT Top 1 [UserId] FROM [User]
                                      WHERE [Username]=:username
                                      AND [Password]= CONVERT(nvarchar,HASHBYTES('SHA2_256', :password),2)");
            $statement->bindParam(':username', $username);
            $statement->bindParam(':password', $password);
            $statement->execute();

            $result = $statement->fetch();

            if ($result == false)
            {
                return NULL;
            }

            return $result["UserId"];

        }
        catch(Exception $ex)
        {
            return NULL;
        }
    }

    /**
     * @param $username
     * @return bool
     */
    public function UpdateAccessFailedCounter($username)
    {
        global $db;

        $statement = $db -> prepare('Update [User] set [AccessFailedCount] = [AccessFailedCount] + 1
                                      WHERE [Username]=:username');
        $statement->bindParam(':username', $username);
        $statement->execute();

        if($statement->rowCount() == 1)
        {
            $statementsel = $db->prepare('Select [AccessFailedCount] from [User]
                                          WHERE [Username]=:username');
            $statementsel->bindParam(':username', $username);
            $statementsel->execute();

            $result = $statementsel->fetch();

            if($result["AccessFailedCount"] >= 3)
            {
                if(SetUserLockout($username))
                {
                    return true;
                }
            }
            return false;
        }
        return false;
    }

    /**
     * @param $username
     * @return bool
     */
    public function CheckUserLocked($username)
    {
        global $db;

        $statement = $db -> prepare('Select Top 1 [UserId] from [User]
                                      WHERE [LockoutEnabled] = 1
                                      AND [Username]=:username
                                      AND [LockoutEndDate] is not null
                                      AND [LockoutEndDate] > GetDate()');
        $statement->bindParam(':username', $username);
        $statement->execute();

        // if null user is not locked
        if($statement->fetch() == false)
        {
            return false;
        }

        //if 1 user is locked -> check if unlock needed
        $statementUpdate = $db -> prepare('UPDATE [User] set LockoutEnabled = 0
                                          WHERE [Username]=:username
                                          AND [LockoutEnabled] = 1
                                          AND [LockoutEndDate] is not null
                                          AND [LockoutEndDate] < GetDate()');

        $statementUpdate->bindParam(':username', $username);
        $statementUpdate->execute();

        // if 1 updated -> user is no longer locked
        if($statementUpdate->rowCount() == 1)
        {
            return false;
        }

        //if here user is locked
        return true;
    }

    /**
     * @param $username
     * @return bool
     */
    public function SetUserLockout($username)
    {
        global $db;

        $statement = $db -> prepare('Update [User] set [LockoutEnabled] = 1,
                                      [LockoutEndDate] = DateAdd(Minute, 10, GetDate())
                                       WHERE [Username]=:username');
        $statement->bindParam(':username', $username);

        $statement->execute();
        return $statement->rowCount()== 1;
    }

    /**
     * @param $userid
     * @return bool
     */
    public function ResetUserLockout($userid)
    {
        global $db;

        $statement = $db -> prepare('Update [User] set [LockoutEnabled] = 0, [LockoutEndDate] = NULL
                                       WHERE [UserId]=:userid');
        $statement->bindParam(':userid', $userid);

        $statement->execute();
        return $statement->rowCount()== 1;
    }

    /**
     * @param $userid
     * @return bool
     */
    public function ResetAccessFailedCounter($userid)
    {
        global $db;

        $statement = $db -> prepare('Update [User] set [AccessFailedCount] = 0
                                      WHERE [UserId]=:userid AND [LockoutEnabled] = 0');
        $statement->bindParam(':userid', $userid);

        $statement->execute();
        return $statement->rowCount()== 1;
    }

}
