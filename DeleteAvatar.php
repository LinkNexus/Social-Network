<?php

require_once 'Include/Load.php';

$user = App::getUser();
$user->restrict();
$user->deleteAvatar();
App::redirect('AvatarMenu.php');
