<?php
class SDK
{
    private $connection    = [];
    private $headers       = [];
    public static $payload = [];
    public function __construct($instanceUrl, $token)
    {
        self::$payload['user_token'] = '';
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
        $params = (isset(self::$payload['params']))?self::$payload['params']:'';
        $query  = ($params != null)? $this->queryMaker($params) :'';
        $subUrl = '/api/v1/service/'.$service.'/db?table='.$table.$query;
        self::$payload['params'] = [];  
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
     * @return $this
     */
    public function where($column, $value)
    {
        self::bindToParams('where', $column.','.$value);
        return $this;
    }
    /**
    * Carryout db action orwhere $column equals $value
    * @param $column
    * @param $value
    * @return $this
    */
    public function orWhere($column, $value)
    {
        self::bindToParams('orWhere', $column.','.$value);
        return $this;
    }

    /**
    * Carryout db action where the $field is between $range1 and $range2
    * @param $field
    * @param $range1
    * @param $range2
    * @return $this
    */
    public function between($field, $range1, $range2)
    {
        self::bindToParams('between', $field.','.$range1.','.$range2);
        return $this;
    }

    public function greaterThan($field, $number)
    {
        self::bindToParams('greaterThan', $field.','.$number);
        return $this;
    }

    public function lessThan($field, $number)
    {
        self::bindToParams('lessThan',  $field.','.$number);
        return $this;
    }

    public function lessThanEqual($field, $number)
    {
        self::bindToParams('lessThanEqual',  $field.','.$number);
        return $this;
    }

    public function greaterThanEqual($field, $number)
    {
        self::bindToParams('greaterThanEqual',  $field.','.$number);
        return $this;
    }

    public function search($field, $query)
    {
        self::bindToParams('search', $field.','.$query);
        return $this;
    }

    public function queryParam($name, $params=[])
    {
        self::bindToParams($name, implode(",", $params));
        return $this;
    }
    /**
     * Skip $value number of results
     * @param $value
     * @return $this
     */
    public function offset($value)
    {
        self::bindToParams('offset', $value);
        return $this;
    }
    /**
     * Get a given number of records
     * @param $value
     * @return $this
     */
    public  function size($value)
    {
        self::bindToParams('size', $value);
        return $this;
    }
    /**
     * Order records by a given field
     * @param $value
     * @return $this
     */
    public function orderBy($value)
    {
        self::bindToParams('orderBy', $value);
        return $this;
    }
    /**
     * Get related records
     * @param $value
     * @return $this
     */
    public function related($value)
    {
        self:self::bindToParams('related', $value);
        return $this;
    }

    public function randomize()
    {
        self:self::bindToParams('randomize', 0);
        return $this;
    }
    /**
     * set user token for authentication
     * @param $token
     * @return instance obj
     */
    public function setUserToken($token)
    {
        array_push($this->headers, 'devless-user-token:'.$token);
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
        if ($methodName == 'where' || $methodName == 'orWhere' ) {
            (isset(self::$payload['params'][$methodName]))?  true : self::$payload['params'][$methodName] = [];
            array_push(self::$payload['params'][$methodName], $args);
        } else {
            (isset(self::$payload['params'][$methodName]))?  true : self::$payload['params'][$methodName] = '';
            self::$payload['params'][$methodName] = $args;
        }
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
            return json_decode($response, true);
        }
    }
    private function queryMaker($params, $recKey=null) {
        $queryParams = '';

        foreach($params as $key => $value) {
            $key = ($recKey !== null)? $recKey : $key;
            if(is_array($value)) {
                //replace integer indecies with query key
                $recKey = $key;
                $queryParams .= $this->queryMaker($value, $recKey);
                $recKey = null;

            } else {
                $queryParams = "&".$key."=".$value.$queryParams;

            }
        }
        
        return $queryParams;
    }
}
