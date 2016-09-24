<?php

namespace Devless\sdk;

class SDK
{

    private $connection    = [];
    private $headers       = [];
    public static $payload = [];



    public function __construct($instanceUrl, $token)
    {
        $this->payload['user_token'] = '';
        $this->connection['instanceUrl'] = $instanceUrl;
        $this->connection['token'] = $token; 
        $this->headers = [
            "cache-control: no-cache",
            "content-type: application/json",
            "devless-token: ".$token,
          ];

          return $this;
    }



    /**
     * add data to a service table
     * @param $service
     * @param $table
     * @param $data
     * @return array
     */
    public function addData($service, $table, $data)
    {
        $data = json_encode($data, true);

        $data = '{"resource": [ {"name": "'.$table.'","field": ['.$data.'] } ]}';
        
        $subUrl =  '/api/v1/service/'.$service.'/db';
        return $this->requestProcessor($data, $subUrl, 'POST');
    }

    /**
     * get data from Devless instance
     * @param $service
     * @param $table
     * @return array
     */
    public function getData($service, $table ) 
    {
        $data = [];
        $params = self::$payload['params'];
        $queryParams = '';
        
        function queryMaker($params, $recKey=null) {
            foreach($params as $key => $value) {

                $key = ($recKey !== null)? $recKey : $key;
                if(is_array($value)) {
                    
                    //replace integer indecies with query key
                    $recKey = $key;
                    $queryParams .= queryMaker($value, $recKey);

                      
                } else {
                    $queryParams = "&".$key."=".$value.$queryParams;    
                }
                
            }
            return $queryParams;
        }

        $query  = ($params != null)? queryMaker($params) :'';
        
        $subUrl = '/api/v1/service/'.$service.'/db?table='.$table.$query;
        
        return $this->requestProcessor($data, $subUrl, 'GET');
    }



    public function updateData($service, $table, $data)
    {
        $params = self::$payload['params']['where'][0];

        $data   = json_encode($data, true);

        $data   = '{  "resource":[  {  "name":"'.$table.'","params":[  {  "where":"'.$params.'","data":['.$data.']}]}]}';
        
        $subUrl = '/api/v1/service/'.$service.'/db';
        return $this->requestProcessor($data, $subUrl, 'PATCH');         
    }


    /**
     * delete data from service table
     * @param $service
     * @param $table
     * @return array
     */
    public function deleteData($service, $table)
    {
        $params = self::$payload['params']['where'][0]; 

        $data   = '{  "resource":[  {  "name":"'.$table.'","params":[  {  "where":"'.$params.'","delete":true}]}]}';
        
        $subUrl = '/api/v1/service/'.$service.'/db';
        return $this->requestProcessor($data, $subUrl, 'DELETE');
    }

     /**
     * Carryout db action where $column equals $value
     * @param $column
     * @param $value
     * @return SDK
     */
    public function where($column, $value)
    {
        self::bindToParams('where', $column.','.$value);
        return $this;
    }




     /**
     * Skip $value number of results
     * @param $value
     * @return DataStore
     */
    public function offset($value)
    {
         self::bindToParams('offset', $value);
         return $this;
    }

    /**
     * Get a given number of records
     * @param $value
     * @return null
     */
    public  function size($value)
    {
        self::bindToParams('size', $value);
        return $this;
    }



    /**
     * Order records by a given field
     * @param $value
     * @return DataStore
     */
    public function orderBy($value)
    {
        self::bindToParams('orderBy', $value);
        return $this;
    }

    /**
     * set user token for authentication
     * @param $token
     * @return instance obj
     */
    public function setUserToken($token)
    {
        array_push($this->headers, 'devless_user_token:'.$token);
        return $this;
    }

    public function call($service, $method, $params)
    {
        $id = rand(1,10000000);
        $params = json_encode($params);
        $params = '{ "jsonrpc": "2.0","method":"'.$service.'","id": '.$id.',"params": '.$params.'}';
        
        $subUrl = "/api/v1/service/".$service."/rpc?action=".$method;

        return $this->requestProcessor($params, $subUrl, 'POST');
    }

    /**
     * set query parameter values 
     * @param $methodName
     * @param $args
     * @return instance obj
     */
    private static function bindToParams($methodName, $args)
    {
        
        if ($methodName == 'where') {
            (isset(self::$payload['params'][$methodName]))?  true : self::$payload['params'][$methodName] = [];
        
            array_push(self::$payload['params'][$methodName], $args);

        } else {
            (isset(self::$payload['params'][$methodName]))?  true : self::$payload['params'][$methodName] = '';
        
            self::$payload['params'][$methodName] = $args;
        }
        
        return self;
    }



    private function requestProcessor($data, $subUrl, $method)
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
            return  json_decode($response, true);
        }
    }
}
