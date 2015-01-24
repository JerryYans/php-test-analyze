<?php

include_once 'system/function.php';

error_reporting(E_ALL);
ini_set("display_errors", "on");
pf_require_class("PF");

$pf = PF::getInstance();
$pf->run();