<?php

require_once __DIR__ . '/../../../../../vendor/autoload.php';

ob_start();
$html = str(file_get_contents(__DIR__ . '/ui/index.html'))
    ->replace('__TEMPEST_HYDRATION_DATA__', '')
    ->replace('// __TEMPEST_HYDRATION_SCRIPT__', file_get_contents(__DIR__ . '/ui/dist/main.js'))
    ->replace('/* __TEMPEST_HYDRATION_CSS__ */', file_get_contents(__DIR__ . '/ui/dist/main.css'))
    ->toString();
echo $html;
ob_end_flush();
