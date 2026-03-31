<?php
$log = file_get_contents('storage/logs/laravel.log');
preg_match_all('/\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\] local\.ERROR:\s*(.*?)Stack trace/is', $log, $matches);
if (!empty($matches[1])) {
    echo "LATEST ERROR: \n" . end($matches[1]);
} else {
    echo "No errors found";
}
