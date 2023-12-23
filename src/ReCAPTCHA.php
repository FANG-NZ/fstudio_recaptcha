<?php

namespace Fstudio\Recaptcha;

use Exception;

class ReCAPTCHA{

    private $secret;
    private $client_response;
    private $url;

    public function __construct(String $_secret, String $_client_response, String $_url)
    {
        $this->secret = $_secret;
        $this->client_response = $_client_response;
        $this->url = $_url;
    }

    public function verify(String $ip = null)
    {
        //To setup data 
        $data = [
            'secret'   => $this->secret,
            'response' => $this->client_response
        ];

        if($ip)
            $data['remoteip'] = $ip;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $output = curl_exec($ch);
        curl_close($ch);

        if(!$output){
            throw new Exception("[Google reCAPTCHA] CURL post request error");
        }

        $response = json_decode($output);
        return $response->success;
    }

}