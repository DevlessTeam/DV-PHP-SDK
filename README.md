[![Build Status](https://travis-ci.org/DevlessTeam/DV-PHP-SDK.svg?branch=master)](https://travis-ci.org/DevlessTeam/DV-PHP-SDK)
## Install

Via Composer

``` bash
$ composer require devless/php-sdk 
```
# DV-PHP-SDK
Official Devless php sdk

# Getting started 

### To connect to the Devless instance 

```
use Devless\SDK\SDK;

$devless = new SDK("http://example.com", "1234567abcdefghijklmnopqrst");

```
### To add data to table 

```
$devless->addData('service_name', 'service_table', ['name'=>'james']);

```

### To query data from the Devless instance 

```
$results = $devless->getData('service_name','service-table');

var_dump($results);

```
### Also you may filter your query with : 

``size`` : determine the number of results to return 

``` eg: $results = $devless->size(3)->getData('service_name', 'service_table'); ```

``offset`` : Set step in data data to be sent back 

## NB: This is to be used in combination with size

`` eg: $results = $devless->offset(2)->size(6)->getData('service_name', 'service_table'); ```

`` where `` : Get data based on where a key matches a certain value 

``` eg: $results = $devless->where('name', 'edmond')->getData('service_name', 'service_table'); ```

`` orWhere `` : Get a combination of results using a particular identifier from the table 

``` eg: $results = $devless->orWhere('name', 'edmond')->getData('service_name', 'service_table'); ```


``orderBy`` : Order incoming results in descending order based on a key 

`` eg: $results = $devless->orderBy('name')->getData('service_name', 'service_table'); ``


### To update data to table 

```
$devless->where('id',1)->updateData('service_name', 'service_table', ['name'=>'edmond']);

```

### To delete data from a Devless instance 

```
$devless->where('id',1)->deleteData('service_name','service_table');

```

## Make a call to an Action Class 

```
$devless->call('service_name','method_name',[params]);

```

## Authenticating with a Devless instance

```
$token = $devless->call('devless','login',['email'=>'k@gmail.com','password'=>'password'])['token'];

$devless->setUserToken($token['payload']['result']);

```



