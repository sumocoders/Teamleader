<?php

//require
require_once '../../../autoload.php';
require_once 'config.php';

use \SumoCoders\Teamleader\Teamleader;

// create instance
$teamleader = new Teamleader(API_GROUP, API_KEY);

try {
    // code goes here
} catch (Exception $e) {
    var_dump($e);
}

// output
var_dump($response);
