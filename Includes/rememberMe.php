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

        $parts = explode(':', $selectorToken);

        if(count($parts)== 2)
        {
            $selector = $parts[0];
            $token = $parts[1];
        }

        $authRepo = new AuthTokenRepository();
        $authToken = $authRepo->GetAuthToken($selector, $token);

        if($authToken !== NULL)
        {
            $_SESSION["userid"] = $authToken->UserId;

            //Create new token
            $authToken = $authRepo->CreateAuthToken(intval($authToken->UserId));

            $month = time() + 3600 * 24 * 31; // a month
            setcookie('SecureRememberMe', $authToken->Selector . ':' . $authToken->Token , $month);
        }
    }
}