<?php
/**
 * Created by PhpStorm.
 * User: Christoph
 * Date: 14.05.2015
 * Time: 04:02
 */

class UserRepository extends BaseRepository
{
    public function __construct($db)
    {
        parent::__construct($db);
    }

    /**
     * @param User $user
     * @return bool
     */
    public function InsertUser(User $user){


        $stmt = $this->db->prepare("INSERT INTO [dbo].[User]
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
        $stmt->bindParam(":Firstname", htmlspecialchars($user->Firstname));
        $stmt->bindParam(":Lastname", htmlspecialchars($user->Lastname));

        $stmt->execute();

        if ($stmt->rowCount() == 1)
        {
            return $this->db->lastInsertId();
        }

        return false;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function UpdateUser(User $user){


        $stmt = $this->db->prepare("UPDATE [dbo].[User]
                               SET [Username]=:Username
                               ,[Birthdate]=:Birthdate
                               ,[EMail]=:EMail
                               ,[Description]=:Description
                               ,[RoleId]=:RoleId
                               ,[Firstname]=:Firstname
                               ,[Lastname]=:Lastname
                                WHERE [UserId]=:userid");

        $date = date_format($user->BirthDate, 'm.d.Y');

        $stmt->bindValue(":Username", $user->Username);
        $stmt->bindValue(":Birthdate", $date);
        $stmt->bindValue(":EMail", $user->EMail);
        $stmt->bindValue(":Description", $user->Description);
        $stmt->bindValue(":RoleId", $user->RoleId);
        $stmt->bindValue(":Firstname", htmlspecialchars($user->Firstname));
        $stmt->bindValue(":Lastname", htmlspecialchars($user->Lastname));
        $stmt->bindValue(":userid", $user->UserId);

        $stmt->execute();

        if ($stmt->rowCount() == 1)
        {
            return true;
        }

        return false;
    }

    /**
     * @param $userid
     * @param $pictureLink
     * @return bool
     */
    public function UpdateUserPicture($userid, $pictureLink){


        $stmt = $this->db->prepare("UPDATE [dbo].[User]
                               SET
                               [PictureLink]=:PictureLink
                                WHERE [UserId]=:userid");

        $stmt->bindParam(":PictureLink", $pictureLink);
        $stmt->bindParam(":userid", $userid);
        $stmt->execute();

        if ($stmt->rowCount() == 1)
        {
            return true;
        }

        return false;
    }

    /**
     * @param $userid
     * @param $oldpassword
     * @param $newpassword
     * @return bool
     */
    public function UpdateUserPassword($userid, $oldpassword , $newpassword){


        $stmt = $this->db->prepare("UPDATE [dbo].[User]
                               SET
                               [Password]= CONVERT(nvarchar,HASHBYTES('SHA2_256', :NewPassword),2)
                                WHERE [UserId]=:userid AND  [Password]=CONVERT(nvarchar,HASHBYTES('SHA2_256', :OldPassword),2) ");

        $stmt->bindValue(":NewPassword", $newpassword);
        $stmt->bindValue(":OldPassword", $oldpassword);
        $stmt->bindValue(":userid", $userid);
        $stmt->execute();

        if ($stmt->rowCount() == 1)
        {
            return true;
        }

        return false;
    }

    /**
     * @param $userid
     * @return User
     */
    public function GetUser($userid){
        $stmt = $this->db->prepare('select top 1
            [Username]
           ,convert(varchar, [Birthdate], 104) as [Birthdate]
           ,[EMail]
           ,[Description]
           ,[PictureLink]
           ,[LockoutEnabled]
           , convert(varchar, [LockoutEndDate], 104) as [LockoutEndDate]
           ,[AccessFailedCount]
           ,[Deactivated]
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
        $user->BirthDate = ParseDate($result["Birthdate"]);
        $user->AccessFailedCount = $result["AccessFailedCount"];
        $user->AuthTokenId = $result["AuthTokenId"];
        $user->EMail = $result["EMail"];
        $user->Deactivated = $result["Deactivated"];
        $user->Username = $result["Username"];
        $user->PictureLink = $result["PictureLink"];
        $user->LockoutEnabled = $result["LockoutEnabled"];
        if( $result["LockoutEndDate"] != NULL)
        {
            $user->LockoutEndDate = ParseDate($result["LockoutEndDate"]);
        }
        $user->RoleId = intval($result["RoleId"]);

        return $user;
    }

    /**
     * @param $userid
     * @return User
     */
    public function GetAllUsers()
    {
        $roleRepo = new RoleRepository($this->db);

        $stmt = $this->db->prepare('select
            [UserId]
            ,[Username]
           ,convert(varchar, [Birthdate], 104) as [Birthdate]
           ,[EMail]
           ,[Description]
           ,[PictureLink]
           ,[LockoutEnabled]
           ,convert(varchar, [LockoutEndDate], 104) as [LockoutEndDate]
           ,[Deactivated]
           ,[AccessFailedCount]
           ,[RoleId]
           ,[AuthTokenId]
           ,[Firstname]
           ,[Lastname]
           from [dbo].[User] U');

        $stmt->execute();

        $results = $stmt->fetchAll();

        if($results == NULL){
            return NULL;
        }

        $users = Array();
        $i = 0;

        foreach($results as $result)
        {
            $user = new User();

            $user->UserId = $result["UserId"];
            $user->Description = $result["Description"];
            $user->Firstname = $result["Firstname"];
            $user->Lastname = $result["Lastname"];
            $user->BirthDate = ParseDate($result["Birthdate"]);
            $user->AccessFailedCount = $result["AccessFailedCount"];
            $user->AuthTokenId = $result["AuthTokenId"];
            $user->EMail = $result["EMail"];
            $user->Deactivated = $result["Deactivated"];
            $user->Username = $result["Username"];
            $user->PictureLink = $result["PictureLink"];
            $user->LockoutEnabled = $result["LockoutEnabled"];
            if( $result["LockoutEndDate"] != NULL) {
                $user->LockoutEndDate = ParseDate($result["LockoutEndDate"]);
            }
            $user->RoleId = intval($result["RoleId"]);
            $user->Role =$roleRepo->GetRole($user->RoleId);
            $users[$i++] = $user;
        }

        return $users;
    }

    /**
     * @param string $roleName
     * @return bool
     */
    public function IsUserInRole($roleName, $userid){
        $roleRepo = new RoleRepository($this->db);
        $roleId = $roleRepo->GetRoleId($roleName);

        $query = "SELECT Top(1) UserId FROM [User] U
                  WHERE U.RoleId=:roleId AND U.UserId=:id";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':id', $userid);
        $statement->bindValue(':roleId', $roleId);
        $statement->execute();

        return ($statement->fetch() !== false);
    }

    /**
     * @param $username
     * @return bool
     */
    public function IsUsernameUsed($username){


        $query = "SELECT Top(1) [UserId] FROM [User]
                  WHERE [Username]=:username";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':username', htmlspecialchars($username));
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


        try
        {
            $statement = $this->db->prepare("SELECT Top 1 [UserId] FROM [User]
                                      WHERE [Username]=:username
                                      AND [Password]= CONVERT(nvarchar,HASHBYTES('SHA2_256', :password),2)");
            $statement->bindValue(':username', $username);
            $statement->bindValue(':password', $password);
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


        $statement = $this->db -> prepare('Update [User] set [AccessFailedCount] = COALESCE([AccessFailedCount],0) + 1
                                      WHERE [Username]=:username');
        $statement->bindParam(':username', $username);
        $statement->execute();

        if($statement->rowCount() == 1)
        {
            $statementsel = $this->db->prepare('Select COALESCE ([AccessFailedCount],0 ) as [AccessFailedCount] from [User]
                                          WHERE [Username]=:username');
            $statementsel->bindValue(':username', $username);
            $statementsel->execute();

            $result = $statementsel->fetch();

            if($result["AccessFailedCount"] >= 3)
            {
                if($this->SetUserLockout($username))
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


        $statement = $this->db -> prepare('Select Top 1 [UserId] from [User]
                                      WHERE [LockoutEnabled] = 1
                                      AND [Username]=:username
                                      AND [LockoutEndDate] is not null
                                      AND [LockoutEndDate] > GetDate()');
        $statement->bindValue(':username', $username);
        $statement->execute();

        // if null user is not locked
        if($statement->fetch() == false)
        {
            return false;
        }

        return true;
    }

    /**
     * @param $username
     * @return bool
     */
    public function SetUserLockout($username)
    {


        $statement = $this->db -> prepare('Update [User] set [LockoutEnabled] = 1,
                                      [LockoutEndDate] = DateAdd(Minute, 10, GetDate())
                                       WHERE [Username]=:username');
        $statement->bindValue(':username', $username);

        $statement->execute();
        return $statement->rowCount()== 1;
    }

    /**
     * @param $userid
     * @return bool
     */
    public function SetUserDeactivated($userid)
    {


        $statement = $this->db -> prepare('Update [User] set [Deactivated] = 1
                                       WHERE [UserId]=:userid');
        $statement->bindValue(':userid', $userid);

        $statement->execute();
        return $statement->rowCount()== 1;
    }

    /**
     * @param $userid
     * @return bool
     */
    public function ResetUserDeactivated($userid)
    {


        $statement = $this->db -> prepare('Update [User] set [Deactivated] = 0
                                       WHERE [UserId]=:userid AND [Deactivated]=1');
        $statement->bindValue(':userid', $userid);

        $statement->execute();
        return $statement->rowCount()== 1;
    }

    /**
     * @param $userid
     * @return bool
     */
    public function ResetUserLockout($userid)
    {


        $statement = $this->db -> prepare('UPDATE [User] SET [LockoutEnabled] = 0, [LockoutEndDate] = null
                                          WHERE [UserId]=:userid
                                          AND [LockoutEnabled] = 1
                                          AND [LockoutEndDate] is not null
                                          AND [LockoutEndDate] < GetDate()');
        $statement->bindValue(':userid', $userid);

        $statement->execute();
        return $statement->rowCount()== 1;
    }

    /**
     * @param $userid
     * @return bool
     */
    public function ResetAccessFailedCounter($userid)
    {


        $statement = $this->db -> prepare('Update [User] set [AccessFailedCount] = 0
                                      WHERE [UserId]=:userid AND [LockoutEnabled] = 0');
        $statement->bindParam(':userid', $userid);

        $statement->execute();
        return $statement->rowCount()== 1;
    }

    public function CheckEMailExists($email)
    {


        $statement = $this->db -> prepare("select convert(nvarchar, Hashbytes('SHA2_256', [Username] + [EMail] + [Password]),  2) as [Reset]
                                      from [User]
                                      WHERE [EMail]=:email");
        $statement->bindParam(':email', $email);

        $statement->execute();
        $result = $statement->fetch();

        if($result != NULL)
        {
            return $result['Reset'];
        }
        return NULL;
    }

    public function ResetPassword($resetLink, $newPassword, $email, $username)
    {


        $statement = $this->db -> prepare("UPDATE [User]
                                       SET [Password]=CONVERT(nvarchar,HASHBYTES('SHA2_256', :newPassword),2)
                                        WHERE [Username]=:username AND [EMail]=:email
                                        AND convert(nvarchar, Hashbytes('SHA2_256', [Username] + [EMail] + [Password]), 2)=:resetLink");
        $statement->bindValue(':newPassword', $newPassword);
        $statement->bindValue(':resetLink', $resetLink, PDO::PARAM_STR);
        $statement->bindValue(':username', $username);
        $statement->bindValue(':email', $email);

        $statement->execute();

        return $statement->rowCount() == 1;
    }

}
