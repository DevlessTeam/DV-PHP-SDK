<?php

require __DIR__ .'/../src/SDK.php';

//instantiation (get token from devless insatance )
$devless = new SDK("http://localhost:6060", "14a687b94b1532cc7d2330ae530e3be5");

//authenticating a user
// $output = ($devless->call('dvauth','login',['email'=>'k@gmail.com','password'=>'password']));
// var_dump($output);
// //set user token from authentication 
 // $devless->setUserToken($output['payload']['result']);

// //add data to table 
// var_dump($devless->addData('event', 'event-table', ['name'=>'meme', 'country'=>'US']));

// //update record ewithin table 
// var_dump($devless->where('id',5)->updateData('event', 'event-table',['country'=>'kenya']));

// //delete record from table 
// var_dump($devless->where('id',5)->deleteData('event','event-table'));

//get record from table between from service `children` table `ages`
// $results = $devless->between('age',1,19)->getData('children','ages');
// $results = $devless->lessThan('age', 60)->getData('serviceit','temporal');
// $results = $devless->greaterThan('age', 60)->getData('serviceit','temporal');
// $results = $devless->greaterThanEqual('age', 90)->getData('serviceit','temporal');
// $results = $devless->lessThanEqual('age', 30)->getData('serviceit','temporal');
// $results = $devless->search("name", "james")->getData('serviceit','temporal');
// $results = $devless->offset(2)->size(3)->getData('serviceit','temporal');
// $results = $devless->randomize()->size(1)->getData('serviceit','temporal');
// $results = $devless->related('*')->getData('serviceit','temporal');
// $results = $devless->orderBy("name")->getData('serviceit','temporal');

var_dump($results);

