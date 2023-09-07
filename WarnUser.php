<?php

require_once 'Include/Load.php';

$session = App::getSession();
$link = App::getDatabase();
$admin = App::getAdmin();
$validator = App::getValidator();

$admin->restrict();

if (!str_contains($session->getKey('user_infos')->status, 'admin') || !$admin->has('id')){
    App::redirect('Posts.php');
}

$result = $link->query('SELECT * FROM users WHERE id = :id', [
    'id' => App::get('id'),
]);

$admin->warnUser($result);


