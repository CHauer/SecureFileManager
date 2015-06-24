<?php
/**
 * Created by PhpStorm.
 * User: Christoph
 * Date: 22.05.2015
 * Time: 02:33
 */

function CheckRememberMeLogin()
{
    if (isset($_COOKIE['SecureRememberMe']))
    {
        $selectorToken = $_COOKIE['SecureRememberMe'];

        $parts = explode(':', urldecode($selectorToken));

        if(count($parts)== 2)
        {
            $selector = $parts[0];
            $token = $parts[1];
        }

        $authRepo = new AuthTokenRepository(CreateDatabaseAccess());
        $authToken = $authRepo->GetAuthToken($selector, $token);

        if($authToken !== NULL)
        {
            $_SESSION["userid"] = $authToken->UserId;

            //TODO renew token expire time
            //$authToken = $authRepo->CreateAuthToken(intval($userid));

            $month = time() + 3600 * 24 * 31; // a month
            setcookie('SecureRememberMe', urldecode($selectorToken), $month, '/');
        }
    }
}