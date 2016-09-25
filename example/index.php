<?php
namespace Devless\sdk;

require './src/SDK.php';

//instantiation (get token from devless insatance )
$devless = new SDK("http://localhost:8000", "955c8a0dc37b4a22b5950a9e0e9491d0");

//authenticating a user
$output = ($devless->call('dvauth','login',['email'=>'k@gmail.com','password'=>'password']));

//set user token from authentication 
$devless->setUserToken($output['payload']['result']);

//add data to table 
var_dump($devless->addData('event', 'event-table', ['name'=>'meme', 'country'=>'US']));

//update record ewithin table 
var_dump($devless->where('id',5)->updateData('event', 'event-table',['country'=>'kenya']));

//delete record from table 
var_dump($devless->where('id',5)->deleteData('event','event-table'));

//get record from table 
$results = $devless->getData('event','event-table');

var_dump($results);
