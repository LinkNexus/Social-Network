<?php

require_once 'Include/Load.php';

$session = App::getSession();
$link = App::getDatabase();
$user = App::getUser();

$user->restrict();

App::setMode();





