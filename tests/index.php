<?php

spl_autoload_register(function ($class) {
    $parts = explode('\\', $class);
    if ($parts[0] == 'SumoCoders' && $parts[1] == 'Teamleader') {
        unset($parts[0], $parts[1]);
        $root = __DIR__ . DIRECTORY_SEPARATOR . '..';
        $file = '';
        foreach ($parts as $part) {
            $file .= DIRECTORY_SEPARATOR . $part;
        }
        $file .= '.php';
        require_once $root . $file;
    }
});

require_once 'config.php';
require_once '../vendor/autoload.php';

include 'token.php';
include 'refreshtoken.php';
include 'expiredin.php';

use \SumoCoders\Teamleader\Teamleader;

// create instance
$teamleader = new Teamleader(CLIENT_ID,CLIENT_SECRET, USERNAME, PASSWORD, REDIRECT_URL,$token,$refresh,$text);
?>
<html>
<head>
</head>
<body>
<?php
try {

    $task = new \SumoCoders\Teamleader\Tasks\Task();
    $task->setDueDate(time());
    $task->setStartDate('2016-02-04T16:00:00+00:00');
    $task->setDescription('Dit is een test');
//    $task->setForId('d0a81ce0-3bcc-09a6-ba77-931281950386'); V2
    $task->setForId(26542982);
    $task->setFor('contact');
    $task->setTeamId(29356);
//    $task->setTaskTypeId('ef564ba8-437d-0de5-8d17-bffd231ba552'); V2
    $task->setTaskTypeId(55486);
    $task->setWorkTypeId('af6e0e3a-c1b5-0b4a-9c4b-76435e46d8be');
    $task->setPriority('B');

//    $response = $teamleader->crmGetContacts(1,1,'cedric.van.hove@vanhovegarages.be');

//    $response = $teamleader->calendarAddTask($task);
//    $response = $teamleader->getUserList();
//    $response = $teamleader->dealsGetDeals();

    $response = $teamleader->crmAddTask($task);

//    $response = $teamleader->getTaskTypes();

    $token = $teamleader->getToken();
    $var_str = var_export($token, true);
    $var = "<?php\n\n\$token = $var_str;\n\n?>";
    file_put_contents('token.php', $var);
    $refresh = $teamleader->getRefreshToken();
    $var_str = var_export($refresh, true);
    $var = "<?php\n\n\$refresh = $var_str;\n\n?>";
    file_put_contents('refreshtoken.php', $var);
    $expire = $teamleader->getExpiredDate();
    $var_str = var_export($expire, true);
    $var = "<?php\n\n\$text = $expire;\n\n?>";
    file_put_contents('expiredin.php', $var);
} catch (Exception $e) {
    var_dump($e);
}
?>
<pre>
<?=print_r($response);?>
</pre>
<a href="http://dev.teamleader.api"><button>Rerun</button></a>
</body>
</html>
