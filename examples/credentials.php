<?php

// define own teamleader credentials
$apiGroup = ''; // required
$apiKey = ''; // required

// username and password are required
if (empty($apiGroup) || empty($apiKey)) {
    echo 'Please define your api group and key in ' . __DIR__ . '/credentials.php';
}
