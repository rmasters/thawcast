<?php

$server = require __DIR__ . '/config.php';

$info = $server->stats();

var_dump($info);
