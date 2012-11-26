<?php

$server = require __DIR__ . '/config.php';

$clients = $server->clients('/live.ogg');

foreach ($clients->sources as $mount => $source) {
    echo sprintf("%s - %d listeners\n", $mount, count($source['listeners']));
    echo str_repeat("=", 10) . "\n\n";

    foreach ($source['listeners'] as $l) {
        echo sprintf("#%d %s (%s)\n", $l['id'], $l['ip'], $l['user_agent']);
    }
}
