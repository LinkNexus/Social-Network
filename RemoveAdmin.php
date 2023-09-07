<?php

require_once 'Include/Load.php';

$session = App::getSession();
$link = App::getDatabase();
$superAdmin = App::getSuperAdmin();
$admin = App::getAdmin();
$validator = App::getValidator();

$admin->restrict();

if (!$admin->has('id') && !$superAdmin->has('answer')){
    App::redirect('Posts.php');
}

if ($admin->has('id')) {
    if (str_contains($session->getKey('user_infos')->status, 'admin')) {
        $result = $link->query('SELECT * FROM users WHERE id = :id', [
            'id' => App::get('id')
        ])->fetch();

        if ($admin->removeAdmin($result)){
            $session->setFlash('success', 'This User is not an Administrator of this Website anymore');
        } else {
            $session->setFlash('success', 'The Boss Approval in order to remove this User from the Administrators is awaited');
        }
    }
}

if ($superAdmin->has('answer')){
    $answer = App::get('answer');
    if (!$superAdmin->has('id')){
        App::redirect('Posts.php');
    }

    $result = $link->query('SELECT * FROM users WHERE id = :id', [
        'id' => App::get('id')
    ])->fetch();

    if ($superAdmin->approveAdminRemoval($result, $answer)){
        $session->setFlash('success', 'This User is not an Administrator of this Website anymore');
    }
}

App::redirect('Posts.php');