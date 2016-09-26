<?php
namespace Devless\sdk;

require './src/SDK.php';

//instantiation (get token from devless insatance )
$devless = new SDK("http://45.33.95.89:7080", "f9f88701336a8bfe4d9466619654754b");

//authenticating a user
$output = ($devless->call('dvauth','login',['email'=>'k@gmail.com','password'=>'password']));
var_dump($output);
// //set user token from authentication 
 $devless->setUserToken($output['payload']['result']);

// //add data to table 
// var_dump($devless->addData('event', 'event-table', ['name'=>'meme', 'country'=>'US']));

// //update record ewithin table 
// var_dump($devless->where('id',5)->updateData('event', 'event-table',['country'=>'kenya']));

// //delete record from table 
// var_dump($devless->where('id',5)->deleteData('event','event-table'));

//get record from table 
$results = $devless->getData('event','event_signup_professional');

var_dump($results);
