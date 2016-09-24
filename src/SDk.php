<?php

namespace Devless\sdk;

class SDK
{

    private $connection = [];
    private $headers = [];


    public function __construct($instanceUrl, $token)
    {
        $this->connection['instanceUrl'] = $instanceUrl;
        $this->connection['token'] = $token; 
        $this->headers = [
            "cache-control: no-cache",
            "content-type: application/json",
            "devless-token: ".$token,
          ];
    }

    public function queryData() 
    {
        //code goes headers
    }

    private function requestProcessor($data, $subUrl, $method, $parse)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->connection['instanceUrl'].$subUrl,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => strtoupper($method),
          CURLOPT_POSTFIELDS => $data,
          CURLOPT_HTTPHEADER => $this->headers,
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          return  $err;
        } else {
            return  json_decode($response);
        }
    }
}
