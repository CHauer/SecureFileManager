<?php
/**
 * Created by PhpStorm.
 * User: Christoph
 * Date: 14.05.2015
 * Time: 04:02
 */

class AuthTokenRepository
{

    public function CreateAuthToken($userid){
        global $db;

        $token = bin2hex(openssl_random_pseudo_bytes(16));

        $selector = 'sel' . $userid;

        //check if token exists
        if($this->CheckAuthTokenExists($userid))
        {
            //if exists delete
            $this->DeleteAuthToken($userid);
        }

        $stmt = $db->prepare("INSERT INTO [dbo].[AuthToken] ([UserId],[Expires],[Selector],[Token])
                             VALUES (:userid, DateAdd(Month, 1, GETDATE()),
                             HASHBYTES('MD5', :selector), HASHBYTES('SHA2_256', :token))");
        $stmt->bindParam(":userid", $userid);
        $stmt->bindParam(":selector", $selector );
        $stmt->bindParam(":token", $token);

        $stmt->execute();

        if ($stmt->rowCount() == 1)
        {
            return true;
        }

        $authToken = new AuthToken();
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

        if ($stmt->rowCount() == 1)
        {
            return true;
        }
    }

    public function DeleteAuthToken($userid)
    {
        global $db;

        $stmt = $db->prepare("DELETE FROM [dbo].[AuthToken]
                                   WHERE [UserId]=:userid");

        $stmt->bindParam(":userid", $userid);
        $stmt->execute();
    }

    public function GetAuthToken($selector, $token)
    {
        global $db;

        $stmt = $db->prepare("SELECT TOP 1 [UserId],[Expires],[Selector],[Token]
                              FROM [dbo].[AuthToken]
                              WHERE [Selector]=:selector
                              AND [Expires] > GETDATE()
                              AND [Token]=HASHBYTES('SHA2_256', :token) ");
        $stmt->bindParam(":selector", $selector);

        $stmt->execute();

        if($stmt->rowCount() !== 1){
            return NULL;
        }

        $result = $stmt->fetchAll[0];

        $authToken = new AuthToken();
        $authToken->UserId = $result['UserId'];
        $authToken->Expires = $result['Expires'];
        $authToken->Selector = $result['Selector'];
        $authToken->Token = $result['Token'];

        return $authToken;
    }

}
