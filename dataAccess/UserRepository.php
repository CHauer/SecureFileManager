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
           HASHBYTES(\'SHA2_256\', :Password),
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
        return $statement->columnCount() == 1;
    }

    public function CheckUserCredentials(string $username, string $password)
    {
        global $db;

        $statement = $db -> prepare('SELECT [UserId] FROM [User]
                                      WHERE [Username]=:username
                                      AND [Password]= HASHBYTES(\'SHA2_256\', :password)');
        $statement->bindParam(':username', $username);
        $statement->bindParam(':password', $username);
        $statement->execute();
        if($statement->columnCount() <= 0){
            return NULL;
        }

        return $statement->fetchAll()[0]["UserId"];
    }

    public function UpdateAccessFailedCounter(string $username)
    {
        global $db;

        $statement = $db -> prepare('Update [User] U set U.AccessFailedCount = U.AccessFailedCount + 1
                                      WHERE [Username]=:username');
        $statement->bindParam(':username', $username);
        $statement->execute();

        if( $statement->columnCount() == 1)
        {
            $statementsel = $db->prepare('Select [AccessFailedCount] from [User] U
                                          WHERE [Username]=:username');
            $statementsel->bindParam(':username', $username);
            $statementsel->execute();

            $result = $statementsel->fetchAll()[0];

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

    public function CheckUserLocked(string $username)
    {
        global $db;

        $statement = $db -> prepare('select [UserId] from [User] U
                                      WHERE [LockoutEnabled] = 1
                                      AND [Username]=:username
                                      AND [LockoutEndDate] is not null
                                      AND [LockoutEndDate] > GetDate()');
        $statement->bindParam(':username', $username);
        $statement->execute();

        // if null user is not locked
        if($statement->columnCount() == 0) {
            return false;
        }

        //if 1 user is locked -> check if unlock needed
        $statementUpdate = $db -> prepare('UPDATE [User] set LockoutEnabled = 0
                                          WHERE [Username]=:username
                                          AND [LockoutEnabled] = 1
                                          AND [LockoutEndDate] is not null
                                          AND [LockoutEndDate] < GetDate()');

        $statement->bindParam(':username', $username);
        $statement->execute();

        // if 1 updated -> user is no longer locked
        if($statement->columnCount() == 1)
        {
            return false;
        }

        //if here user is locked
        return true;
    }

    public function SetUserLockout(string $username)
    {
        global $db;

        $statement = $db -> prepare('Update [User] U set U.LockoutEnabled = 1,
                                      U.LockoutEndDate = DateAdd(Minute, 10, GetDate())
                                       WHERE [Username]=:username');
        $statement->bindParam(':username', $username);

        $statement->execute();
        return $statement->columnCount() == 1;
    }

    public function ResetUserLockout($userid)
    {
        global $db;

        $statement = $db -> prepare('Update [User] U set U.LockoutEnabled = 0, U.LockoutEndDate = NULL
                                       WHERE [UserId]=:userid');
        $statement->bindParam(':userid', $userid);

        $statement->execute();
        return $statement->columnCount() == 1;
    }

    public function ResetAccessFailedCounter($userid)
    {
        global $db;

        $statement = $db -> prepare('Update [User] U set U.AccessFailedCount = 0
                                      WHERE [UserId]=:userid and U.LockoutEnabled = 0');
        $statement->bindParam(':userid', $userid);

        $statement->execute();
        return $statement->columnCount() == 1;
    }

}
