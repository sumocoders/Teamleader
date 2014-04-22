<?php
// datetime
$dateTimezone = ini_get('date.timezone');
if ($dateTimezone == '') {
    date_default_timezone_set('Europe/Brussels');
}

// parse headers
header('content-type: text/html;charset=utf-8');

// credentials
define('API_GROUP', '');
define('API_KEY', '');
