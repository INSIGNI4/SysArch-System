
<?php

date_default_timezone_set('Asia/Manila');

echo '<pre>';

echo 'PHP timezone: ' . date_default_timezone_get() . "\n";
echo 'PHP local time: ' . date('Y-m-d H:i:s') . "\n";
echo 'PHP UTC time: ' . gmdate('Y-m-d H:i:s') . "\n";

echo '</pre>';