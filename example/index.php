<?php


$devless = new SDK("http://localhost:8000", "955c8a0dc37b4a22b5950a9e0e9491d0");
$output = ($devless->call('dvauth','login',['email'=>'k@gmail.com','password'=>'password']));
$devless->setUserToken($output['payload']['result']);
//var_dump($devless->addData('event', 'event-table', ['name'=>'meme', 'country'=>'US']));
//var_dump($devless->where('id',5)->updateData('event', 'event-table',['country'=>'kenya']));
//var_dump($devless->where('id',5)->deleteData('event','event-table'));
//var_dump($devless->getData('event','event-table'));

$results = $devless->getData('event','event-table');

var_dump($results);
