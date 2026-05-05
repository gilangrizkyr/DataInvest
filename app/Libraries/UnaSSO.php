<?php

namespace App\Libraries;

use Exception;

class UnaSSO
{
    private $secret;

    public function __construct()
    {
        $this->secret = getenv('SSO_SECRET');
    }

 public function builtRealm()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, getenv('SSO_URL') . 'ssov1/createRealm');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->secret
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        if (!$response) {
            throw new Exception('Failed to connect to SSO server');
        }

        $response = json_decode($response);
        if ($response->status == false) {
            throw new Exception('Failed to create realm: ' . $response->message);
        }

        $url = getenv('SSO_URL') . 'ssov1/realm/' . $response->token;

        return $url;
    }
}
