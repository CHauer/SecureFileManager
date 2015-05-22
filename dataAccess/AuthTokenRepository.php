<?php
/**
 * Created by PhpStorm.
 * User: Christoph
 * Date: 14.05.2015
 * Time: 04:02
 */

class AuthTokenRepository
{

    /**
     * @param $userid
     * @return AuthToken|null
     */
    public function CreateAuthToken($userid){
        global $db;

        $token = strtoupper(bin2hex(openssl_random_pseudo_bytes(16)));

        $selector = rand(100, 999) . 'sel' . $userid;

        //check if token exists
        if($this->CheckAuthTokenExists($userid))
        {
            //if exists delete
            $this->DeleteAuthToken($userid);
        }

        $stmt = $db->prepare("INSERT INTO [dbo].[AuthToken] ([UserId],[Expires],[Selector],[Token])
                             VALUES (:userid, DateAdd(Month, 1, GETDATE()),
                              :selector, CONVERT(nvarchar,HASHBYTES('SHA2_256', :token),2))");
        $stmt->bindParam(":userid", intval($userid));
        $stmt->bindParam(":selector", $selector );
        $stmt->bindParam(":token", $token, PDO::PARAM_STR);

        $stmt->execute();

        if ($stmt->rowCount() == 0)
        {
            return NULL;
        }

        $authTokenId =  $db->lastInsertId();

        $stmtUptUser = $db->prepare("UPDATE [dbo].[User] SET [AuthTokenId]=:authTokenId WHERE [UserId]=:userid");
        $stmtUptUser->bindParam(":authTokenId", intval($authTokenId));
        $stmtUptUser->bindParam(":userid", intval($userid));

        $stmtUptUser->execute();

        if($stmtUptUser->rowCount() == 0) {
            return NULL;
        }

        $authToken = new AuthToken();
        $authToken->AuthTokenId = $authTokenId;
        $authToken->Token = $token;
        $authToken->Selector = $selector;
        $authToken->UserId = $userid;

        return $authToken;
    }

    public function CheckAuthTokenExists($userid)
    {
        global $db;

        $stmt = $db->prepare("SELECT TOP 1 [UserId] FROM [dbo].[AuthToken]
                                   WHERE [UserId]=:userid");

        $stmt->bindParam(":userid", $userid);
        $stmt->execute();

        if ($stmt->fetch() !== false)
        {
            return true;
        }
        return false;
    }

    public function DeleteAuthToken($userid)
    {
        global $db;

        $stmtUptUser = $db->prepare("UPDATE [dbo].[User] SET [AuthTokenId]=null WHERE [UserId]=:userid");
        $stmtUptUser->bindParam(":userid", intval($userid));

        $stmtUptUser->execute();

        if($stmtUptUser->rowCount() == 0)
        {
            return;
        }

        $stmt = $db->prepare("DELETE FROM [dbo].[AuthToken]
                                   WHERE [UserId]=:userid");

        $stmt->bindParam(":userid", intval($userid));
        $stmt->execute();
    }

    public function GetAuthToken($selector, $token)
    {
        global $db;

        $stmt = $db->prepare("SELECT TOP 1 [UserId],[Expires],[Selector],[Token]
                              FROM [dbo].[AuthToken]
                              WHERE [Selector]=:selector
                              AND [Expires] > GETDATE()
                              AND [Token]=CONVERT(nvarchar,HASHBYTES('SHA2_256', :token),2) ");
        $stmt->bindParam(":selector", $selector);
        $stmt->bindParam(":token", $token, PDO::PARAM_STR);

        $stmt->execute();

        $result = $stmt->fetch();

        if($result == false){
            return NULL;
        }

        $authToken = new AuthToken();
        $authToken->UserId = $result['UserId'];
        $authToken->Expires = $result['Expires'];
        $authToken->Selector = $result['Selector'];
        $authToken->Token = $token;

        return $authToken;
    }

}
