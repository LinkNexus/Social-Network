<?php

require_once 'Include/Load.php';

$user = App::getUser();
$user->restrict();

$user->displayAvatar();



