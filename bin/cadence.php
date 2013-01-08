<?php
/**
 * Cadence
 *
 * @author Herman J. Radtke III <hermanradtke@gmail.com>
 * @license New BSD {@link http://www.opensource.org/licenses/bsd-license.php}
 */

$date = new DateTime();
// special awesome Jira date format
$now = $date->format('Y-m-d\TH:i:s.000O');

$iniFile = $_SERVER['HOME'] . '/.jira';

if (!file_exists($iniFile)) {
    echo "Unable to locate configuration file: {$iniFile}", PHP_EOL;
}

$info = parse_ini_file($iniFile);
$host = $info['host'];
$logFile = $info['logFile'];
$username = $info['username'];
$password = $info['password'];

if (!file_exists($logFile)) {
    echo "Unable to locate work log: {$logFile}", PHP_EOL;
}

$workLog = file_get_contents($logFile);

$tasks = explode(PHP_EOL, $workLog);

foreach ($tasks as $task) {
    $parts = explode(' ', $task, 3);

    // skip empty lines
    if (!$parts[0]) {
        continue;
    }

    // hide comments
    if ($parts[0] === '#') {
        continue;
    }

    list($issue, $timeSpent, $comment) = $parts;

    $data = array(
        'timeSpent' => $timeSpent,
        'started' => $now,
        'comment' => $comment,
    );

    $url = "http://{$host}/rest/api/2/issue/{$issue}/worklog";
    $json = json_encode($data);
    $headers = array(
        'Accept: application/json',
        'Content-Type: application/json',
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_VERBOSE, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, "{$username}:{$password}");
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    $output = curl_exec($ch);
    $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    if ($httpStatus !== 201) {
        echo "Failed to record time for {$issue}", PHP_EOL;
        echo 'HTTP Response code of ' , $httpStatus, PHP_EOL;
        print_r(json_decode($output));
        continue;
    }

    echo $issue, PHP_EOL;
}
